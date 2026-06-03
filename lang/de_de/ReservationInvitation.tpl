Hallo,<br/>
Sie wurden zu einer Reservierung in {$AppTitle} eingeladen.<br/>
<br/> 
	Reservierungsdetails:
	<br/>
	<br/>

	Beginn: {formatdate date=$StartDate key=reservation_email}<br/>
	Ende: {formatdate date=$EndDate key=reservation_email}<br/>
	{if $ResourceNames|default:array()|count > 1}
		Ressourcen:<br/>
		{foreach from=$ResourceNames item=resourceName}
			{$resourceName}<br/>
		{/foreach}
		{else}
		Ressource: {$ResourceName}<br/>
	{/if}

	{if $ResourceImage}
		<div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
	{/if}

	Titel: {$Title}<br/>
	Beschreibung: {$Description|nl2br}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		Die Reservierung gilt f&uuml;r den/die folgenden Tag(e):
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Zubeh&ouml;r:<br/>
		{foreach from=$Accessories item=accessory}
			({$accessory->QuantityReserved}) {$accessory->Name}<br/>
		{/foreach}
	{/if}

	{if $RequiresApproval}
		<br/>
		Eine oder mehrere Ressourcen ben&ouml;tigen eine Genehmigung.
		Diese Reservierung wird zur&uuml;ckgehalten, bis sie genehmigt ist.
	{/if}

	<br/>
	M&ouml;chten Sie teilnehmen? <a href="{$ScriptUrl}/{$AcceptUrl}">Ja</a> <a href="{$ScriptUrl}/{$DeclineUrl}">Nein</a>
	<br/>
	<br/>

	<a href="{$ScriptUrl}/{$ReservationUrl}">Reservierung ansehen</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Zum Kalender hinzuf&uuml;gen</a> |
	<a href="{$ScriptUrl}">{$AppTitle}-Anmeldung</a>

