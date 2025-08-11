Va�a rezervacija uskoro istice.<br/>
Detalji o rezervaciji:
	<br/>
	<br/>
	Pocetak: {formatdate date=$StartDate key=reservation_email}<br/>
	Kraj: {formatdate date=$EndDate key=reservation_email}<br/>
	Teren: {$ResourceName}<br/>
	Naziv: {$Title}<br/>
	Opis: {$Description|nl2br}<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">Pregledaj rezervaciju</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Dodaj u kalendar</a> |
<a href="{$ScriptUrl}">Ulogiraj se</a>

