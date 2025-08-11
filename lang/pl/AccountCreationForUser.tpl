<p>{$FullName},</p>

<p>Konto {$AppTitle} zostało dla Ciebie utworzone z poniższymi danymi:<br/>
Adres e-mail: {$EmailAddress}<br/>
Imię: {$FullName}<br/>
Numer telefonu: {$Phone}<br/>
Organizacja: {$Organization}<br/>
Stanowisko: {$Position}<br/>
Hasło: {$Password}</p>
{if !empty($CreatedBy)}
	Utworzono przez: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Log In to {$AppTitle}</a>
