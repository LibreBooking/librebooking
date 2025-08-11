<p>Your reservation is starting soon.</p>
<p><strong>Reservation Details:</strong></p>
<p>
	<strong>Start:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>End:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Resource:</strong> {$ResourceName}<br/>
	<strong>Title:</strong> {$Title}<br/>
	<strong>Description:</strong> {$Description|nl2br}
</p>

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">View this reservation</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Add to Calendar</a> |
	<a href="{$ScriptUrl}">Log in to {$AppTitle}</a>
</p>
