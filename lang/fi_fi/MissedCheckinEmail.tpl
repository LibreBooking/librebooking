<p>Sisäänkirjaus jäi tekemättä.</p>
<p><strong>Varauksen tiedot:</strong></p>
<p>
	<strong>Alkaa:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Päättyy:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Resurssi:</strong> {$ResourceName}<br/>
	<strong>Otsikko:</strong> {$Title}<br/>
	<strong>Kuvaus:</strong> {$Description|nl2br}
</p>

{if $IsAutoRelease}
	<p>Jos et kirjaudu sisään, tämä varaus peruutetaan automaattisesti {formatdate date=$AutoReleaseTime key=reservation_email}</p>
{/if}

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Näytä varaus</a> |
	<a href="{$ScriptUrl}">Kirjaudu sovellukseen {$AppTitle}</a>
</p>
