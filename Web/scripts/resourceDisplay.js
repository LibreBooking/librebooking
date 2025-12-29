function ResourceDisplay(opts) {
    var options = opts;

    var elements = {
        loginForm: $('#loginForm'),
        loginButton: $('#loginButton'),
        loginError: $('#loginError'),
        loginBox: $('#login-box'),
        resourceList: $('#resourceList'),
        resourceListBox: $('#resource-list-box'),
        activateResourceDisplayForm: $('#activateResourceDisplayForm'),
        placeholder: $('#placeholder'),
        reservationPopup: $('#reservation-box-wrapper'),
        reservationForm: $('#formReserve'),
        rawStartDate: $('#availabilityStartDate'),
        startDate: $('#formattedBeginDate')
    };

    var _refreshEnabled = true;

    function populateResourceList(resources) {
        $.each(resources, function (i, resource) {
            elements.resourceList.append('<option value="' + resource.id + '">' + resource.name + '</option>');
        });

        elements.resourceListBox.removeClass('no-show');
    }

    function activateResourceDisplay(resourceId) {
        $('#waitModal').modal('show');
        ajaxPost(elements.activateResourceDisplayForm, null, null, function (data) {
            if (data.location) {
                window.location = data.location;
            }
            else {
                $('#waitModal').modal('hide');
            }
        });
    }

    ResourceDisplay.prototype.init = function () {
        elements.loginForm.submit(function (e) {
            e.preventDefault();
            ajaxPost(elements.loginForm, null, null, function (data) {
                if (data.error == true) {
                    elements.loginError.removeClass('no-show');
                }
                else {
                    elements.loginBox.addClass('no-show');
                    populateResourceList(data.resources);
                }

                hideWait();
            });
        });
    };

    ResourceDisplay.prototype.initDisplay = function (opts) {

        var url = opts.url;

        if (_.isEmpty(elements.startDate.val())) {
            elements.rawStartDate.datepicker("setDate", new Date(opts.initialDate));
        }

        refreshResource();

        setInterval(refreshResource, 60000);

        elements.placeholder.on('click', '.reservePrompt', function (e) {
            var emailAddress = $('#emailAddress');
            if (_.isEmpty(emailAddress.val())) {
                emailAddress.closest('.input-group').addClass('has-error');
                return;
            }

            elements.reservationForm.submit();
        });

        elements.placeholder.on('click', '#reservePopup', function (e) {
            pauseRefresh();
        });

        elements.placeholder.on('click', '#reserveCancel', function (e) {
            resumeRefresh();
            refreshResource();
        });

        elements.placeholder.on('submit', '#formReserve', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var beforeReserve = function () {
                $('#validationErrors').addClass('d-none');
                showWait();
            };

            var afterReserve = function (data) {
                var validationErrors = $('#validationErrors');
                if (data.success) {
                    validationErrors.find('ul').empty().addClass('d-none');
                    resumeRefresh();
                    refreshResource();
                    $('#reservation-box').modal('hide');
                }
                else {
                    var errors = data.errors ? data.errors : data.Messages;
                    validationErrors.find('ul').empty().html($.map(errors, function (item) {
                        return "<li>" + item + "</li>";
                    }));
                    validationErrors.removeClass('d-none');
                }
                hideWait();
            };

            ajaxPost($('#formReserve'), null, beforeReserve, afterReserve);
        });

        elements.placeholder.on('click', '.slot', function (e) {
            var slot = $(e.target);
            var begin = $('#beginPeriod');
            begin.val(slot.data('begin'));
            begin.trigger('change');
        });

        elements.placeholder.on('click', '#checkin', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var beforeCheckin = function () {
                showWait();
            };

            var afterCheckin = function () {
                refreshResource(hideWait);
            };

            ajaxPost($('#formCheckin'), null, beforeCheckin, afterCheckin);
        });

        elements.placeholder.on('mouseenter', '.reservable', function (e) {
            $(this).addClass('hilite');
        }).on('mouseleave', '.reservable', function () {
            $(this).removeClass('hilite');
        });

        elements.rawStartDate.on('change', function () {
            showWait();
            refreshResource(hideWait);
        });

        var beginIndex = 0;

        function pauseRefresh() {
            _refreshEnabled = false;
        }

        function resumeRefresh() {
            _refreshEnabled = true;
        }

        function refreshResource(next) {
            if (!next) {
                next = function () { };
            }
            if (!_refreshEnabled) {
                return next();
            }
            var startDate = elements.startDate.val();
            if (!_.isEmpty(startDate)) {
                url = opts.url + "&sd=" + startDate
            }

            ajaxGet(url, null, function (data) {
                if (!_refreshEnabled) {
                    return;
                }
                elements.placeholder.html(data);

                var formCheckin = $('#formCheckin');
                formCheckin.unbind('submit');

                ConfigureAsyncForm(formCheckin, null, afterCheckin, null, {
                    onBeforeSubmit: showWait,
                    onBeforeSerialize: beforeCheckin
                });

                var begin = $('#beginPeriod');
                var end = $('#endPeriod');
                beginIndex = begin.find('option:selected').index();

                begin.unbind('change');
                begin.on('change', function () {
                    var newIndex = begin.find('option:selected').index();
                    var currentEnd = end.find('option:selected').index();
                    var newSelectedEnd = newIndex - beginIndex + currentEnd;
                    var totalNumberOfEnds = end.find('option').length - 1;
                    if (newSelectedEnd < 0) {
                        newSelectedEnd = 0;
                    }
                    if (newSelectedEnd > totalNumberOfEnds) {
                        newSelectedEnd = totalNumberOfEnds;
                    }
                    end.prop('selectedIndex', newSelectedEnd);
                    beginIndex = newIndex;
                });

                if (opts.allowAutocomplete) {
                    $('#emailAddress').unbind();
                    $('#emailAddress').userAutoComplete(opts.userAutocompleteUrl, function (ui) {
                        $('#emailAddress').val(ui.item.data.Email);
                    });
                }
                next()
            });

            function beforeCheckin() {
                $('#referenceNumber').val($('td[data-checkin="1"]').attr('data-refnum'));
            }

            function afterCheckin(data) {
                refreshResource(hideWait);
            }
        }
    };

    function showWait() {
        $('#wait-modal').modal('show');
    }

    function hideWait() {
        $('#wait-modal').modal('hide');
    }

    elements.loginButton.click(function (e) {
        e.preventDefault();
        showWait();
        elements.loginForm.submit();
    });

    elements.resourceList.on('change', function () {
        var resourceId = $(this).val()

        if (resourceId != '') {
            activateResourceDisplay(resourceId);
        }
    });
}