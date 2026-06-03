Hallo,<br/>
bitte f&uuml;hren Sie einen Checkin zu Ihrer Reservierung durch.<br/>
<br/>
<p><strong>Reservierungsdetails:</strong></p>
<p>
	<strong>Start:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Ende:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Ressource:</strong> {$ResourceName}<br/>
	<strong>Titel:</strong> {$Title}<br/>
	<strong>Beschreibung:</strong> {$Description|nl2br}
</p>

{if $IsAutoRelease}
	<p>Wenn Sie sich nicht einchecken, wird Ihre Reservierung automatisch um <b>{formatdate date=$AutoReleaseTime key=reservation_email}</b> gel&ouml;scht.<br/>
	Ihr Anspruch auf die Reservierung verf&auml;llt damit.</p>
{/if}

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Reservierung ansehen</a> |
	<a href="{$ScriptUrl}">{$AppTitle}-Anmeldung</a>
</p>
