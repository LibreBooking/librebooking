Vaša rezervácia čoskoro skončí.<br/>
Podrobnosti rezervácie:
	<br/>
	<br/>
	Začiatok: {formatdate date=$StartDate key=reservation_email}<br/>
	Koniec: {formatdate date=$EndDate key=reservation_email}<br/>
	Ihrisko: {$ResourceName}<br/>
	Názov: {$Title}<br/>
	Popis: {$Description|nl2br}<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">Zobraziť túto rezerváciu</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Pridať do kalendára</a> |
<a href="{$ScriptUrl}">Prihláste sa</a>

