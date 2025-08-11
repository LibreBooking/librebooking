<p>{$To},</p>

<p>Zarejestrowano nowego użytkownika z poniższymi danymi:<br/>
Adres e-mail: {$EmailAddress}<br/>
Imię: {$FullName}<br/>
Nr telefonu: {$Phone}<br/>
Organizacja: {$Organization}<br/>
Stanowisko: {$Position}</p>
{if !empty($CreatedBy)}
	Utworzono przez: {$CreatedBy}
{/if}
