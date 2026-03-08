<p>{$FullName},</p>

<p>Egy új fiók: {$AppTitle} készült az Ön számára az alábbi információkkal:<br/>
Email: {$EmailAddress}<br/>
Név: {$FullName}<br/>
Telefon: {$Phone}<br/>
Szervezet: {$Organization}<br/>
Pozíció: {$Position}</p>
Jelszó: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	Készítette: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Bejelentkezés ide: {$AppTitle}</a>
