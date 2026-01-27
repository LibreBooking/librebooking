let scheduleSpecificDates = [];

function Schedule(opts, resourceGroups) {
    let options = opts;
    let groupDiv = $('#resourceGroups');
    let scheduleId = $('#scheduleId');
    let multidateselect = $('#multidateselect');
    let renderingEvents = false;

    const ScheduleStandard = "0";
    const ScheduleWide = "1";
    const ScheduleTall = "2";
    const ScheduleCondensed = "3";

    this.init = function () {
        this.initUserDefaultSchedule();
        this.initRotateSchedule();
        this.initResourceFilter();
        renderEvents();
        this.initResources();
        this.initNavigation();
        addNumericalIdsToRows();

        if (scheduleOpts.autoScrollToday) {
            var today = $(".today");
            if (today && today.length > 0) {
                $('html, body').animate({
                    scrollTop: today.offset().top - 50
                }, 500);
            }
        }

        $(window).on('resize', _.debounce(function () {
            let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
            if (!isMobile) {
                renderEvents(true);
            }
        }, 1000));

        setInterval(function () {
            renderEvents(true);
        }, 300000);

        const ownerFilter = $("#ownerFilter");
        const participantFilter = $("#participantFilter");

        if (ownerFilter.length != 0) {
            ownerFilter.userAutoComplete(options.autocompleteUrl, selectOwner);
        }

        if (participantFilter.length != 0) {
            participantFilter.userAutoComplete(options.autocompleteUrl, selectParticipant);
        }
    };

    function renderEvents(clear = false) {
        $("#loading-schedule").removeClass("d-none");
        renderingEvents = true;

        if (clear) {
            $("#reservations").find("div.event, div.condensed-event, div.buffer").remove();
            $('#reservations').find('td').css('height', '40px');
        }

        let cellAdjustment = 0;
        if (opts.scheduleStyle === ScheduleStandard || opts.scheduleStyle === ScheduleTall) {
            // adjust for how different browsers calculate positions for elements with borders
            let slots = $('#reservations').find('td.slot');
            if (slots.length !== 0) {
                cellAdjustment = Math.min(1, (slots.first().position().top % 40));
            }
        }

        function attachReservationEvents(div, reservation) {
            var reservations = $('#reservations');
            var resid = reservation.ReferenceNumber;
            var pattern = 'div.reserved[data-resid="' + resid + '"]';

            div.click(function (e) {
                var reservationUrl = options.reservationUrlTemplate.replace("[referenceNumber]", resid);
                window.location = reservationUrl;
            });

            if (opts.isMobileView) {
                return;
            }

            div.hover(function (e) {
                $(pattern, reservations).addClass('hilite');
            }, function (e) {
                $(pattern, reservations).removeClass('hilite');
            });

            var qTipElement = div;

            qTipElement.qtip({
                position: {
                    my: 'bottom left', at: 'top left', effect: false, viewport: $(window)
                },

                content: {
                    text: function (event, api) {
                        $.ajax({ url: options.summaryPopupUrl, data: { id: resid } })
                            .done(function (html) {
                                api.set('content.text', html);
                            })
                            .fail(function (xhr, status, error) {
                                api.set('content.text', status + ': ' + error);
                            });

                        return 'Loading...';
                    }
                },

                show: {
                    delay: 700, effect: false,
                },

                hide: {
                    fixed: true, delay: 500
                },

                style: {
                    classes: 'qtip-light qtip-bootstrap'
                }
            });
        }

        function findClosestStart(tds, reservation, startAttribute) {
            let startTd = null;

            tds.each((i, v) => {
                const td = $(v);
                let tdMin = Number.parseInt(td.data('min'));
                let tdMax = Number.parseInt(td.data('max'));
                let resStart = Number.parseInt(reservation[startAttribute]);

                if (tdMin <= resStart && tdMax > resStart) {
                    startTd = td;
                } else if (tdMax < resStart && i + 1 < tds.length) {
                    startTd = $(tds[i + 1]);
                }
            });

            if (!startTd) {
                startTd = tds.first();
            }

            return startTd;
        }

        function findClosestEnd(tds, reservation, endAttribute) {
            let endTd = null;

            tds.each((i, v) => {
                const td = $(v);
                let tdMin = Number.parseInt(td.data('min'));
                let resEnd = Number.parseInt(reservation[endAttribute]);

                if (tdMin <= resEnd) {
                    endTd = td;
                }
            });

            if (!endTd) {
                endTd = tds.last();
            }

            return endTd;
        }

        function findStartAndEnd(res, table, startAttribute, endAttribute) {
            let startTd = table.find('td[data-resourceid="' + res.ResourceId + '"][data-min="' + res[startAttribute] + '"]:first');
            let endTd = table.find('td[data-resourceid="' + res.ResourceId + '"][data-min="' + res[endAttribute] + '"]:first');
            let calculatedAdjustment = 0;

            if (startTd.length === 0) {
                startTd = findClosestStart(table.find('td[data-resourceid="' + res.ResourceId + '"]'), res, startAttribute);
            }
            if (endTd.length === 0) {
                endTd = findClosestEnd(table.find('td[data-resourceid="' + res.ResourceId + '"]'), res, endAttribute);
                calculatedAdjustment = endTd.outerWidth();
            }
            if (startTd.length === 0 || endTd.length === 0) {
                // does not fit in this reservation table
                return;
            }

            let left = startTd.position().left;
            let height = 40;
            let width = endTd.position().left - startTd.position().left + calculatedAdjustment;
            let top = startTd.position().top;

            if (opts.scheduleStyle === ScheduleTall) {
                width = startTd.outerWidth() - cellAdjustment;
                height = endTd.position().top - startTd.position().top;
                top = startTd.position().top;
                left += cellAdjustment;
            }

            return {
                startTd,
                endTd,
                calculatedAdjustment,
                height,
                width,
                top: top - cellAdjustment,
                left: left - cellAdjustment
            };
        }

        function isReservationInTable(reservation, table) {
            const tableStartDate = Number.parseInt(table.data("min"));
            const tableEndDate = Number.parseInt(table.data("max"));
            return (
                (reservation.StartDate >= tableStartDate && reservation.StartDate < tableEndDate)
                || (reservation.EndDate > tableStartDate && reservation.EndDate <= tableEndDate)
                || (reservation.StartDate <= tableStartDate && reservation.EndDate >= tableEndDate)
            );
        }

        function findReservationStartAndEndSlots(reservation, tr_slots) {
            // Each time slot is 30 minutes. There are 48 slots in a day.
            let startSlot = null;
            let endSlot = null;
            const StartDate = Number.parseInt(reservation.StartDate);
            const EndDate = Number.parseInt(reservation.EndDate);
            tr_slots.find("td").each(function () {
                const slot = $(this);
                const slotStartDate = Number.parseInt(slot.data("min"));
                const slotEndDate = Number.parseInt(slot.data("max"));
                if (!startSlot && slotStartDate >= StartDate) {
                    startSlot = slot;
                }
                if (slotEndDate <= EndDate) {
                    endSlot = slot;
                } else {
                    return;
                }
            });

            let left = startSlot.position().left;
            let height = 40;
            let width = endSlot.position().left - startSlot.position().left + endSlot.outerWidth();
            let top = startSlot.position().top;
            return {
                startSlot,
                endSlot,
                height,
                width,
                top: top - cellAdjustment,
                left: left - cellAdjustment
            };
        }

        function createDivForReservation(reservation, startSlot, endSlot, height, width, top, left) {
            let divHeight = height;
            let className = reservation.IsReservation ? "reserved" : "unreservable";
            const mine = reservation.IsOwner ? "mine" : "";
            const past = reservation.IsPast ? "past" : "";
            const participant = reservation.IsParticipant ? "participating" : "";
            const isPending = reservation.IsPending ? "pending" : "";
            const additonalCSSClasses = reservation.AdditonalCSSClasses ? reservation.AdditonalCSSClasses.join(" ") : "";
            const isNew = reservation.IsNew ? `<span class="reservation-new">${opts.newLabel}</span>` : "";
            const isUpdated = reservation.IsUpdated ? `<span class="reservation-updated">${opts.updatedLabel}</span>` : "";
            const isDraggable = reservation.IsReservation && ((reservation.IsOwner && !reservation.IsPast) || reservation.IsAdmin);
            const draggableAttribute = isDraggable ? 'draggable="true"' : "";

            let color = reservation.BackgroundColor !== "" ? `background-color:${reservation.BackgroundColor};color:${reservation.TextColor};` : "";
            const style = `left:${left}px; top:${top}px; width:${width}px; height:${divHeight}px;`;
            const div = $(`<div
                            class="${className} ${mine} ${past} ${participant} ${isPending} ${additonalCSSClasses} event"
                            style="${style} ${color}"
                            data-resid="${reservation.ReferenceNumber}"
                            data-resourceid="${reservation.ResourceId}"
                            data-start="${startSlot.data('min')}"
                            data-end="${endSlot.data('min')}"
                            ${draggableAttribute}>${isNew} ${isUpdated} ${reservation.Label}</div>`);
            return div;
        }


        ajaxPost($("#fetchReservationsForm"), options.reservationLoadUrl, null, function (reservationList) {
            reservationList.sort((r1, r2) => {
                const resourceOrder = options.resourceOrder[r1.ResourceId] - options.resourceOrder[r2.ResourceId];
                if (resourceOrder === 0) {
                    return r1.StartDate - r2.startDate;
                }

                return resourceOrder;
            });

            if (opts.scheduleStyle === ScheduleStandard && opts.fastReservationLoad) {

                // convert our list of reservations to a mapping of arrays. keyed by the resourceId
                let resourceIdMap = new Map();
                reservationList.forEach(item => {
                    let resource_id = Number.parseInt(item.ResourceId);
                    if (!resourceIdMap.has(resource_id)) {
                        resourceIdMap.set(resource_id, []);
                    }
                    resourceIdMap.get(resource_id).push(item);
                });


                // Loop through all the days of reservations.
                $('#reservations').find(".reservations").each(function () {
                    const t = $(this);
                    let divs = $();

                    // Iterate through all the <tr class="slots"> in the `tbody`.
                    // This will be the row of reservation slots for a resource.
                    // The tr_slots object will contain the resourceId.
                    t.find("tbody").find("tr.slots").each(function () {
                        const tr_slots = $(this);
                        let resourceId = tr_slots.data("resourceid");
                        if (resourceIdMap.has(resourceId)) {
                            for (reservation of resourceIdMap.get(resourceId)) {
                                if (isReservationInTable(reservation, t)) {
                                    if (reservation.IsBuffer) {
                                        // buffers are added dynamically in grid views
                                        continue;
                                    }
                                    const startEnd = findReservationStartAndEndSlots(reservation, tr_slots);
                                    const { startSlot, endSlot, height, width, top, left } = startEnd;
                                    let div = createDivForReservation(reservation, startSlot, endSlot, height, width, top, left);
                                    if (reservation.IsReservation) {
                                        attachReservationEvents(div, reservation);
                                    }
                                    const isDraggable = reservation.IsReservation && ((reservation.IsOwner && !reservation.IsPast) || reservation.IsAdmin);
                                    if (isDraggable) {
                                        div.on(
                                            'dragstart',
                                            { referenceNumber: reservation.ReferenceNumber, resourceId: reservation.ResourceId },
                                            function (event) {
                                                div.qtip("hide");
                                                $(event.target).removeClass('clicked');
                                                const data = JSON.stringify({
                                                    referenceNumber: event.data.referenceNumber, resourceId: event.data.resourceId
                                                });
                                                event.originalEvent.dataTransfer.setData("text", data);
                                            }
                                        );
                                    }
                                    divs = divs.add(div);
                                }
                            }
                        }
                    });
                    // By adding all the `div` elements for a day at one-time is significant performance boost.
                    t.append(divs);
                });

                if (options.isReservable) {
                    initReservable();
                }
                $("#loading-schedule").addClass("d-none");
                renderingEvents = false;

                return;
            }

            //--------GET ROW AND LABELS HEIGHT TO ALLOW FULL LABEL TEXT TO SHOW IN STANDARD SCHEDULE--------
            if (opts.scheduleStyle === ScheduleStandard) {
                var trHeights = {};                         //row height to be implemented
                var trAdjusted = {};                        //check if row height has already been adjusted

                reservationList.forEach(res => {
                    $('#reservations').find(".reservations").each(function () {
                        const t = $(this);

                        //ALLOWS FULL LABEL TO BE SHOWN CORRECTLY IN ROWS WITH MULTIDAY AND CONCURRENT RESERVATIONS (BOTH AT THE SAME TIME)
                        let current_TD;

                        if (getNumberOfDaysInReservation(res.StartDate, res.EndDate) == 1) {
                            current_TD = t.find('td[data-resourceid="' + res.ResourceId + '"][data-min="' + res["StartDate"] + '"]:first');
                        } else {
                            current_TD = t.find('td[data-resourceid="' + res.ResourceId + '"]:first');
                        }

                        //----GET THE HEIGHT THAT THE SLOT LABEL WILL USE----
                        const startEnd = findStartAndEnd(res, t, "StartDate", "EndDate");

                        let slotWidth;
                        if (!startEnd) {
                            slotWidth = current_TD.width(); //WIDTH OF A SINGLE SLOT (IF THE TOTAL RESERVATION SLOTS WIDTH SOMEHOW FAILS)
                        } else {
                            slotWidth = startEnd.width;
                        }

                        let $tempElement = $('<div>')
                            .css({
                                position: 'absolute',
                                left: -9999, // Move off-screen
                                'font-size': '0.85em',
                                width: slotWidth, //schedule slot width
                                padding: 0,
                                margin: 0,
                                border: 'none',
                            })
                            .text(res.Label);

                        // Append the element to the body to get accurate dimensions
                        $('body').append($tempElement);

                        // Get the computed height
                        const labelHeight = $tempElement.height() + 5;

                        // Remove the temporary element
                        $tempElement.remove();

                        //---------------------------------------------------

                        const current_TR = current_TD.parent();

                        const currentTrId = current_TR.attr('id');

                        if ((typeof trHeights[currentTrId] !== "undefined" && trHeights[currentTrId] < labelHeight)) {
                            trHeights[currentTrId] = labelHeight;
                        }
                        else if (typeof trHeights[currentTrId] === "undefined") {
                            if (labelHeight > current_TR.height()) {
                                trHeights[currentTrId] = labelHeight;
                            } else {
                                trHeights[currentTrId] = current_TR.height();
                            }
                            trAdjusted[currentTrId] = false;
                        }
                    });
                });
            }
            //-----------------------------------------------------------------------------------------------

            reservationList.forEach(res => {
                $('#reservations').find(".reservations").each(function () {
                    const t = $(this);
                    const tableMin = Number.parseInt(t.data("min"));
                    const tableMax = Number.parseInt(t.data("max"));

                    const rendersWithin = ((res.StartDate >= tableMin && res.StartDate < tableMax) || (res.EndDate > tableMin && res.EndDate <= tableMax) || (res.StartDate <= tableMin && res.EndDate >= tableMax));

                    if (!rendersWithin) {
                        return;
                    }

                    let className = res.IsReservation ? "reserved" : "unreservable";
                    const mine = res.IsOwner ? "mine" : "";
                    const participant = res.IsParticipant ? "participating" : "";
                    const past = res.IsPast ? "past" : "";
                    const isNew = res.IsNew ? `<span class="reservation-new">${opts.newLabel}</span>` : "";
                    const isUpdated = res.IsUpdated ? `<span class="reservation-updated">${opts.updatedLabel}</span>` : "";
                    const isPending = res.IsPending ? "pending" : "";
                    const additonalCSSClasses = res.AdditonalCSSClasses ? res.AdditonalCSSClasses.join(" ") : "";
                    const isDraggable = res.IsReservation && ((res.IsOwner && !res.IsPast) || res.IsAdmin);
                    const draggableAttribute = isDraggable ? 'draggable="true"' : "";
                    let color = res.BackgroundColor !== "" ? `background-color:${res.BackgroundColor};color:${res.TextColor};` : "";

                    if (opts.scheduleStyle === ScheduleCondensed || (opts.isMobileView === "1" && opts.scheduleStyle === ScheduleStandard)) {
                        if (Number.parseInt(t.data("resourceid")) !== Number.parseInt(res.ResourceId)) {
                            return;
                        }

                        if (res.BorderColor !== "") {
                            color = `${color} border-color:${res.BorderColor};`;
                        }

                        const startsBefore = res.StartDate < tableMin;
                        const endsAfter = res.EndDate > tableMax;
                        let startTime = startsBefore ? opts.midnightLabel : res.StartTime;
                        let endTime = endsAfter ? opts.midnightLabel : res.EndTime;
                        const div = $(`<div
                                    class="${className} ${mine} ${past} ${participant} ${isPending} ${additonalCSSClasses} condensed-event"
                                    style="${color}"
                                    data-resid="${res.ReferenceNumber}">
                                    <span>${startTime}-${endTime}</span>
                                    ${isNew} ${isUpdated} ${res.Label}</div>`);

                        t.append(div);
                        if (res.IsReservation) {
                            attachReservationEvents(div, res);
                        }
                        return;
                    }

                    if (res.IsBuffer) {
                        // buffers are added dynamically in grid views
                        return;
                    }

                    const startEnd = findStartAndEnd(res, t, "StartDate", "EndDate");
                    if (!startEnd) {
                        return;
                    }
                    let { startTd, endTd, height, width, top, left } = startEnd;

                    let numberOfConflicts = 0;
                    let conflictIds = [];

                    const adjustOverlap = function () {
                        const precision = 2;
                        t.find(`div.event[data-resourceid="${res.ResourceId}"]`).each((i, div) => {
                            if ($(div).data('resid') === res.ReferenceNumber) {
                                return;
                            }
                            const divPosition = $(div).position();
                            const divLeft = Number.parseFloat(divPosition.left.toFixed(precision));
                            const divRight = Number.parseFloat((divPosition.left + $(div).width()).toFixed(precision));
                            const divTop = Number.parseFloat(divPosition.top.toFixed(precision));
                            const divBottom = Number.parseFloat((divTop + height).toFixed(precision));
                            const myLeft = Number.parseFloat(left.toFixed(precision));
                            const myTop = Number.parseFloat(top.toFixed(precision));
                            const myRight = Number.parseFloat((left + width).toFixed(precision));
                            const myBottom = Number.parseFloat((top + height).toFixed(precision));

                            let overlap = true;

                            if (divRight <= myLeft || myRight <= divLeft) {
                                overlap = false;
                            }

                            if (divTop >= myBottom || myTop >= divBottom) {
                                overlap = false;
                            }

                            if (overlap) {
                                if (opts.scheduleStyle === ScheduleStandard && typeof trHeights[currentTrId] !== "undefined") {
                                    top += trHeights[currentTrId];
                                }
                                else {
                                    top += height;
                                }
                                numberOfConflicts++;
                                adjustOverlap();
                            }
                        });
                    };

                    //----------CHANGE HEIGTH OF ROWS TO ALLOW FULL LABEL TEXT TO SHOW------------
                    if (opts.scheduleStyle === ScheduleStandard) {
                        let current_TD = t.find('td[data-resourceid="' + res.ResourceId + '"]:first');

                        var current_TR = current_TD.parent();

                        var currentTrId = current_TR.attr('id');

                        if (trAdjusted[currentTrId] === false) {                                             //no sense in setting the row height multiple times because it will always be the same so do a check and set the height once per row with reservations
                            if (current_TR.height() <= trHeights[currentTrId]) {
                                if (scheduleOpts.resourceMaxConcurrentReservations[res.ResourceId] > 1 && className != "unreservable") {    //takes into account possible existence of concurrent reservations
                                    current_TD.css('height', trHeights[currentTrId] + 40 + 'px');
                                } else {
                                    current_TD.css('height', trHeights[currentTrId] + 'px');
                                }
                            }
                            trAdjusted[currentTrId] = true;
                        }
                    }
                    //----------------------------------------------------------------------------

                    if (opts.scheduleStyle === ScheduleTall) {
                        const countConflicts = function () {
                            t.find(`div.event[data-resourceid="${res.ResourceId}"]`).each((i, div) => {
                                let divMin = Number.parseInt($(div).data('start'));
                                let divMax = Number.parseInt($(div).data('end'));
                                let resStart = Number.parseInt(res.StartDate);
                                let resEnd = Number.parseInt(res.EndDate);

                                const overlaps = resStart <= divMin && resEnd >= divMax;
                                const conflictsStart = resStart >= divMin && resStart < divMax;
                                const conflictsEnd = resEnd > divMin && resEnd <= divMax;

                                if (overlaps || conflictsStart || conflictsEnd) {
                                    numberOfConflicts++;
                                    if (!conflictIds.includes(res.ReferenceNumber)) {
                                        conflictIds.push(res.ReferenceNumber);
                                    }
                                    if (!conflictIds.includes($(div).data('resid'))) {
                                        conflictIds.push($(div).data('resid'));
                                    }
                                }
                            });
                        }

                        countConflicts();

                        top = startTd.position().top;
                        if (height === 0) {
                            height = endTd.outerHeight();
                        }
                    }
                    else {
                        adjustOverlap();
                        if (numberOfConflicts > 0) {
                            //CHANGE ROW SIZE BASED ON NUMBER OF CONCURRENT RESERVATIONS ALLOWING SPACE IN SLOT IF NOT REACHED THE MAX NUMBER
                            if (opts.scheduleStyle === ScheduleStandard) {
                                if (scheduleOpts.resourceMaxConcurrentReservations[res.ResourceId] !== numberOfConflicts + 1 && scheduleOpts.resourceMaxConcurrentReservations[res.ResourceId] > 2) {
                                    startTd.css('height', trHeights[currentTrId] * (numberOfConflicts + 2) + "px");
                                }
                                else {
                                    startTd.css('height', trHeights[currentTrId] * (numberOfConflicts + 1) + "px");
                                }
                            } else {
                                startTd.css('height', 40 * (numberOfConflicts + 1) + "px");
                            }
                        }
                    }

                    let divHeight;
                    //SLOT LABEL HEIGHT TO ALLOW FULL TEXT TO SHOW IN STANDARD SCHEDULE
                    if (opts.scheduleStyle === ScheduleStandard && typeof trHeights[currentTrId] !== "undefined") {
                        if (className == "reserved") {
                            divHeight = trHeights[currentTrId];
                        }
                        //BLACKOUTS SHOULD OCUPPY ENTIRE ROW AND THERE'S NO NEED TO WORRY ABOUT CHANGES TO ROW HEIGHT BECAUSE THEY ARE ALWAYS THE LAST TO BE PUT IN EACH
                        else if (className == "unreservable") {
                            divHeight = current_TR.height();
                        }
                    }
                    else {
                        divHeight = opts.scheduleStyle === ScheduleTall ? height : 41;
                    }
                    const style = `left:${left}px; top:${top}px; width:${width}px; height:${divHeight}px;`;
                    const div = $(`<div
                                    class="${className} ${mine} ${past} ${participant} ${isPending} ${additonalCSSClasses} event"
                                    style="${style} ${color}"
                                    data-resid="${res.ReferenceNumber}"
                                    data-resourceid="${res.ResourceId}"
                                    data-start="${startTd.data('min')}"
                                    data-end="${endTd.data('min')}"
                                    ${draggableAttribute}>${isNew} ${isUpdated} ${res.Label}</div>`);

                    if (res.IsReservation) {
                        attachReservationEvents(div, res);
                    }

                    t.append(div);

                    if (conflictIds.length > 0 && opts.scheduleStyle === ScheduleTall) {
                        width = startTd.outerWidth() / (numberOfConflicts + 1);
                        conflictIds.forEach((conflict, index) => {
                            left = startTd.position().left + (width * index) - cellAdjustment;
                            const div = t.find(`[data-resid="${conflict}"]`);
                            div.css('width', width + "px");
                            div.css('left', left + "px");
                        })
                    }

                    if (res.IsBuffered) {
                        const bufferStartEnd = findStartAndEnd(res, t, "BufferedStartDate", "BufferedEndDate");
                        if (bufferStartEnd) {
                            let bufferHeight = 41;
                            let bufferTop = top;
                            if (opts.scheduleStyle === ScheduleTall) {
                                bufferTop = bufferStartEnd.top;
                                bufferHeight = bufferStartEnd.height;
                            }

                            const style = `left:${bufferStartEnd.left}px; top:${bufferTop}px; width:${bufferStartEnd.width}px; height:${bufferHeight}px;`;
                            const bufferDiv = $(`<div
					                                    class="${past} buffer"
					                                    style="${style}"
					                                    data-resid="${res.ReferenceNumber}"
					                                    data-resourceid="${res.ResourceId}"
					                                    data-start="${startTd.data('min')}"
					                                    data-end="${endTd.data('min')}">&nbsp;</div>`);
                            t.append(bufferDiv);
                        }
                    }

                    if (isDraggable) {
                        div.on('dragstart', function (event) {
                            div.qtip("hide");
                            $(event.target).removeClass('clicked');
                            const data = JSON.stringify({
                                referenceNumber: res.ReferenceNumber, resourceId: res.ResourceId
                            });
                            event.originalEvent.dataTransfer.setData("text", data);
                        });
                    }
                });
            });

            if (options.isReservable) {
                initReservable();
            }

            $("#loading-schedule").addClass("d-none");
            renderingEvents = false;
        });
    }

    this.renderEvents = renderEvents;

    this.initResources = function () {
        $('.resourceNameSelector').each(function () {
            $(this).bindResourceDetails($(this).attr('resourceId'));
        });
    };

    this.initNavigation = function () {
        //var datePicker = $("#datepicker");
        var expandCalendar = readCookie('schedule_calendar_toggle');

        function collapse() {
            createCookie('schedule_calendar_toggle', false, 30, opts.scriptUrl);
            $('#datepicker').collapse('hide');
            $('#individualDates').collapse('hide');
        }

        function expand() {
            createCookie('schedule_calendar_toggle', true, 30, opts.scriptUrl);
            $('#datepicker').collapse('show');
            $('#individualDates').collapse('show');
        }

        if (expandCalendar == "true") {
            expand();
        } else {
            collapse();
        }

        $("#calendar_toggle").click(function (event) {
            event.preventDefault();

            if ($("#datepicker").css("display") == "none") { //Change verification method
                expand();
            } else {
                collapse();
            }
        });

        function CheckMultiDateSelect() {
            $('#individualDatesList').empty().show();
            $('.schedule_dates').hide();
        }

        multidateselect.click(function (e) {
            if (multidateselect.is(':checked')) {
                CheckMultiDateSelect();
            } else {
                $('#individualDatesList').empty().hide();
                $('.schedule_dates').show();
            }
        });

        $('#individualDatesList').on('click', '.removeSpecificDate', function () {
            var dateDiv = $(this).closest('div');
            var dateText = dateDiv.data('date');
            var index = scheduleSpecificDates.indexOf(dateText);
            scheduleSpecificDates.splice(index, 1);
            $('#individualDatesList').find('div[data-date="' + dateText + '"]').remove();

            if (scheduleSpecificDates.length == 0) {
                $('#individualDatesGo').hide();
                $('#individualDatesGo').click();
            }
        });

        $('#individualDatesGo').click(function (e) {
            e.preventDefault();

            if (multidateselect.is(':checked')) {
                var dates = scheduleSpecificDates.join(',');
                RedirectToSelf('sds', /(sds=[\d\-\,]*)/i, 'sds=' + dates);
            } else {
                RedirectToSelf('sds', /(sds=[\d\-\,]*)/i, '');
            }
        });

        if (options.specificDates.length > 0) {
            CheckMultiDateSelect();

            multidateselect.prop('checked', true);

            $.each(options.specificDates, function (i, v) {
                const parts = v.split('-');
                const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);

                AddSpecificDate(dateObj);
            });
        }

        $('#schedules').on('change', function (e) {
            // e.preventDefault();
            var scheduleId = $(this).val();

            RedirectToSelf("sid", /sid=\d+/i, "sid=" + scheduleId, function (url) {
                var x = RemoveGroupId(url);
                x = RemoveResourceId(x);
                return x;
            });
        });

        $('.schedule-dates, .alert').find('.change-date').on('click', function (e) {
            e.preventDefault();
            var year = $(this).attr('data-year');
            var month = $(this).attr('data-month');
            var day = $(this).attr('data-day');
            ChangeDate(year, month, day);
        });

        $("#print_schedule").on('click', (function (e) {
            e.preventDefault();

            const element = $("#page-schedule")[0];
            html2canvas(element).then(function (canvas) {
                const tmpImage = canvas.toDataURL("image/png");
                const newWindow = window.open("");
                $(newWindow.document.body).html("<img id='print-schedule-image' src=" + tmpImage + " style='width:100%;'></img>").ready(function () {
                    newWindow.focus();
                    newWindow.print();
                    newWindow.close();
                });
            });

        }))
    };

    this.initUserDefaultSchedule = function (anonymous) {
        var makeDefaultButton = $('#make_default');
        if (anonymous) {
            makeDefaultButton.hide();
            return;
        }

        makeDefaultButton.show();

        var defaultSetMessage = $('#defaultSetMessage');
        makeDefaultButton.click(function (e) {
            e.preventDefault();
            var scheduleId = $('#scheduleId').val();
            var changeDefaultUrl = options.setDefaultScheduleUrl.replace("[scheduleId]", scheduleId);


            $.ajax({
                url: changeDefaultUrl, success: function (data) {
                    defaultSetMessage.show().delay(5000).fadeOut();
                }
            });
        });
    };

    this.initRotateSchedule = function () {
        $('#schedule-actions .schedule-style').click(function (e) {
            e.preventDefault();

            var scheduleDisplay = $(this).attr('schedule-display');

            // Validate if schedule-display is an integer
            var isInteger = /^[0-9]+$/.test(scheduleDisplay);

            if (isInteger) {
                // If is valid cerate a normal cookie
                createCookie(opts.cookieName, parseInt(scheduleDisplay, 10), 30, opts.scriptUrl);
                window.location.reload();
            } else {
                // Otherwise create a cookie with value 0
                createCookie(opts.cookieName, 0, 30, opts.scriptUrl);
                window.location.reload();
            }
        });
    };

    this.toggleResourceFilter = function () {

        var shown = false;

        function hide() {
            shown = false;
            $('#reservations-left').addClass('d-none');
            $('#reservations').removeClass('col-md-10').addClass('col-md-12');
            $('#restore-sidebar').removeClass('bi-chevron-double-left');
            $('#restore-sidebar').addClass('bi-chevron-double-right');


            localStorage.setItem('hide-sidebar-status', true);
        }

        function show() {
            shown = true;
            $('#reservations-left').removeClass('d-none');
            $('#reservations').addClass('col-md-10').removeClass('col-md-12');
            $('#restore-sidebar').removeClass('bi-chevron-double-right');
            $('#restore-sidebar').addClass('bi-chevron-double-left');


            localStorage.removeItem('hide-sidebar-status');
        }

        function toggle() {
            if (shown) {
                hide();
            } else {
                show();
            }

            renderEvents(true);
        }

        $('.toggle-sidebar').on('click', function (e) {
            e.preventDefault();
            toggle();
        });

        var hideSidebar = localStorage.getItem('hide-sidebar-status');
        show();
        if (hideSidebar) {
            hide();
        }
    };

    function initReservable() {
        let selectingTds = false;
        const reservations = $('#reservations');

        function openReservation(startTd, endTd) {
            let sd = '';
            let ed = '';

            const start = startTd.data('start');
            if (start) {
                sd = start;
            }
            const end = endTd.data('end');
            if (end) {
                ed = end;
            }

            const link = startTd.data('href');
            window.location = link + "&sd=" + sd + "&ed=" + ed;
        }

        if (options.disableSelectable != '1') {
            let firstTd;
            let lastTd;
            let tds = [];

            function isSequentialReservation(td) {
                const resourceId = td.data('resourceid');
                const firstMinTime = Number.parseInt(firstTd.data("min"));
                const minTime = Number.parseInt(td.data('min'));
                const lastResourceId = lastTd.data('resourceid');
                const lastMinTime = Number.parseInt(lastTd.data('min'));
                const isSequential = resourceId === lastResourceId && minTime > firstMinTime && minTime > lastMinTime;
                return isSequential;
            }

            function add(td) {
                tds.push(td);
            }

            function removeIfNonSequential(td) {
                tds.forEach(i => {
                    if (Number.parseInt(i.data("min")) > Number.parseInt(td.data("min")) || i.data("resourceid") !== firstTd.data("resourceid")) {
                        i.removeClass("hilite")
                    }
                });
                tds = tds.filter(i => Number.parseInt(i.data("min")) <= Number.parseInt(td.data("min")));
            }

            reservations.on("mousedown", "td.reservable", e => {
                selectingTds = true;
                firstTd = $(e.target);
                lastTd = $(e.target);
                add(firstTd);
                return false;
            });

            reservations.on("mouseenter", "td.reservable", e => {
                let td = $(e.target);
                td.addClass("hilite");
                td.siblings('.resourcename').toggleClass('hilite');

                if (selectingTds) {
                    removeIfNonSequential(td);
                    if (isSequentialReservation(td)) {
                        add(td);
                    }
                    lastTd = td;
                    e.stopPropagation();
                    return false;
                }
            });

            reservations.on("mouseleave", "td.reservable", e => {
                let td = $(e.target);

                td.siblings('.resourcename').removeClass('hilite');
                if (selectingTds && tds.find(i => i.data("ref") === td.data("ref")) !== undefined) {
                    e.stopPropagation();
                } else {
                    td.removeClass("hilite");
                }
            });

            reservations.on("mouseup", "td.reservable", e => {
                if (selectingTds) {
                    e.stopPropagation();
                    if (Number.parseInt(firstTd.data("min")) < Number.parseInt(lastTd.data("min")) && firstTd.data("resourceid") === lastTd.data("resourceid")) {
                        openReservation(firstTd, lastTd);
                    } else {
                        reservations.find("td.hilite, td.clicked").each((i, e) => $(e).removeClass("hilite clicked"));
                    }
                }
                selectingTds = false;
            });

            reservations.find("td.reservable").on("selectstart", e => {
                return false;
            });

            makeReservationsMoveable(reservations);
        }

        /**
         reservations.delegate('.clickres:not(.reserved)', 'mouseenter', function () {
            if (selectingTds) {
                return;
            }
            $(this).siblings('.resourcename').toggleClass('hilite');
            var ref = $(this).attr('ref');
            reservations.find('td[ref="' + ref + '"]').addClass('hilite');
        });

         reservations.delegate('.clickres:not(.reserved)', 'mouseleave', function () {
            if (selectingTds) {
                return;
            }

            $(this).siblings('.resourcename').removeClass('hilite');
            var ref = $(this).attr('ref');
            reservations.find('td[ref="' + ref + '"]').removeClass('hilite');
            $(this).removeClass('hilite');
        });
         */

        reservations.on('mousedown', '.clickres', function (e) {
            $(e.target).addClass('clicked');
        });

        reservations.on('mouseup', '.clickres', function (e) {
            $(e.target).removeClass('clicked');
        });

        reservations.on('click', '.reservable', function (e) {
            openReservation($(e.target), $(e.target));
        });
    }

    this.initReservable = initReservable;

    function makeReservationsMoveable(reservations) {
        reservations.find('td.reservable').on('dragover dragleave drop', function (event) {
            event.preventDefault();
            event.stopPropagation();

            if (renderingEvents) {
                return false;
            }

            const data = JSON.parse(event.originalEvent.dataTransfer.getData("text"));
            var referenceNumber = data.referenceNumber;
            var sourceResourceId = data.resourceId;

            var targetSlot = $(event.target);

            if (event.type == 'dragover') {
                $(event.target).addClass('hilite');
            } else if (event.type == 'dragleave') {
                $(event.target).removeClass('hilite');
            } else if (event.type === 'drop') {
                renderingEvents = true;

                var droppedCell = $(event.target);
                droppedCell.addClass('dropped');
                droppedCell.html('<i class="spinner-border spinner-border-sm" aria-hidden="true"></i>');

                var targetResourceId = targetSlot.attr('data-resourceId');
                var startDate = decodeURIComponent(targetSlot.attr('data-start'));
                $('#moveStartDate').val(startDate);
                $('#moveReferenceNumber').val(referenceNumber);
                $('#moveResourceId').val(targetResourceId);
                $('#moveSourceResourceId').val(sourceResourceId);

                ajaxPost($('#moveReservationForm'), options.updateReservationUrl, null, function (updateResult) {
                    droppedCell.removeClass('dropped');
                    droppedCell.html('');

                    if (updateResult.success) {
                        renderEvents(true);
                    } else {
                        renderingEvents = false;
                        return false;
                    }
                });
            }
        });
    }

    this.initResourceFilter = function () {

        $('#advancedFilter').attr('action', opts.filterUrl);

        $('#show_all_resources').click(function (e) {
            e.preventDefault();

            groupDiv.tree('selectNode', null);

            $('#clearFilter').val('1');
            $('#resettable').find('input, select').val('');
            $(this).closest('form').submit();
        });

        groupDiv.tree({
            data: resourceGroups, saveState: 'tree' + options.scheduleId,

            onCreateLi: function (node, $li) {
                var span = $li.find('span');
                var itemName = span.text();
                var label = $('<label><input type="checkbox" name="resourceId[]"/> ' + itemName + '</label>');

                var checkbox = label.find('input');

                if (node.type == 'resource') {
                    checkbox.attr('resource-id', node.resource_id);
                    checkbox.attr('group-id', node.group_id);
                    checkbox.val(node.resource_id);
                    if (opts.selectedResources.indexOf(parseInt(node.resource_id)) !== -1) {
                        checkbox.attr('checked', true);
                        groupDiv.tree("openNode", node.parent);
                    }
                    $li.find('span').html(label);
                }
            }
        });

        groupDiv.bind('tree.click', function (event) {
            if (event.node) {
                var node = event.node;
                if (node.type != 'resource') {
                    $('#resourceGroups').find(':checkbox').attr('checked', false);
                    ChangeGroup(node);
                }
            }
        });

        this.toggleResourceFilter();
    };

    function selectOwner(ui, textbox) {
        $("#ownerId").val(ui.item.value);
        textbox.val(ui.item.label);
    }

    function selectParticipant(ui, textbox) {
        $("#participantId").val(ui.item.value);
        textbox.val(ui.item.label);
    }
}

