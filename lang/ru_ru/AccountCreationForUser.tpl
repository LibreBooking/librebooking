<p>{$FullName},</p>

<p>Для вас была создана учетная запись в {$AppTitle} со следующими данными:<br/>
Email: {$EmailAddress}<br/>
Имя: {$FullName}<br/>
Телефон: {$Phone}<br/>
Организация: {$Organization}<br/>
Должность: {$Position}<br/>
Пароль: {$Password}</p>
{if !empty($CreatedBy)}
	Создано пользователем: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Войти в {$AppTitle}</a>
