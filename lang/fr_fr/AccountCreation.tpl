<p>{$To},</p>

<p>Un nouvel utilisateur s'est enregistré avec les informations suivantes:<br/>
Email: {$EmailAddress}<br/>
Nom: {$FullName}<br/>
Téléphone: {$Phone}<br/>
Organisation: {$Organization}<br/>
Position: {$Position}</p>
{if !empty($CreatedBy)}
	Créé par: {$CreatedBy}
{/if}
