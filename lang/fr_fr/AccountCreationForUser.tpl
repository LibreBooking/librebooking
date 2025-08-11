<p>{$FullName},</p>

<p>Le compte {$AppTitle} a été créé pour vous avec les informations suivantes:<br/>
Email: {$EmailAddress}<br/>
Nom: {$FullName}<br/>
Téléphone: {$Phone}<br/>
Organisation: {$Organization}<br/>
Position: {$Position}<br/>
Mot de passe: {$Password}</p>
{if !empty($CreatedBy)}
	Créé par: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Connexion à {$AppTitle}</a>
