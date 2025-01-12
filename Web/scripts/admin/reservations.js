function ReservationManagement(opts, approval) {
    var options = opts;

    var elements = {
        userFilter: $("#userFilter"),
        startDate: $("#formattedStartDate"),
        endDate: $("#formattedEndDate"),
        userId: $("#userId"),
        scheduleId: $("#scheduleId"),
        resourceId: $("#resourceId"),
        statusId: $('#statusId'),
        referenceNumber: $("#referenceNumber"),
        reservationTable: $("#reservationTable"),
        updateScope: $('#hdnSeriesUpdateScope'),
        resourceStatusIdFilter: $('#resourceStatusIdFilter'),
        resourceReasonIdFilter: $('#resourceReasonIdFilter'),
        title: $('#reservationTitle'),
        description: $('#reservationDescription'),
        missedCheckin: $('#missedCheckin'),
        missedCheckout: $('#missedCheckout'),

        deleteInstanceDialog: $('#deleteInstanceDialog'),
        deleteSeriesDialog: $('#deleteSeriesDialog'),

        deleteInstanceForm: $('#deleteInstanceForm'),
        deleteSeriesForm: $('#deleteSeriesForm'),

        statusForm: $('#statusForm'),
        statusDialog: $('#statusDialog'),
        statusReasons: $('#resourceReasonId'),
        statusOptions: $('#resourceStatusId'),
        statusResourceId: $('#statusResourceId'),
        statusReferenceNumber: $('#statusUpdateReferenceNumber'),

        filterButton: $('#filter'),
        clearFilterButton: $('#clearFilter'),
        filterTable: $('#filter-reservations-panel'),

        attributeUpdateForm: $('#attributeUpdateForm'),

        referenceNumberList: $(':hidden.referenceNumber'),
        inlineUpdateErrors: $('#inlineUpdateErrors'),
        inlineUpdateErrorDialog: $('#inlineUpdateErrorDialog'),

        importReservationsForm: $('#importReservationsForm'),
        importReservationsDialog: $('#importReservationsDialog'),

        deleteMultiplePrompt: $('#delete-selected'),
        deleteMultipleDialog: $('#deleteMultipleDialog'),
        deleteMultipleForm: $('#deleteMultipleForm'),
        deleteMultipleCheckboxes: $('.delete-multiple'),
        deleteMultipleSelectAll: $('#delete-all'),
        deleteMultipleCount: $('#deleteMultipleCount'),
        deleteMultiplePlaceHolder: $('#deleteMultiplePlaceHolder'),

        addTermsOfService: $('#addTermsOfService'),
        termsOfServiceDialog: $('#termsOfServiceDialog'),
        termsOfServiceForm: $('#termsOfServiceForm'),
        termsOfServiceText: $('#tos-manual'),
        termsOfServiceUrl: $('#tos-url'),
        termsOfServiceFile: $('#tos-upload-link'),
        deleteTerms: $('#deleteTerms')
    };

    var reservations = {};
    var reasons = {};

    ReservationManagement.prototype.init = function () {

        var reservationTable = $('#reservationTable_wrapper');

        reservationTable.on('click', '.update', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var td = $(this);
            if (this.tagName != 'TD') {
                td = $(this).closest('td');
            }
            setCurrentReservationInformation(td);
        });

        reservationTable.on('click', '.changeAttribute', function (e) {
            e.stopPropagation();
            $(e.target).closest('.updateCustomAttribute').find('.inlineAttribute').editable('toggle');
        });

        reservationTable.on('click', 'tr.editable', function (e) {
            if ($(e.target).hasClass('action') || $(e.target).hasClass('user') || $(e.target).closest('td').hasClass('action')) {
                e.stopPropagation();
                return;
            }
            $(this).addClass('clicked');
            viewReservation($(this).attr('data-refnum'));
        });

        reservationTable.on('click', '.delete', function (e) {
            e.preventDefault();
            e.stopPropagation();
            showDeleteReservation(getActiveReferenceNumber());
        });

        reservationTable.on('click', '.approve', function (e) {
            e.preventDefault();
            e.stopPropagation();
            approveReservation(getActiveReferenceNumber());
        });

        elements.deleteTerms.click(function (e) {
            elements.termsOfServiceForm.attr('ajaxAction', options.deleteTermsOfServiceAction);
        });

        $(".save").click(function () {
            $(this).closest('form').submit();
        });

        elements.userFilter.userAutoComplete(options.autocompleteUrl, selectUser);

        elements.userFilter.change(function () {
            if ($(this).val() == '') {
                elements.userId.val('');
            }
        });

        function setCurrentReservationInformation(td) {
            var tr = td.parents('tr');
            var referenceNumber = tr.attr('data-refnum');
            var reservationId = tr.find('.id').text();
            setActiveReferenceNumber(referenceNumber);
            setActiveReservationId(reservationId);
            elements.referenceNumberList.val(referenceNumber);
        }


        reservationTable.on('click', '.edit', function (e) {
            //This conditional prevents the edit button from working on mobile devices
            /*if ($(e.target).hasClass('action') || $(e.target).closest('td').hasClass('action')) {
                e.stopPropagation();
                return;
            }*/
            viewReservation($(this).closest('tr').attr('data-refnum'));
        });

        elements.reservationTable.find('tr.editable').each(function () {
            var seriesId = $(this).attr('data-seriesId');
            var refNum = $(this).attr('data-refnum');
            $(this).attachReservationPopup(refNum, options.popupUrl);

            /*$(this).hover(function (e) {
                $(this).find('td').addClass('highlight');
            }, function (e) {
                $(this).find('td').removeClass('highlight');
            });*/
        });

        elements.statusOptions.change(function (e) {
            populateReasonOptions(elements.statusOptions.val(), elements.statusReasons);
        });

        elements.resourceStatusIdFilter.change(function (e) {
            populateReasonOptions(elements.resourceStatusIdFilter.val(), elements.resourceReasonIdFilter);
            if (opts.resourceReasonFilter) {
                elements.resourceReasonIdFilter.val(opts.resourceReasonFilter)
            }
        });

        elements.deleteSeriesForm.find('.saveSeries').click(function () {
            var updateScope = opts.updateScope[$(this).attr('id')];
            elements.updateScope.val(updateScope);
            elements.deleteSeriesForm.submit();
        });

        elements.statusDialog.find('.saveAll').click(function () {
            $('#statusUpdateScope').val('all');
            $(this).closest('form').submit();
        });

        elements.filterButton.click(filterReservations);
        elements.clearFilterButton.click(function (e) {
            e.preventDefault();
            elements.filterTable.find('input,select,textarea').val('');
            elements.filterTable.find('checkbox').prop('checked', false);
        });

        $('#import-reservations').click(function (e) {
            this.referenceNumber = '';
            e.preventDefault();
            $('#importErrors').empty().addClass('d-none');
            $('#importResults').addClass('d-none');
            elements.importReservationsDialog.modal('show');
        });

        elements.deleteMultiplePrompt.click(function (e) {
            e.preventDefault();
            var checked = elements.reservationTable.find('.delete-multiple:checked');
            elements.deleteMultipleCount.text(checked.length);
            elements.deleteMultiplePlaceHolder.empty();
            elements.deleteMultiplePlaceHolder.append(checked.clone());
            elements.deleteMultipleDialog.modal('show');
        });

        elements.deleteMultipleSelectAll.click(function (e) {
            e.stopPropagation();
            var isChecked = elements.deleteMultipleSelectAll.is(":checked");
            elements.deleteMultipleCheckboxes.prop('checked', isChecked);
            elements.deleteMultiplePrompt.toggleClass('d-none', !isChecked);
        });

        elements.deleteMultipleCheckboxes.click(function (e) {
            e.stopPropagation();
            var numberChecked = elements.reservationTable.find('.delete-multiple:checked').length;
            var allSelected = numberChecked == elements.reservationTable.find('.delete-multiple').length;
            elements.deleteMultipleSelectAll.prop('checked', allSelected);
            elements.deleteMultiplePrompt.toggleClass('d-none', numberChecked == 0);
        });

        elements.addTermsOfService.click(function (e) {
            e.preventDefault();
            loadExistingTermsOfService();
            elements.termsOfServiceDialog.modal('show');
        });

        elements.termsOfServiceDialog.find('.toggle').click(function (e) {
            elements.termsOfServiceDialog.find('.tos-div').addClass('d-none');
            var radio = $(e.target);
            $('#' + radio.data('ref')).removeClass('d-none');
        });

        var deleteReservationResponseHandler = function (response, form) {
            form.find('.delResResponse').empty();
            if (!response.deleted) {
                form.find('.delResResponse').text(response.errors.join('<br/>'));
            }
            else {
                window.location.reload();
            }
        };

        var importHandler = function (responseText, form) {
            if (!responseText) {
                return;
            }

            $('#importCount').text(responseText.importCount);
            $('#importSkipped').text(responseText.skippedRows.length > 0 ? responseText.skippedRows.join(',') : '0');
            $('#importResult').removeClass('d-none');

            var errors = $('#importErrors');
            errors.empty();
            if (responseText.messages && responseText.messages.length > 0) {
                var messages = responseText.messages.join('</li><li>');
                errors.html('<div>' + messages + '</div>').removeClass('d-none');
            }
        };

        ConfigureAsyncForm(elements.deleteInstanceForm, getDeleteUrl, null, deleteReservationResponseHandler, { dataType: 'json' });
        ConfigureAsyncForm(elements.deleteSeriesForm, getDeleteUrl, null, deleteReservationResponseHandler, { dataType: 'json' });
        ConfigureAsyncForm(elements.statusForm, getUpdateStatusUrl, function () {
            elements.statusDialog.modal('hide');
            // todo inline update
            window.location.reload();
        });

        ConfigureAsyncForm(elements.importReservationsForm, defaultSubmitCallback(elements.importReservationsForm), importHandler);
        ConfigureAsyncForm(elements.deleteMultipleForm, defaultSubmitCallback(elements.deleteMultipleForm));
        ConfigureAsyncForm(elements.termsOfServiceForm, defaultSubmitCallback(elements.termsOfServiceForm), function () {
            var control = $("#tos-upload");
            control.val('').replaceWith(control = control.clone(true)); // 'reset' file upload
            control.trigger('change');
            elements.termsOfServiceDialog.modal('hide');
        });
    };

    ReservationManagement.prototype.addReservation = function (reservation) {
        if (!(reservation.referenceNumber in reservations)) {
            reservation.resources = {};
            reservations[reservation.referenceNumber] = reservation;
        }

        reservations[reservation.referenceNumber].resources[reservation.resourceId] = {
            id: reservation.resourceId,
            statusId: reservation.resourceStatusId,
            descriptionId: reservation.resourceStatusReasonId
        };

    };

    ReservationManagement.prototype.addStatusReason = function (id, statusId, description) {
        if (!(statusId in reasons)) {
            reasons[statusId] = [];
        }

        reasons[statusId].push({ id: id, description: description });
    };

    ReservationManagement.prototype.initializeStatusFilter = function (statusId, reasonId) {
        elements.resourceStatusIdFilter.val(statusId);
        elements.resourceStatusIdFilter.trigger('change');
        elements.resourceReasonIdFilter.val(reasonId);
    };

    var defaultSubmitCallback = function (form) {
        return function () {
            return options.submitUrl + "?action=" + form.attr('ajaxAction') + '&rn=' + getActiveReferenceNumber();
        };
    };

    function getDeleteUrl() {
        return opts.deleteUrl;
    }

    function getUpdateStatusUrl() {
        return opts.resourceStatusUrl.replace('[refnum]', getActiveReferenceNumber());
    }

    function setActiveReferenceNumber(referenceNumber) {
        this.referenceNumber = referenceNumber;
    }

    function getActiveReferenceNumber() {
        return this.referenceNumber;
    }

    function setActiveReservationId(reservationId) {
        this.reservationId = reservationId;
    }

    function showDeleteReservation(referenceNumber) {
        if (reservations[referenceNumber].isRecurring == '1') {
            elements.deleteSeriesDialog.modal('show');
        }
        else {
            elements.deleteInstanceDialog.modal('show');
        }
    }

    function populateReasonOptions(statusId, reasonsElement) {
        reasonsElement.empty().append($('<option>', { value: '', text: '-' }));

        if (statusId in reasons) {
            $.each(reasons[statusId], function (i, v) {
                reasonsElement.append($('<option>', {
                    value: v.id, text: v.description
                }));
            });
        }
    }

    function selectUser(ui, textbox) {
        elements.userId.val(ui.item.value);
        textbox.val(ui.item.label);
    }

    function filterReservations() {
        var reasonId = '';
        if (elements.resourceReasonIdFilter.val()) {
            reasonId = elements.resourceReasonIdFilter.val();
        }

        var attributes = elements.filterTable.find('[name^=psiattribute]');
        var attributeString = '';
        $.each(attributes, function (i, attribute) {
            attributeString += '&' + $(attribute).attr('name') + '=' + $(attribute).val();
        });

        var toInt = function (boolVal) {
            return boolVal ? 1 : 0;
        };

        var filterQuery = 'sd=' + elements.startDate.val() + '&ed=' + elements.endDate.val() + '&sid=' + elements.scheduleId.val() + '&rid=' + elements.resourceId.val() + '&uid=' + elements.userId.val() + '&un=' + elements.userFilter.val() + '&rn=' + elements.referenceNumber.val() + '&rsid=' + elements.statusId.val() + '&rrsid=' + elements.resourceStatusIdFilter.val() + '&rrsrid=' + reasonId + '&rtitle=' + elements.title.val() + '&rdesc=' + elements.description.val() + '&in=' + toInt(elements.missedCheckin.is(':checked')) + '&out=' + toInt(elements.missedCheckout.is(':checked'));

        window.location = document.location.pathname + '?' + encodeURI(filterQuery) + attributeString;
    }

    function viewReservation(referenceNumber) {
        window.location = options.reservationUrlTemplate.replace('[refnum]', referenceNumber);
    }

    function approveReservation(referenceNumber) {
        $.blockUI({ message: $('#approveDiv') });
        approval.Approve(referenceNumber);
    }

    function loadExistingTermsOfService() {
        ajaxGet(options.termsOfServiceUrl, null, function (data) {

            elements.termsOfServiceFile.addClass('d-none');
            elements.deleteTerms.addClass('d-none');
            elements.termsOfServiceForm.attr('ajaxAction', options.updateTermsOfServiceAction);

            if (data == null) {
                elements.termsOfServiceText.val('');
                elements.termsOfServiceUrl.val('');
                return;
            }

            var text = data.text;
            var url = data.url;
            var filename = data.filename;
            var applicability = data.applicability;

            if (text != null && text != '') {
                $('#tos_manual_radio').click();
                elements.deleteTerms.removeClass('d-none');
                elements.termsOfServiceText.val(text);
            }
            if (url != null && url != '') {
                $('#tos_url_radio').click();
                elements.deleteTerms.removeClass('d-none');
                elements.termsOfServiceUrl.val(url);
            }
            if (filename != null && filename != '') {
                $('#tos_upload_radio').click();
                elements.deleteTerms.removeClass('d-none');
                elements.termsOfServiceFile.removeClass('d-none');
            }

            if (applicability == 'REGISTRATION') {
                $('#tos_registration').click();
            }
            else {
                $('#tos_reservation').click();
            }
        });
    }

    function deleteTerms() {
        ajaxGet(options.deleteTermsOfServiceUrl, null, function () {
            elements.termsOfServiceDialog.modal('hide');
        });
    }
}
