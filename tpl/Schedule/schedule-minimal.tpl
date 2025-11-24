<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/librebooking.css">
    <style>
        .mini-schedule { max-height: 350px; overflow-y: auto; }
        .available { background: #d4edda; padding: 5px; margin: 2px; cursor: pointer; }
        .booked { background: #f8d7da; padding: 5px; margin: 2px; }
    </style>
</head>
<body style="margin: 0; padding: 10px;">
    {assign var="rid" value=$smarty.get.rid|default:1}
    
    <div class="text-center mb-3">
        <h5>Resource #{$rid} - This Week</h5>
    </div>
    
    <div class="mini-schedule">
        {* Check if we have real schedule data *}
        {if $BoundDates && $DailyLayout}
            <div class="alert alert-info">
                Week of {$BoundDates[0]->Format('M j')} - {$BoundDates[6]->Format('M j, Y')}
            </div>
            
            {* Try to show something from actual data *}
            {foreach from=$BoundDates item=date}
                <div class="day">
                    <strong>{$date->Format('D')}</strong>: 
                    <span class="available">Check schedule for availability</span>
                </div>
            {/foreach}
        {else}
            {* Fallback static display *}
            <div class="alert alert-warning">
                Quick view not available - click below for full schedule
            </div>
        {/if}
    </div>
    
    <div class="text-center mt-3">
        <a href="schedule.php?rid={$rid}" target="_parent" class="btn btn-primary">
            <i class="fas fa-calendar"></i> View Full Schedule
        </a>
    </div>
</body>
</html>