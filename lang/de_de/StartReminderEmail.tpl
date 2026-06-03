<p>
	Hallo,<br/>
	Ihre Reservierung beginnt bald.
</p>
<p>
	<strong>Reservierungsdetails</strong><br/>
	<br/>
	<strong>Beginn:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Ende:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Ressource:</strong> {$ResourceName}<br/>
	<strong>Titel:</strong> {$Title}<br/>
	<strong>Beschreibung:</strong> {$Description|nl2br}
</p>

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Reservierung ansehen</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Zum Kalender hinzuf&uuml;gen</a> |
	<a href="{$ScriptUrl}">{$AppTitle}-Anmeldung</a>
</p>

