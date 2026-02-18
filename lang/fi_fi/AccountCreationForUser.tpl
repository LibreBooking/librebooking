<p>{$FullName},</p>

<p>sinulle on luotu {$AppTitle} -tili seuraavilla tiedoilla:<br/>
Sähköposti: {$EmailAddress}<br/>
Nimi: {$FullName}<br/>
Puhelin: {$Phone}<br/>
Organisaatio: {$Organization}<br/>
Rooli: {$Position}<br/>
Salasana: {$Password}</p>
{if !empty($CreatedBy)}
	Luonut: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Kirjaudu sovellukseen {$AppTitle}</a>
