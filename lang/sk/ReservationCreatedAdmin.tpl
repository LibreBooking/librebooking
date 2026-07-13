	Boli vytvorené tieto nové rezervácie:
	<br/>
	<br/>

    Užívateľ: {$UserName}<br/><br/>
	Nadpis: {$Title}<br/>
    Popis: {$Description|nl2br}<br/><br/>
	Začiatok: {formatdate date=$StartDate key=reservation_email}<br/>
	Koniec: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Zdroje ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Zdroj:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>Rozvrh:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>ID zdroja:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Umiestnenie:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Kontakt:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Popis:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Poznámky:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Správca zdroja:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Podrobnosti zdroja:</strong><br/>
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

	{if count($RepeatDates) gt 0}
		<br/>
		Boli rezervované všetky tieto termíny:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Príslušenstvo:<br/>
		{foreach from=$Accessories item=accessory}
			({$accessory->QuantityReserved}) {$accessory->Name}<br/>
		{/foreach}
	{/if}
	{if $RequiresApproval}
		<br/>
		Jedna, alebo viac rezervácií si vyžaduje schválenie od administrátora. Do tej doby bude Vaša rezervácia v stave schvalovania.
	{/if}

	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Zobraziť rezerváciu v systéme</a> | <a href="{$ScriptUrl}">Prihlásiť sa do systému</a>

