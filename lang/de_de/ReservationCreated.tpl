Sie haben die folgende Reservierung erstellt.<br/>
<br/>
	Reservierungsdetails:
	<br/>
	<br/>

	Beginn: {formatdate date=$StartDate key=reservation_email}<br/>
	Ende: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Ressourcen ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Ressource:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>Zeitplan:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>Ressourcen-ID:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Standort:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Kontakt:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Beschreibung:</strong> {$resource.description|escape|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Notizen:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Ressourcenadministrator:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Ressourcendetails:</strong><br/>
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

	Titel: {$Title}<br/>
	Beschreibung: {$Description|nl2br}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		Diese Reservierung gilt f&uuml;r den/die folgenden Tag(e):
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

	{if $Attributes|default:array()|count > 0}
		<br/>
		{foreach from=$Attributes item=attribute}
			<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
		{/foreach}
	{/if}

	{if $RequiresApproval}
		<br/>
		Eine oder mehrere Ressourcen ben&ouml;tigen eine Genehmigung.
		Diese Reservierung wird zur&uuml;ckgehalten, bis sie genehmigt ist.
	{/if}

	{if !empty($ApprovedBy)}
		<br/>
		Genehmigt von: {$ApprovedBy}
	{/if}

	<br/>
	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Reservierung ansehen</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Zum Kalender hinzuf&uuml;gen</a> |
	<a href="{$ScriptUrl}">{$AppTitle}-Anmeldung</a>

