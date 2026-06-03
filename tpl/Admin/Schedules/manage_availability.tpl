<div class="availableDates" data-has-availability="{$schedule->HasAvailability()|intval}"
    data-start-date="{formatdate date=$schedule->GetAvailabilityBegin() timezone=$timezone format='Y-m-d'}"
    data-end-date="{formatdate date=$schedule->GetAvailabilityEnd() timezone=$timezone format='Y-m-d'}">
</div>

{translate key=Available}
<span class="propertyValue fw-bold">
    {if $schedule->HasAvailability()}
        {formatdate date=$schedule->GetAvailabilityBegin() timezone=$timezone key=schedule_daily} -
        {formatdate date=$schedule->GetAvailabilityEnd() timezone=$timezone key=schedule_daily}
    {else}
        {translate key=AvailableAllYear}
    {/if}
</span>
