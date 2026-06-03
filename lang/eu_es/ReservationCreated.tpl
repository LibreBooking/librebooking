	Erreserbaren xehetasunak:
	<br/>
	<br/>

	Hasiera: {formatdate date=$StartDate key=reservation_email}<br/>
	Amaiera: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Baliabideak ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Baliabidea:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>Ordutegia:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>Baliabide IDa:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Kokapena:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Kontaktua:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Deskribapena:</strong> {$resource.description|escape|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Oharrak:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Baliabide administratzailea:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Baliabidearen xehetasunak:</strong><br/>
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

	Izenburua: {$Title}<br/>
	Deskripzioa: {$Description|nl2br}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		Erreserba data hauetarako da:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Osagarria:<br/>
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
		Baliabide batek edo gehiagok onarpena behar du erabili baino lehen. Erreserba hau zain geratuko da onartua izan arte.
	{/if}

	{if !empty($ApprovedBy)}
		<br/>
		Nork onartua: {$ApprovedBy}
	{/if}

	<br/>
	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Erreserba hau ikusi</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Egutegi batera gehitu</a> |
	<a href="{$ScriptUrl}">LibreBooking-en saioa hasi</a>
