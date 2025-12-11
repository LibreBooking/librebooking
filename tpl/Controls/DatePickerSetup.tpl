{function name=datePickerDateFormat}
    new Date({$date->Year()}, {$date->Month()-1}, {$date->Day()})
{/function}
<script>
    function initFlatpickr(selector, config) {
        flatpickr(selector, config);
    }

    $(document).ready(function() {
    const hasTimepicker = {if $HasTimepicker}true{else}false{/if};
    const dateFormat = "{$DateFormat}" + (hasTimepicker ? " {$TimeFormat}" : "");

    const config = {
        inline: {if $Inline|default:false}true{else}false{/if},
        enableTime: hasTimepicker,
        noCalendar: {if $NoCalendar|default:false}true{else}false{/if},
        dateFormat: dateFormat,
        locale: {
            weekdays: {
                shorthand: {$DayNamesShort},
                longhand: {$DayNames}
            },
            months: {
                shorthand: {$MonthNamesShort},
                longhand: {$MonthNames}
            },
            {if $FirstDay >= 0 && $FirstDay <= 6}
                firstDayOfWeek: {$FirstDay},
            {/if}
        },
        showMonths: {$NumberOfMonths|default:1},
        weekNumbers: {if $ShowWeekNumbers}true{else}false{/if},
        defaultDate: {if $DefaultDate}{datePickerDateFormat date=$DefaultDate}{else}null{/if},
        minDate: {if $MinDate}{datePickerDateFormat date=$MinDate}{else}null{/if},
        maxDate: {if $MaxDate}{datePickerDateFormat date=$MaxDate->AddDays(1)}{else}null{/if},
        onChange: {$OnSelect},
    };

    {if $AltId neq ''}
        config.altInput = true;
        config.altFormat = "{$AltFormat|escape:'javascript'}";
    {/if}

    // If the control is inside a collapse
    const $collapse = $("#{$ControlId}").closest('.collapse');
    if ($collapse.length > 0) {
        $collapse.on('shown.bs.collapse', function() {
            initFlatpickr("#{$ControlId}", config);
        });
        // If the collapse is already visible upon loading
        if ($collapse.hasClass('show')) {
            initFlatpickr("#{$ControlId}", config);
        }
    } else {
        // If not collapsed, initialize normally
        initFlatpickr("#{$ControlId}", config);
    }

    {if $AltId neq ''}
        $("#{$ControlId}").on('change', function () {
        if ($(this).val() === '') {
            $("#{$AltId}").val('');
        }
        });
    {/if}
    });
</script>