Je reservering start binnenkort.<br/>
Reserverings Details:
	<br/>
	<br/>
	Start: {formatdate date=$StartDate key=reservation_email}<br/>
	Eindigd: {formatdate date=$EndDate key=reservation_email}<br/>
	Bron: {$ResourceName}<br/>
	Titel: {$Title}<br/>
	Beschrijving: {$Description|nl2br}<br/>
<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">Bekijk deze reservering</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Voeg toe aan agenda</a> |
<a href="{$ScriptUrl}">Login in LibreBooking</a>

