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

            function initDatePicker_{$ControlId}() {
            const control = document.getElementById("{$ControlId}");
            if (!control) return;

            const config = {
                inline: {$Inline|json_encode},
                mode: {$Multiple ? '"multiple"' : '"single"'},
                static: true,
                altInput: {if $AltInput|default:true}true{else}false{/if},
                altFormat: {$AltFormatJson nofilter},
                dateFormat: "{$DateFormat}",
                defaultDate: {$DefaultDateJson nofilter},
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

            // Initialize Flatpickr immediately so _flatpickr is always
            // available to consumers. If the control is inside a hidden
            // ancestor, Flatpickr's setCalendarWidth() computes incorrect
            // dimensions (1px). We fix this by triggering a width
            // recalculation once all blocking ancestors become visible.
            if (!control._flatpickr) {
                flatpickr(control, config);
            }

            function getBlockingHiddenAncestor() {
                const hiddenClassAncestor = control.closest('.d-none');
                if (hiddenClassAncestor) {
                    return { type: 'class', element: hiddenClassAncestor };
                }

                const hiddenCollapse = control.closest('.collapse:not(.show)');
                if (hiddenCollapse) {
                    return { type: 'collapse', element: hiddenCollapse };
                }

                const hiddenModal = control.closest('.modal:not(.show)');
                if (hiddenModal) {
                    return { type: 'modal', element: hiddenModal };
                }

                return null;
            }

            // Force Flatpickr to recalculate calendar width via its
            // public set() API. Setting showMonths triggers the internal
            // setCalendarWidth() callback which measures offsetWidth.
            function recalcWidthIfNeeded() {
                const fp = control._flatpickr;
                if (!fp) return;

                const blockingAncestor = getBlockingHiddenAncestor();
                if (blockingAncestor) {
                    // Still hidden behind another ancestor; wait for it too.
                    waitForVisible(blockingAncestor);
                    return;
                }

                // All ancestors are now visible; recalculate width.
                fp.set('showMonths', fp.config.showMonths);
            }

            function waitForVisible(blockingAncestor) {
                if (blockingAncestor.type === 'class') {
                    const observer = new MutationObserver(function(mutations) {
                        for (const mutation of mutations) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                                if (!blockingAncestor.element.classList.contains('d-none')) {
                                    observer.disconnect();
                                    recalcWidthIfNeeded();
                                    return;
                                }
                            }
                        }
                    });
                    observer.observe(blockingAncestor.element, { attributes: true, attributeFilter: ['class'] });
                    return;
                }

                if (blockingAncestor.type === 'collapse') {
                    blockingAncestor.element.addEventListener('shown.bs.collapse', recalcWidthIfNeeded, { once: true });
                    return;
                }

                if (blockingAncestor.type === 'modal') {
                    blockingAncestor.element.addEventListener('shown.bs.modal', recalcWidthIfNeeded, { once: true });
                    return;
                }
            }

            // If the control is currently hidden, set up listeners to
            // recalculate width when it becomes visible.
            const initialBlocker = getBlockingHiddenAncestor();
            if (initialBlocker) {
                waitForVisible(initialBlocker);
            }
        }

        // Waiting for Flatpickr to exist
        whenFlatpickrReady(initDatePicker_{$ControlId});

    })();
</script>
