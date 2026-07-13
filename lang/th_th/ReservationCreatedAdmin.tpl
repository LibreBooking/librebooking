รายละเอียดการจอง:
<br/>
<br/>

ผู้ใช้: {$UserName}<br/>
{if !empty($CreatedBy)}
	สร้างโดย: {$CreatedBy}
	<br/>
{/if}
เริ่มต้น: {formatdate date=$StartDate key=reservation_email}<br/>
สิ้นสุด: {formatdate date=$EndDate key=reservation_email}<br/>
<p>
    {if $Resources|default:array()|count > 1}
    <strong>ทรัพยากร ({$Resources|default:array()|count}):</strong>
    <br/>
    {else}
    <strong>ทรัพยากร:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>ตารางเวลา:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>รหัสทรัพยากร:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>สถานที่:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>ผู้ติดต่อ:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>คำอธิบาย:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
        {if $resource.notes}<strong>หมายเหตุ:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
        {if $resource.resourceAdministrator}<strong>ผู้ดูแลทรัพยากร:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

        {if $resource.attributeRows|default:array()|count > 0}
            <strong>รายละเอียดทรัพยากร:</strong><br/>
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

ชื่อเรื่อง: {$Title}<br/>
รายละเอียด: {$Description|nl2br}

{if count($RepeatRanges) gt 0}
    <br/>
    การจองจะเกิดขึ้นในวันที่ดังต่อไปนี้:
    <br/>
{/if}

{foreach from=$RepeatRanges item=date name=dates}
    {formatdate date=$date->GetBegin()}
    {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
    <br/>
{/foreach}

{if $Accessories|default:array()|count > 0}
	<br/>
	อุปกรร์เสริม:
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
	ทรัพยากรที่สงวนไว้อย่างน้อยหนึ่งรายการต้องได้รับอนุมัติก่อนการใช้งาน โปรดตรวจสอบให้แน่ใจว่าคำขอจองนี้ได้รับการอนุมัติหรือปฏิเสธ
{/if}

{if $CheckInEnabled}
	<br/>
	ทรัพยากรที่สงวนไว้อย่างน้อยหนึ่งรายการกำหนดให้ผู้ใช้เช็คอินและออกจากการจอง
	{if $AutoReleaseMinutes != null}
		การจองนี้จะถูกยกเลิกเว้นแต่ผู้ใช้จะเช็คอินภายใน {$AutoReleaseMinutes} นาทีหลังจากเวลาเริ่มต้นที่กำหนดไว้
	{/if}
{/if}

<br/>
หมายเลขอ้างอิง: {$ReferenceNumber}

<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">ดูการจองนี้</a> | <a href="{$ScriptUrl}">เข้าสู่ระบบ LibreBooking</a>
