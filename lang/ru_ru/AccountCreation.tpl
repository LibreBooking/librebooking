<p>{$To},</p>

<p>Новый пользователь зарегистрировался со следующей информацией:<br/>
Email: {$EmailAddress}<br/>
Имя: {$FullName}<br/>
Телефон: {$Phone}<br/>
Организация: {$Organization}<br/>
Должность: {$Position}</p>
{if !empty($CreatedBy)}
	Создано пользователем: {$CreatedBy}
{/if}
