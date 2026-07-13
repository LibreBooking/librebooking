	Reserverings Details:
	<br/>
	<br/>

	Gebruiker: {$UserName}<br/>
	Start: {formatdate date=$StartDate key=reservation_email}<br/>
	Eindigd: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Bronnen ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Bron:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>Schema:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>Resource-ID:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Locatie:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Contactpersoon:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Beschrijving:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Notities:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Resourcebeheerder:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Resourcedetails:</strong><br/>
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
	Beschrijving: {$Description}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		De reservering komt voor op de volgende data:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Benodigdheden:<br/>
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
		E�n of meerdere bronnen die gereserveerd zijn hebben goedkeuring nodig voordat ze gebruikt kunnen worden. Accepteer of wijs de reservering af.
	{/if}

	<br/>
	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Bekijk deze reservering</a> | <a href="{$ScriptUrl}">Login in LibreBooking</a>

