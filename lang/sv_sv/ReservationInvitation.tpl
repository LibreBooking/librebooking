	Bokningsdetaljer:
	<br/>
	<br/>

	Bokningen börjar: {formatdate date=$StartDate key=reservation_email}<br/>
	Bokningen slutar: {formatdate date=$EndDate key=reservation_email}<br/>
	Bokning: {$ResourceName}<br/>

	{if $ResourceImage}
		<div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
	{/if}

	Rubrik: {$Title}<br/>
	Beskrivning: {$Description|nl2br}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		Bokning har gjorts följande datum:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $RequiresApproval}
		<br/>
		Denna bokning behöver godkännas innan den börjar gälla.  Denna bokning är reserverad tills den är godkänd.
	{/if}

	<br/>
	Deltar? <a href="{$ScriptUrl}/{$AcceptUrl}">Ja</a> <a href="{$ScriptUrl}/{$DeclineUrl}">Nej</a>
	<br/>

	<a href="{$ScriptUrl}/{$ReservationUrl}">Visa denna bokning</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Lägg till i Outlook</a> |
	<a href="{$ScriptUrl}">Logga in i Bokningsprogrammet</a>

