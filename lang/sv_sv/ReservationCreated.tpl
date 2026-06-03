	Bokningsdetaljer:
	<br/>
	<br/>

	Er tid börjar: {formatdate date=$StartDate key=reservation_email}<br/>
	Välkommen till oss 10min innan bokad tid.<br/>

	Slutar: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Resurser ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Resurs:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>Schema:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>Resurs-ID:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Plats:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Kontakt:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Beskrivning:</strong> {$resource.description|escape|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Anteckningar:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Resursadministratör:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Resursdetaljer:</strong><br/>
			<table cellpadding="4" cellspacing="0" border="1" style="border-collapse: collapse; margin-top: 4px;">
				{foreach from=$resource.attributeRows item=row}
					<tr>
						<th scope="row" valign="top" style="text-align: left;"><strong>{$row.label|escape}</strong></th>
						<td valign="top">{$row.displayValue|escape|nl2br}</td>
					</tr>
				{/foreach}
			</table>
		{/if}

		{if $resource.image}
			<div class="resource-image"><img alt="{$resource.name|escape}" src="{$ScriptUrl}/{$resource.image|escape}"/></div>
		{/if}

		{if !$smarty.foreach.resourceLoop.last}<br/>{/if}
	{/foreach}

	<br/>

	<br/>
	Rubrik: {$Title}<br/>
	<br/>
	Beskrivning: {$Description|nl2br}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		Ni har reserverat följande tid / er:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $RequiresApproval}
		<br/>
		Innan er reservation övergår i bokning behöver den godkännas först.
	{/if}

	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Visa Bokning</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Lägg till i Outlook</a> |
	<a href="{$ScriptUrl}">Logga in i Bokningsprogrammet</a>

