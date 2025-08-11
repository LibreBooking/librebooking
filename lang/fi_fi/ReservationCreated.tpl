	Varauksen tiedot:
	<br/>
	<br/>

	Alkaa: {formatdate date=$StartDate key=reservation_email}<br/>
	Päättyy: {formatdate date=$EndDate key=reservation_email}<br/>
	Resurssi: {$ResourceName}<br/>

	{if $ResourceImage}
		<div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
	{/if}

	Otsikko: {$Title}<br/>
	Kuvaus: {$Description|nl2br}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		Varaus toistuu seuraavina päivinä:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $RequiresApproval}
		<br/>
		Yksi tai useampi varattu resurssi vaatii hyväksynnän ennen käyttöä.  Ole hyvä ja varmista, hyväksytäänkö vai hylätäänkö tämä varauspyyntö.
	{/if}

	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Näytä varaus</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Lisää Outlookiin</a> |
	<a href="{$ScriptUrl}">Kirjaudu sovellukseen LibreBooking</a>


