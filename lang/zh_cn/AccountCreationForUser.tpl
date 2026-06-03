<p>{$FullName},</p>

<p>在{$AppTitle}上有一个账户已为您建立，以下是该账户信息：<br/>
邮箱: {$EmailAddress}<br/>
姓名: {$FullName}<br/>
电话: {$Phone}<br/>
单位: {$Organization}<br/>
职位: {$Position}<br/>
密码: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	创建者: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">登录到{$AppTitle}</a>
