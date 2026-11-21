<p>{$To},</p>

<p>Зарегистрирован новый пользователь:<br/>
Email: {$EmailAddress}<br/>
Имя: {$FullName}<br/>
Телефон: {$Phone}<br/>
Организация: {$Organization}<br/>
Должность: {$Position}</p>
{if !empty($CreatedBy)}
	Кем создано: {$CreatedBy}
{/if}
