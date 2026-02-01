function AvailabilitySearch(options) {
    var elements = {
        searchForm: $('#searchForm'),
        availabilityResults: $('#availability-results'),
        anyResource: $('#anyResource'),
        resourceGroups: $('#resourceGroups'),
        // today: $('#today'),
        // tomorrow: $('#tomorrow'),
        // thisweek: $('#thisweek'),
        daterange: $('input[name="AVAILABILITY_RANGE"]'),
        beginDate: $('#beginDate'),
        endDate: $('#endDate'),
        specificTime: $('#specificTime'),
        hours: $('#hours'),
        minutes: $('#minutes'),
        beginTime: $('#startTime'),
        endTime: $('#endTime'),
    };

    var init = function () {
        ConfigureAsyncForm(elements.searchForm, function () {
            elements.availabilityResults.empty();
        }, showSearchResults, null, { onBeforeSubmit: validateTimes });

        elements.availabilityResults.on('click', '.opening', function (e) {
            var opening = $(this);
            window.location = options.reservationUrlTemplate
                .replace('[rid]', encodeURIComponent(opening.data('resourceid')))
                .replace('[sd]', encodeURIComponent(opening.data('startdate')))
                .replace('[ed]', encodeURIComponent(opening.data('enddate')));
        });

        elements.anyResource.click(function (e) {
            if (elements.anyResource.is(':checked')) {
                elements.resourceGroups.val('').change();
                elements.resourceGroups.attr('disabled', 'disabled');
            }
            else {
                elements.resourceGroups.removeAttr('disabled');
            }
        });

        elements.daterange.change(function (e) {
            if ($(e.target).val() == 'daterange') {
                elements.beginDate.removeAttr('disabled');
                elements.endDate.removeAttr('disabled');
            }
            else {
                elements.beginDate.val('').attr('disabled', 'disabled');
                elements.endDate.val('').attr('disabled', 'disabled');
            }
        });

        elements.specificTime.on('click', function (e) {
            elements.beginTime.removeClass('is-invalid');
            elements.endTime.removeClass('is-invalid');

            if (elements.specificTime.is(':checked')) {
                elements.beginTime.removeAttr('disabled');
                elements.endTime.removeAttr('disabled');
                elements.hours.attr('disabled', 'disabled');
                elements.minutes.attr('disabled', 'disabled');
            }
            else {
                elements.hours.removeAttr('disabled');
                elements.minutes.removeAttr('disabled');
                elements.beginTime.attr('disabled', 'disabled');
                elements.endTime.attr('disabled', 'disabled');
            }
        });
    };

    var showSearchResults = function (data) {
        elements.availabilityResults.empty().html(data);
        elements.availabilityResults.find('.resourceName').each(function () {
            var resourceId = $(this).attr("data-resourceId");
            $(this).bindResourceDetails(resourceId, { position: 'left top' });
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
