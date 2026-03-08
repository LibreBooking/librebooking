<p>Kære {$FullName},</p>

<p>Der er oprettet en konto for dig i {$AppTitle} med disse informationer:<br/>
E-mail: {$EmailAddress}<br/>
Navn: {$FullName}<br/>
Telefon: {$Phone}<br/>
Organisation: {$Organization}<br/>
Adresse: {$Position}<br/>
Adgangskode: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	Oprettet af: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Log på {$AppTitle}</a>
