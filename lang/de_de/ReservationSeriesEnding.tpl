<p>
	Hallo {$FirstName},<br/>
	Ihre Serien-Reservierung f&uuml;r {$ResourceName} endet am {formatdate date=$StartDate key=reservation_email}.
</p>

<p><strong>Reservierungsdetails</strong></p>
<p>
	<strong>Start:</<strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Ende:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Resource:</strong> {$ResourceName}<br/>
	<strong>Titel:</strong> {$Title}<br/>
	<strong>Beschreibung:</strong> {$Description|nl2br}
</p>

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Reservierung ansehen</a> | <a href="{$ScriptUrl}">{$AppTitle}-Login</a>
</p>
