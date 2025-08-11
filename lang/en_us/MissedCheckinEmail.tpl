<p>You missed your check in.</p>
<p><strong>Reservation Details:</strong></p>
<p>
	<strong>Start:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>End:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Resource:</strong> {$ResourceName}<br/>
	<strong>Title:</strong> {$Title}<br/>
	<strong>Description:</strong> {$Description|nl2br}
</p>

{if $IsAutoRelease}
	<p>If you do not check in, this reservation will be automatically cancelled at {formatdate date=$AutoReleaseTime key=reservation_email}</p>
{/if}

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">View this reservation</a> |
	<a href="{$ScriptUrl}">Log in to {$AppTitle}</a>
</p>
