<script>
    (function() {

            function whenFlatpickrReady(callback, retries = 150) {
                if (window.flatpickr) {
                    callback();
                } else if (retries > 0) {
                    setTimeout(() => whenFlatpickrReady(callback, retries - 1), 150);
                } else {
                    console.warn('Flatpickr not loaded for {$ControlId}');
                }
            }

            function initFlatpickrOnce(element, config) {
                if (!element._flatpickr) {
                    flatpickr(element, config);
                }
            }

            function initDatePicker_{$ControlId}() {
            const control = document.getElementById("{$ControlId}");
            if (!control) return;

            const config = {
                inline: {$Inline|json_encode},
                mode: {$Multiple ? '"multiple"' : '"single"'},
                static: true,
                altInput: {if $AltInput|default:true}true{else}false{/if},
                altFormat: "{$AltFormat}",
                dateFormat: "{$DateFormat}",
                defaultDate:
                    {if $DefaultDate}
                        {if $Multiple}
                            {$DefaultDate|json_encode}
                        {else}
                            "{$DefaultDate}"
                        {/if}
                    {else}
                        null
                {/if},
                minDate: {if $MinDate}"{$MinDate}"{else}null{/if},
                maxDate: {if $MaxDate}"{$MaxDate}"{else}null{/if},
                enableTime: {$HasTimepicker ? 'true' : 'false'},
                time_24hr: {$Time24Hr ? 'true' : 'false'},
                onChange: {if $OnSelect}{$OnSelect}{else}null{/if},
                onClose: {if $OnClose}{$OnClose}{else}null{/if},
                allowInput: true,
                disableMobile: true,
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
            };

            const init = () => initFlatpickrOnce(control, config);

            const collapse = control.closest('.collapse');
            const modal = control.closest('.modal');

            if (modal) {
                modal.addEventListener('shown.bs.modal', init, { once: true });
                return;
            }

            if (collapse) {
                collapse.addEventListener('shown.bs.collapse', init);
                if (collapse.classList.contains('show')) {
                    init();
                }
                return;
            }

            init();
        }

        // Waiting for Flatpickr to exist
        whenFlatpickrReady(initDatePicker_{$ControlId});

    })();
</script>
