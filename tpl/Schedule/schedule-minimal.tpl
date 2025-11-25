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
        {* Week navigation header *}
        <div class="schedule-widget-header">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <a href="?rid={$rid}&week={$PreviousWeekOffset}" 
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-chevron-left"></i>
                </a>
                
                <h5 style="margin: 0;">
                    Equipment #{$rid}<br>
                    <small>{$WeekRangeText|default:'Current Week'}</small>
                </h5>
                
                <a href="?rid={$rid}&week={$NextWeekOffset}" 
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            
            {* Today button - returns to current week *}
            {if $WeekOffset != 0}
                <div style="text-align: center; margin-bottom: 10px;">
                    <a href="?rid={$rid}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-calendar-day"></i> Today
                    </a>
                </div>
            {/if}
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