<script type="text/javascript">
    $(function() {
        let $controlId = $("#{$ControlId}");
        let $altId = $("#{$AltId}");

        {if $MinDate}
            var minDate = "{$MinDate->format('Y-m-d')}"
            $controlId.attr('min', minDate);
        {/if}

        {if $MaxDate}
            var maxDate = "{$MaxDate->AddDays(1)->format('Y-m-d')}"
            $controlId.attr('max', maxDate);
        {/if}

        {if $DefaultDate}
            var defaultDate = "{$DefaultDate->format('Y-m-d')}";
            $controlId.val(defaultDate);

        {/if}

        {if $AltId neq ''}
            $controlId.on('change', function() {
                var dateVal = $controlId.val();
                $altId.val(dateVal);
            });
        {/if}
    });
</script>
