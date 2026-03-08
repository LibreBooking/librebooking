<p>{$FullName},</p>

<p>บัญชีสำหรับ {$AppTitle} ถูกสร้างขึ้นสำหรับคุณโดยมีรายละเอียดดังต่อไปนี้:<br/>
Email: {$EmailAddress}<br/>
ชื่อ: {$FullName}<br/>
โทรศัพท์: {$Phone}<br/>
หน่วยงาน: {$Organization}<br/>
ตำแหน่ง: {$Position}<br/>
รหัสผ่าน: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	สร้างโดย: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">เข้าสู่ระบบเพื่อ {$AppTitle}</a>
