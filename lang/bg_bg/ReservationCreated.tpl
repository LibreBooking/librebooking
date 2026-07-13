	Резервационна информация:
	<br/>
	<br/>

	Начало: {formatdate date=$StartDate key=reservation_email}<br/>
	Край: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Ресурси ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Ресурс:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>График:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>Идентификатор на ресурс:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Местоположение:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Контакт:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Описание:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Бележки:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Администратор на ресурса:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Детайли за ресурса:</strong><br/>
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

	Заглавие: {$Title}<br/>
	Описание: {$Description|nl2br}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		TРезервацията се отнася за следните дати:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Аксесоари:<br/>
		{foreach from=$Accessories item=accessory}
			({$accessory->QuantityReserved}) {$accessory->Name}<br/>
		{/foreach}
	{/if}

	{if $RequiresApproval}
		<br/>
		Един или повече от ресурсите изискват одобрение преди употреба. Тази резервация ще чака докато бъде одобрена.
	{/if}

	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Разгледай тази резервация</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Добави в Outlook</a> |
	<a href="{$ScriptUrl}">Влизане в LibreBooking</a>