function addNumericalIdsToRows() {
    const rows = document.getElementsByClassName('slots');

    for (let i = 0; i < rows.length; i++) {
        rows[i].id = 'row_' + (i + 1); // Set the ID to 'row_1', 'row_2', etc.
    }
}

function getNumberOfDaysInReservation(StartDate, EndDate) {
    const start = new Date(StartDate * 1000);
    const end = new Date(EndDate * 1000);

    start.setHours(0, 0, 0, 0);

    const timeDifference = end - start;
    const totalDays = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));

    return totalDays;
}

function RemoveResourceId(url) {
    if (!url) {
        url = window.location.href;
    }
    return url.replace(/&*rid[]=\d+/i, "");
}

function RemoveGroupId(url) {
    return url.replace(/&*gid=\d+/i, "");
}

function ChangeGroup(node) {
    var groupId = node.id;
    var $resourceGroups = $('#resourceGroups');

    $resourceGroups.find('input[group-id="' + groupId + '"]').click();

    _.each(node.children, function (i) {
        if (i.type == 'group') {
            ChangeGroup(i);
        }
    });
}

function AddSpecificDate(dateObj) {
    const formattedDate = dateObj.getFullYear() + '-' +
        String(dateObj.getMonth() + 1).padStart(2, '0') + '-' +
        String(dateObj.getDate()).padStart(2, '0');
    const stringDate = flatpickr.formatDate(dateObj, scheduleOpts.altFormatDate);

    if (scheduleSpecificDates.includes(formattedDate)) {
        return;
    }

    $('#individualDatesGo').show();
    scheduleSpecificDates.push(formattedDate);

    const dateItem = `<div data-date="${formattedDate}">
        ${stringDate} <i class="bi bi-x-circle text-danger icon remove removeSpecificDate"></i>
    </div>`;

    $('#individualDatesList').append(dateItem);
}

