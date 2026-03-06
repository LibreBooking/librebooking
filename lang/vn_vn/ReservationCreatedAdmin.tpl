Reservation Details:
<br/>
<br/>

User: {$UserName}<br/>
{if !empty($CreatedBy)}
	Created by: {$CreatedBy}
	<br/>
{/if}
Starting: {formatdate date=$StartDate key=reservation_email}<br/>
Ending: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Tài nguyên ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Tài nguyên:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>Lịch:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>Mã tài nguyên:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Vị trí:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Liên hệ:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Mô tả:</strong> {$resource.description|escape|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Ghi chú:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Quản trị viên tài nguyên:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Chi tiết tài nguyên:</strong><br/>
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

Title: {$Title}<br/>
Description: {$Description|nl2br}

{if count($RepeatDates) gt 0}
	<br/>
	The reservation occurs on the following dates:
	<br/>
{/if}

{foreach from=$RepeatDates item=date name=dates}
	{formatdate date=$date}
	<br/>
{/foreach}

{if $Accessories|default:array()|count > 0}
	<br/>
	Accessories:
	<br/>
	{foreach from=$Accessories item=accessory}
		({$accessory->QuantityReserved}) {$accessory->Name}
		<br/>
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
	At least one of the resources reserved requires approval before usage. Please ensure that this reservation request is approved or rejected.
{/if}

{if $CheckInEnabled}
	<br/>
	At least one of the resources reserved requires that the user check in and out of the reservation.
	{if $AutoReleaseMinutes != null}
		This reservation will be cancelled unless the user checks in within {$AutoReleaseMinutes} minutes after the scheduled start time.
	{/if}
{/if}

<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">View this reservation</a> | <a href="{$ScriptUrl}">Log in to LibreBooking</a>
