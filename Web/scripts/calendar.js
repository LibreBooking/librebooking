function Calendar(opts) {
    var _options = opts;
    var _fullCalendar;
    var dateVar = null;

    var dayDialog = $('#dayDialog');

    var elements = {
        loadingIndicator: $('#loadingIndicator'),
        moveReservationForm: $('#moveReservationForm'),
        moveReferenceNumber: $('#moveReferenceNumber'),
        moveStartDate: $('#moveStartDate'),
        moveErrorOk: $('#moveErrorOk'),
        moveErrorDialog: $('#moveErrorDialog'),
        moveErrorsList: $('#moveErrorsList')
    };

    Calendar.prototype.init = function () {

        function bindReservationPopup(target, refNum, detailsUrl) {
            window.attachReservationPopup(target, refNum, detailsUrl);
        }

        function showLoadingIndicator() {
            elements.loadingIndicator.removeClass('d-none');
        }

        function hideLoadingIndicator() {
            elements.loadingIndicator.addClass('d-none');
        }

        _fullCalendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next,today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: _options.todayText,
                month: _options.monthText,
                week: _options.weekText,
                day: _options.dayText
            },
            allDaySlot: false,
            weekNumbers: _options.showWeekNumbers,
            defaultView: _options.view,
            defaultDate: _options.defaultDate,
            eventSources: [{
                url: _options.eventsUrl,
                type: 'GET',
                data: _options.eventsData
            }],
            eventRender: function (event, element, view) {
                if (!_.isEmpty(event.id)) {
                    bindReservationPopup(element.get(0), event.id);
                    var moment = view.start;
                    if (view.type == "month") {
                        moment = view.currentRange.start;
                    }
                    var redirect = _options.returnTo + encodeURIComponent('?ct=' + view.name + '&start=' + moment.year() + '-' + (moment.month() + 1) + '-' + moment.date())
                    element.attr('href', event.url.replace('[redirect]', redirect));
                }
            },
            dayClick: dayClick,
            dayNames: _options.dayNames,
            dayNamesShort: _options.dayNamesShort,
            monthNames: _options.monthNames,
            monthNamesShort: _options.monthNamesShort,
            timeFormat: _options.timeFormat,
            firstDay: _options.firstDay,
            views: {
                agendaDay: { slotLabelFormat: _options.timeFormat },
                agendaWeek: { slotLabelFormat: _options.timeFormat }
            },
            slotLabelFormat: _options.timeFormat,
            loading: function (isLoading) {
                if (isLoading) {
                    showLoadingIndicator();
                }
                else {
                    hideLoadingIndicator();
                }
            },
            eventDrop: function (event, delta, revertFunc) {
                var handleMoveResponse = function (result) {
                    hideLoadingIndicator();
                    if (result.errors.length > 0) {
                        revertFunc();

                        var messages = result.errors.join('</li><li>');
                        messages = '<li>' + messages + '</li>';
                        elements.moveErrorsList.empty().append(messages);
                        elements.moveErrorDialog.modal('show');
                    }
                };

                elements.moveReferenceNumber.val(event.id);
                elements.moveStartDate.val(event.start.format('YYYY-MM-DD HH:mm'));
                ajaxPost(elements.moveReservationForm, _options.moveReservationUrl, showLoadingIndicator, handleMoveResponse);
            }
        });

        $('.fc-widget-content').hover(
            function () {
                $(this).addClass('hover');
            },

            function () {
                $(this).removeClass('hover');
            }
        );

        $(".reservation").each(function (index, value) {
            var refNum = $(this).attr('refNum');
            bindReservationPopup(value, refNum);
        });

        $('#calendarFilter').on('change', function () {
            var sid = '';
            var rid = '';
            var gid = getQueryStringValue('gid');

            if ($(this).find(':selected').hasClass('schedule')) {
                sid = $(this).val().replace('s', '');
            }
            else {
                sid = $(this).find(':selected').prevAll('.schedule').val().replace('s', '');
                rid = $(this).val().replace('r', '');
            }

            _options.eventsData.sid = sid;
            _options.eventsData.rid = rid;
            _options.eventsData.gid = gid;
            _options.dayClickUrl = _options.dayClickUrlTemplate.replace('[sid]', sid).replace('[rid]', rid).replace('[gid]', gid);
            _options.reservationUrl = _options.reservationUrlTemplate.replace('[sid]', sid).replace('[rid]', rid).replace('[gid]', gid);
            _fullCalendar.fullCalendar('refetchEvents');

            rebindSubscriptionData(rid, sid, gid);
        });

        $('#subscriptionContainer').on('click', '#turnOffSubscription', function (e) {
            e.preventDefault();
            PerformAsyncAction($(this),
                function () {
                    return opts.subscriptionDisableUrl;
                },
                null,
                function () {
                    return rebindSubscriptionData('', '', '')
                }
            );
        });

        $('#subscriptionContainer').on('click', '#turnOnSubscription', function (e) {
            e.preventDefault();
            PerformAsyncAction($(this), function () {
                return opts.subscriptionEnableUrl;
            },
                null,
                function () {
                    return rebindSubscriptionData('', '', '');
                }
            );
        });

        dayDialog.find('a').click(function (e) {
            e.preventDefault();
        });

        $('#dayDialogCancel').click(function (e) {
            dayDialog.addClass('d-none');
        });

        $('#dayDialogView').click(function (e) {
            drillDownClick();
        });

        $('#dayDialogCreate').click(function (e) {
            openNewReservation();
        });

        $('#showResourceGroups').click(function (e) {
            e.preventDefault();

            var resourceGroupsContainer = $('#resourceGroupsContainer');

            if (resourceGroupsContainer.is(':visible')) {
                resourceGroupsContainer.hide();
            }
            else {
                if (!resourceGroupsContainer.data('positionSet')) {
                    resourceGroupsContainer.position({ my: 'left top', at: 'right bottom', of: '#showResourceGroups' })
                }
                resourceGroupsContainer.data('positionSet', true);
                resourceGroupsContainer.show();
            }
        });

        elements.moveErrorOk.click(function (e) {
            e.preventDefault();
            elements.moveErrorDialog.modal('hide');
        });



        function selectOwner(ui, textbox) {
            textbox.val(ui.item.label);
            _options.eventsData.uid = ui.item.value;
            _fullCalendar.fullCalendar('refetchEvents');
        }

        function selectParticipant(ui, textbox) {
            textbox.val(ui.item.label);
            _options.eventsData.pid = ui.item.value;
            _fullCalendar.fullCalendar('refetchEvents');
        }

        const ownerFilter = $("#ownerFilter");
        const participantFilter = $("#participantFilter");

        if (ownerFilter.length !== 0) {
            ownerFilter.userAutoComplete(opts.autocompleteUrl, selectOwner);
        }

        if (participantFilter.length !== 0) {
            participantFilter.userAutoComplete(opts.autocompleteUrl, selectParticipant);
        }

        $("#clearUserFilter").on('click', function (e) {
            _options.eventsData.uid = null;
            _options.eventsData.pid = null;
            ownerFilter.val('');
            participantFilter.val('');
            _fullCalendar.fullCalendar('refetchEvents');
        });
    };

    Calendar.prototype.bindResourceGroups = function (resourceGroups, selectedNode) {
        if (resourceGroups.length == 0) {
            $('#showResourceGroups').hide();
            return;
        }

        // this is copied out of schedule.js, so this needs to be fixed

        function ChangeGroup(groupId) {
            RedirectToSelf('gid', /gid=\d+/i, "gid=" + groupId, RemoveResourceId);
        }

        function ChangeResource(resourceId) {
            RedirectToSelf('rid', /rid=\d+/i, "rid=" + resourceId, RemoveGroupId);
        }

        function RemoveResourceId(url) {
            if (!url) {
                url = window.location.href;
            }
            return url.replace(/&*rid=\d+/i, "");
        }

        function RemoveGroupId(url) {
            return url.replace(/&*gid=\d+/i, "");
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
            }
            else if (newUrl.indexOf("?") != -1) {
                newUrl = newUrl + "&" + substitution;
            }
            else {
                newUrl = newUrl + "?" + substitution;
            }

            newUrl = newUrl.replace("#", "");

            window.location = newUrl;
        }

        var groupDiv = $("#resourceGroups");
        groupDiv.tree({
            data: resourceGroups,
            saveState: 'resourceCalendar',

            onCreateLi: function (node, $li) {
                if (node.type == 'resource') {
                    $li.addClass('group-resource');
                }
            }
        });

        groupDiv.bind(
            'tree.select',
            function (event) {
                if (event.node) {
                    var node = event.node;
                    if (node.type == 'resource') {
                        ChangeResource(node.resource_id);
                    }
                    else {
                        ChangeGroup(node.id);
                    }
                }
            });

        if (selectedNode) {
            groupDiv.tree('openNode', groupDiv.tree('getNodeById', selectedNode));
        }
    };

    var dayClick = function (date, jsEvent, view) {
        dateVar = date;

        if (!opts.reservable) {
            drillDownClick();
            return;
        }

        if (view.name.indexOf("Day") > 0) {
            handleTimeClick();
        }
        else {
            //dayDialog.dialog({modal: false, height: 70, width: 'auto'});
            dayDialog.removeClass('d-none');
            dayDialog.position({
                my: 'left bottom',
                at: 'left top',
                of: jsEvent
            });
        }
    };

    var handleTimeClick = function () {
        openNewReservation();
    };

    var rebindSubscriptionData = function (rid, sid, gid) {
        var url = _options.getSubscriptionUrl + '&rid=' + rid + '&sid=' + sid + '&gid=' + gid;
        ajaxGet(url, function () {
        }, function (response) {
            $('#calendarSubscription').html(response);
        });
    };

    var drillDownClick = function () {
        var month = dateVar.month() + 1;
        var url = _options.dayClickUrl;
        url = url + '&start=' + dateVar.year() + '-' + month + '-' + dateVar.date();

        window.location = url;
    };

    var openNewReservation = function () {
        var view = _fullCalendar.fullCalendar('getView');
        var end = moment(dateVar).add(30, 'minutes');
        var year = dateVar.year();
        var month = dateVar.month() + 1;
        var day = dateVar.date();

        var url = _options.reservationUrl +
            "&sd=" + getUrlFormattedDate(dateVar) +
            "&ed=" + getUrlFormattedDate(end) +
            "&redirect=" + _options.returnTo + encodeURIComponent('?ct=' + view.name + '&start=' + year + '-' + month + '-' + day)
            ;

        window.location = url;
    };

    var getUrlFormattedDate = function (d) {
        var month = d.month() + 1;
        return encodeURI(d.year() + "-" + month + "-" + d.date() + " " + d.hour() + ":" + d.minute());
    };
}
