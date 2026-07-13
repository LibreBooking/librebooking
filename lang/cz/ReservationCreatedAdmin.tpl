	Byly vytvořeny tyto nové rezervace:
	<br/>
	<br/>

Uživatel: {$UserName}<br/>
Začátek: {formatdate date=$StartDate key=reservation_email}<br/>
Konec: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Zdroje ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Zdroj:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>Rozvrh:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>ID zdroje:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Umístění:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Kontakt:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Popis:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Poznámky:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Správce zdroje:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Detaily zdroje:</strong><br/>
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

	Nadpis: {$Title}<br/>
    Popis: {$Description}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		Byly rezervovány všechny tyto termíny:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Příslušenství:<br/>
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
		Jedna nebo více rezervací vyžaduje schválení od administrátora. Do té doby bude Vaše rezervace ve stavu schvalování.
	{/if}

	<br/>
	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Zobrazit rezervaci v systému</a> | <a href="{$ScriptUrl}">Přihlásit se do systému</a>
