	פרטי הזמנה:
	<br/>
	<br/>

	משתמש: {$UserName}
	החל מ-: {formatdate date=$StartDate key=reservation_email}<br/>
	עד: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>משאבים ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>משאב:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>לוח זמנים:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>מזהה משאב:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>מיקום:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>איש קשר:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>תיאור:</strong> {$resource.description|escape|nl2br}<br/>{/if}
		{if $resource.notes}<strong>הערות:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>מנהל משאב:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>פרטי המשאב:</strong><br/>
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

		כותר: {$Title}<br/>
	תאור: {$Description}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		ההזמנה קיימת בתאריכים אלו:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>אביזרים:<br/>
		{foreach from=$Accessories item=accessory}
			({$accessory->QuantityReserved}) {$accessory->Name}<br/>
		{/foreach}
	{/if}

	{if $RequiresApproval}
		<br/>
                לאחד או יותר מהמשאבים המוזמנים דרוש אישור לפני שימוש. נא לוודא אישור של בקשת הזמנה זו.
	{/if}

	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">לצפות בהזמנה זו</a> | <a href="{$ScriptUrl}">כניסה ל-LibreBooking</a>

