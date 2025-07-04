{function name=dateFormat}
    new Date({$date->Year()}, {$date->Month()-1}, {$date->Day()}).toISOString().split('T')[0]
{/function}
<script type="text/javascript">
    $(function() {
        let $controlId = $("#{$ControlId}");
        let $altId = $("#{$AltId}");

        {if $MinDate}
            var minDate = {dateFormat date=$MinDate};
            $controlId.attr('min', minDate);
        {/if}

        {if $MaxDate}
            var maxDate = {dateFormat date=$MaxDate->AddDays(1)};
            $controlId.attr('max', maxDate);
        {/if}

        {if $smarty.server.SCRIPT_NAME|basename != 'reservation.php'}
            {if $DefaultDate}
                var defaultDate = {dateFormat date=$DefaultDate};
                $controlId.val(defaultDate);
            {/if}
        {/if}

        {if $AltId neq ''}
            $controlId.on('change', function() {
                var dateVal = $controlId.val();

                if (dateVal === '') {
                    $altId.val('');
                } else {
                    var date = new Date(dateVal);
                    var dateString = date.toISOString().split('T')[0];

                    {if $HasTimepicker}
                        var time = dateVal.split('T')[1];
                        if (time) {
                            dateString += ' ' + time.split('.')[0];
                        }
                    {/if}

                    $altId.val(dateString);
                }
            });
        {/if}


    });
</script>
