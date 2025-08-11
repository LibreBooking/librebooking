Nezmeškejte svůj rezervovaný termín.<br/>
Detaily rezervace:
<br/>
<br/>
Začátek: {formatdate date=$StartDate key=reservation_email}<br/>
Konec: {formatdate date=$EndDate key=reservation_email}<br/>
Zdroj: {$ResourceName}<br/>
Název: {$Title}<br/>
Popis: {$Description|nl2br}<br/>
<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">Zobrazit tuto rezervaci v systému</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Přidat do Outlook</a> |
<a href="{$ScriptUrl}">Přihlásit se do rezervačního systému</a>
