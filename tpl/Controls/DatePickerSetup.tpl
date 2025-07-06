{function name=datePickerDateFormat}
new Date({$date->Year()}, {$date->Month()-1}, {$date->Day()})
{/function}
<script type="text/javascript">
    $(function () {
        $("#{$ControlId}").{if $HasTimepicker}datetimepicker{else}datepicker{/if}({ldelim}
            numberOfMonths: {$NumberOfMonths},
            showButtonPanel: {$ShowButtonPanel},
            onSelect: {$OnSelect},
            dayNames: {$DayNames},
            dayNamesShort: {$DayNamesShort},
            dayNamesMin: {$DayNamesMin},
            dateFormat: '{$DateFormat}',
            {if $FirstDay >=0 && $FirstDay <= 6}
            firstDay: {$FirstDay},
            {/if}
            monthNames: {$MonthNames},
            monthNamesShort: {$MonthNamesShort},
            currentText: "{{translate key='Today'}|escape:'javascript'}",
            timeFormat: "{$TimeFormat}",
            altFieldTimeOnly: false,
            showWeek: {if $ShowWeekNumbers}true{else}false{/if},
            controlType: 'select'
            {if $AltId neq ''}
            ,
            altField: "#{$AltId}",
            altFormat: '{$AltFormat}'
            {/if}
            {if $DefaultDate}
            ,
            defaultDate: {datePickerDateFormat date=$DefaultDate}
            {/if}
            {if $MinDate}
            ,
            minDate: {datePickerDateFormat date=$MinDate}
            {/if}
            {if $MaxDate}
            ,
            maxDate: {datePickerDateFormat date=$MaxDate->AddDays(1)}
            {/if}
            {rdelim});

        {if $AltId neq ''}
        $("#{$ControlId}").change(function () {
            if ($(this).val() == '') {
                $("#{$AltId}").val('');
            }
            else {
                var dateVal = $("#{$ControlId}").{if $HasTimepicker}datetimepicker{else}datepicker{/if}('getDate');
                var dateString = dateVal.getFullYear() + '-' + ('0' + (dateVal.getMonth() + 1)).slice(-2) + '-' + ('0' + dateVal.getDate()).slice(-2);
                {if $HasTimepicker}
                dateString = dateString + ' ' + dateVal.getHours() + ':' + dateVal.getMinutes();
                {/if}
                $("#{$AltId}").val(dateString);
            }
        });
        {/if}

    });
</script>
