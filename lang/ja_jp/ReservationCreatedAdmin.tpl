	Reservation Details:
	<br/>
	<br/>

	ユーザー: {$UserName}
	開始: {formatdate date=$StartDate key=reservation_email}<br/>
	終了: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>リソース ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>リソース:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>スケジュール:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>リソースID:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>場所:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>連絡先:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>説明:</strong> {$resource.description|escape|nl2br}<br/>{/if}
		{if $resource.notes}<strong>備考:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>リソース管理者:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>リソース詳細:</strong><br/>
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

	件名: {$Title}<br/>
	説明: {$Description}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		下記の日時で予約されました:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>備品:<br/>
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
		使用に当たって承認が必要なリソースが含まれています。 この予約申請が承認されないこともありますのでご確認ください。
	{/if}

	<br/>
	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">予約の表示</a> | <a href="{$ScriptUrl}">LibreBooking へログイン</a>

