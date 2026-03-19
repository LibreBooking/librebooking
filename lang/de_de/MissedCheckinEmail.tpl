Hallo {$FirstName},<br/>
bitte führen Sie einen Checkin zu Ihrer Reservierung durch.<br/>
<br/>
Reservierungsdetails:<br/>
<br/>
Start: {formatdate date=$StartDate key=reservation_email}<br/>
Ende: {formatdate date=$EndDate key=reservation_email}<br/>
Resource: {$ResourceName}<br/>
Titel: {$Title}<br/>
Beschreibung: {$Description|nl2br}
{if $IsAutoRelease}
	<br/>
	Wenn Sie sich nicht einchecken, wird Ihre Reservierung automatisch um <b>{formatdate date=$AutoReleaseTime key=reservation_email}</b> gel&ouml;scht.<br/>
	Ihr Anspruch auf die Reservierung verf&auml;llt damit.
{/if}
<br/>
<br/>
<p><a href="{$ScriptUrl}/{$ReservationUrl}">Reservierung ansehen</a> | <a href="{$ScriptUrl}">{$AppTitle}-Login</a></p>
