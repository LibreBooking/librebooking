预约详情:
<br/>
<br/>

开始时间: {formatdate date=$StartDate key=reservation_email}<br/>
结束时间: {formatdate date=$EndDate key=reservation_email}<br/>
<p>
    {if $Resources|default:array()|count > 1}
        <strong>资源 ({$Resources|default:array()|count}):</strong> <br />
    {else}
        <strong>资源:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>日程:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>资源 ID:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>位置:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>联系人:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>说明:</strong> {$resource.description|escape|nl2br}<br/>{/if}
        {if $resource.notes}<strong>备注:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
        {if $resource.resourceAdministrator}<strong>资源管理员:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

        {if $resource.attributeRows|default:array()|count > 0}
            <strong>资源详情:</strong><br/>
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
</p>

预约名称: {$Title}<br/>
预约说明: {$Description|nl2br}

{if count($RepeatRanges) gt 0}
	<br/>
	The reservation occurs on the following dates:
	<br/>
{/if}

{foreach from=$RepeatRanges item=date name=dates}
	{formatdate date=$date->GetBegin()}
    {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
	<br/>
{/foreach}

{if $Accessories|default:array()|count > 0}
	<br/>
	自主添加的附件列表:
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
	至少有一个预约的资源在使用之前需要批准，这个预约在经批准之前处于待审批阶段。
{/if}

{if $CheckInEnabled}
	<br/>
	预约的资源中至少有一个需要您对该预约进行Check in 或者Check out操作。
	{if $AutoReleaseMinutes != null}
		除非您在预约开始之前的 {$AutoReleaseMinutes} 分钟内对预约进行Check in 操作，否则此预约将被取消。
	{/if}
{/if}

{if !empty($ApprovedBy)}
	<br/>
	批准者: {$ApprovedBy}
{/if}


{if !empty($CreatedBy)}
	<br/>
	创建者: {$CreatedBy}
{/if}

<br/>
参考数字: {$ReferenceNumber}

<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">查看此预约</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">添加到日历</a> |
<a href="{$ScriptUrl}">登录到 CVC Rental</a>
