<p>{$FullName},</p>

<p>Для вас создан аккаунт в {$AppTitle}:<br/>
Email: {$EmailAddress}<br/>
Имя: {$FullName}<br/>
Телефон: {$Phone}<br/>
Организация: {$Organization}<br/>
Должность: {$Position}<br/>
Пароль: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	Кем создано: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Войти в {$AppTitle}</a>
