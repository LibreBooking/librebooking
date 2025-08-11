Ihre Reservierung beginnt bald.<br/>
Reservierungsdetails:
	<br/>
	<br/>
	Beginn: {formatdate date=$StartDate key=reservation_email}<br/>
	Ende: {formatdate date=$EndDate key=reservation_email}<br/>
	Ressource: {$ResourceName}<br/>
	Titel: {$Title}<br/>
	Beschreibung: {$Description|nl2br}<br/>
<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">Reservierung ansehen</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Zum Kalender hinzufügen</a> |
<a href="{$ScriptUrl}">Anmelden bei LibreBooking</a>

