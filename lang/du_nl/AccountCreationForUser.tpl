<p>{$FullName},</p>

<p>Er is een account aangemaakt voor {$AppTitle} met de volgende details:<br/>
Email: {$EmailAddress}<br/>
Naam: {$FullName}<br/>
Telefoonnummer: {$Phone}<br/>
Organisatie: {$Organization}<br/>
Positie: {$Position}<br/>
Wachtwoord: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	Gemaakt door: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Login bij {$AppTitle}</a>
