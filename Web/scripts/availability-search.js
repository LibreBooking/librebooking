function AvailabilitySearch(options) {
    var elements = {
        searchForm: document.getElementById('searchForm'),
        availabilityResults: document.getElementById('availability-results'),
        anyResource: document.getElementById('anyResource'),
        resourceGroups: document.getElementById('resourceGroups'),
        daterange: document.querySelectorAll('input[name="AVAILABILITY_RANGE"]'),
        beginDate: document.getElementById('BeginDate'),
        endDate: document.getElementById('EndDate'),
        specificTime: document.getElementById('specificTime'),
        hours: document.getElementById('hours'),
        minutes: document.getElementById('minutes'),
        beginTime: document.getElementById('startTime'),
        endTime: document.getElementById('endTime')
    };

    var init = function () {
        if (!elements.searchForm) {
            return;
        }

        // Keep existing async submit helper compatibility while this module migrates away from jQuery.
        ConfigureAsyncForm($(elements.searchForm), function () {
            if (elements.availabilityResults) {
                elements.availabilityResults.innerHTML = '';
            }
        }, showSearchResults, null, { onBeforeSubmit: validateTimes });

        if (elements.availabilityResults) {
            elements.availabilityResults.addEventListener('click', function (e) {
                var opening = e.target.closest('.opening');
                if (!opening) {
                    return;
                }

                window.location = options.reservationUrlTemplate
                    .replace('[rid]', encodeURIComponent(opening.getAttribute('data-resourceid')))
                    .replace('[sd]', encodeURIComponent(opening.getAttribute('data-startdate')))
                    .replace('[ed]', encodeURIComponent(opening.getAttribute('data-enddate')));
            });
        }

        if (elements.anyResource) {
            elements.anyResource.addEventListener('click', function () {
                if (!elements.resourceGroups) {
                    return;
                }

                if (elements.anyResource.checked) {
                    elements.resourceGroups.value = '';
                    elements.resourceGroups.dispatchEvent(new Event('change', { bubbles: true }));
                    elements.resourceGroups.disabled = true;
                }
                else {
                    elements.resourceGroups.disabled = false;
                }
            });
        }

        elements.daterange.forEach(function (rangeInput) {
            rangeInput.addEventListener('change', function (e) {
                if (e.target.value == 'daterange') {
                    setFlatpickrDisabled(elements.beginDate, false);
                    setFlatpickrDisabled(elements.endDate, false);
                }
                else {
                    setFlatpickrDisabled(elements.beginDate, true);
                    setFlatpickrDisabled(elements.endDate, true);
                }
            });
        });

        if (elements.specificTime) {
            elements.specificTime.addEventListener('click', function () {
                if (elements.beginTime) {
                    elements.beginTime.classList.remove('is-invalid');
                }
                if (elements.endTime) {
                    elements.endTime.classList.remove('is-invalid');
                }

                if (elements.specificTime.checked) {
                    if (elements.beginTime) {
                        elements.beginTime.disabled = false;
                    }
                    if (elements.endTime) {
                        elements.endTime.disabled = false;
                    }
                    if (elements.hours) {
                        elements.hours.disabled = true;
                    }
                    if (elements.minutes) {
                        elements.minutes.disabled = true;
                    }
                }
                else {
                    if (elements.hours) {
                        elements.hours.disabled = false;
                    }
                    if (elements.minutes) {
                        elements.minutes.disabled = false;
                    }
                    if (elements.beginTime) {
                        elements.beginTime.disabled = true;
                    }
                    if (elements.endTime) {
                        elements.endTime.disabled = true;
                    }
                }
            });
        }
    };

    function setFlatpickrDisabled(input, disabled) {
        if (!input) {
            return;
        }

        var fp = input._flatpickr;

        input.disabled = disabled;

        if (fp) {
            if (fp.altInput) {
                fp.altInput.disabled = disabled;
            }

            if (disabled) {
                fp.close();
            }
        }
    }

    var showSearchResults = function (data) {
        if (!elements.availabilityResults) {
            return;
        }

        elements.availabilityResults.innerHTML = data;
        elements.availabilityResults.querySelectorAll('.resourceName').forEach(function (resourceElement) {
            var resourceId = resourceElement.getAttribute('data-resourceId');
            bindResourceDetails(resourceElement, resourceId, { position: 'left top' });
        });
    };

    var validateTimes = function () {
        if (document.getElementById('specificTime').checked) {
            return dateHelper.ValidateTimeRangeElements(
                document.getElementById('startTime'),
                document.getElementById('endTime')
            );
        }
        return true;
    };
    return { init: init };
}
