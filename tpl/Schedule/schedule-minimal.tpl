<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/librebooking.css">
    <link rel="stylesheet" href="css/schedule-widgets.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="margin: 0;">
    {assign var="rid" value=$smarty.get.rid|default:1}
    
    <div class="schedule-widget-container">
        <div class="schedule-widget-header">
            <h5 style="margin: 0;">Equipment #{$rid} - Week Availability</h5>
        </div>
        
        {if $WeekAvailability}
            <div class="schedule-week-grid">
                {foreach from=$WeekAvailability item=day}
                    <div class="schedule-day-card schedule-day-{$day.status}">
                        <div class="schedule-day-name">{$day.dayName}</div>
                        <div class="schedule-day-date">{$day.date}</div>
                        {if $day.isAvailable}
                            <span class="badge bg-success" style="font-size: 11px;">Available</span>
                        {else}
                            <span class="badge bg-danger" style="font-size: 11px;">Booked</span>
                        {/if}
                    </div>
                {/foreach}
            </div>
        {else}
            <div class="alert alert-info">
                No schedule data available
            </div>
        {/if}
        
        <div class="schedule-widget-footer">
            <a href="schedule.php?rid={$rid}" target="_parent" class="btn btn-primary">
               <i class="fas fa-calendar-alt"></i> View Full Schedule
            </a>
        </div>
    </div>
</body>
</html>