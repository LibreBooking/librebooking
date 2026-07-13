	Broneeringu detailid:
	<br/>
	<br/>

	Algus: {formatdate date=$StartDate key=reservation_email}<br/>
	Lõpp: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Ressursid ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Ressurss:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>Graafik:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>Ressursi ID:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Asukoht:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Kontakt:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Kirjeldus:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Märkused:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Ressursi administraator:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Ressursi üksikasjad:</strong><br/>
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

	Pealkiri: {$Title}<br/>
	Kirjeldus: {$Description|nl2br}<br/>
    <br/>
    Ootame Teid broneeritud ajal!<br/>
    Rannahall

	{if count($RepeatDates) gt 0}
		<br/>
		The reservation occurs on the following dates:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Accessories:<br/>
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
		One or more of the resources reserved require approval before usage.  This reservation will be pending until it is approved.
	{/if}

	{if !empty($ApprovedBy)}
		<br/>
		Approved by: {$ApprovedBy}
	{/if}

	{if !empty($CreatedBy)}
		<br/>
		Broneerija: {$CreatedBy}
	{/if}

	<br/>
	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Vaata broneeringut</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Lisa kalendrisse</a> |
	<a href="{$ScriptUrl}">Logi sisse Rannahalli kalendrisse</a>
