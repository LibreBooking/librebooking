	Bokningsdetaljer:
	<br/>
	<br/>

	Användare: {$UserName}
	Er bokning startar: {formatdate date=$StartDate key=reservation_email}<br/>
	Er Bokning slutar: {formatdate date=$EndDate key=reservation_email}<br/>
	Bokning av: {$ResourceName}<br/>

	{if $ResourceImage}
		<div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
	{/if}

	Rubrik: {$Title}<br/>
	Beskrivning: {$Description}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		Bokning har gjorts följande tid / er:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $RequiresApproval}
		<br/>
		Er bokningen behöver godkännas innan den börjar gälla.  Vi ber er därför att kontrollera om denna bokning är godkänd eller inte.
	{/if}

	<br/>
	<a href="{$ScriptUrl}{$ReservationUrl}">Visa Bokning</a> | <a href="{$ScriptUrl}">Logga in i Bokningsprogrammet</a>