function dpDateChanged(selectedDates, inst) {
    const date = selectedDates[0];

    if ($('#multidateselect').is(':checked')) {
        AddSpecificDate(date);
    } else {
        if (date) {
            ChangeDate(
                date.getFullYear(),
                String(date.getMonth() + 1).padStart(2, '0'),
                String(date.getDate()).padStart(2, '0')
            );
        } else {
            const now = new Date();
            ChangeDate(
                now.getFullYear(),
                String(now.getMonth() + 1).padStart(2, '0'),
                String(now.getDate()).padStart(2, '0')
            );
        }
    }
}

function ChangeDate(year, month, day) {
    RedirectToSelf("sd", /sd=\d{4}-\d{1,2}-\d{1,2}/i, "sd=" + year + "-" + month + "-" + day);
}

function RedirectToSelf(queryStringParam, regexMatch, substitution, preProcess) {
    var url = window.location.href;
    var newUrl = window.location.href;

    if (preProcess) {
        newUrl = preProcess(url);
        newUrl = newUrl.replace(/&{2,}/i, "");
    }

    if (newUrl.indexOf(queryStringParam + "=") != -1) {
        newUrl = newUrl.replace(regexMatch, substitution);
    } else if (newUrl.indexOf("?") != -1) {
        newUrl = newUrl + "&" + substitution;
    } else {
        newUrl = newUrl + "?" + substitution;
    }
    newUrl = newUrl.replace("#", "");
    window.location = newUrl;
}
