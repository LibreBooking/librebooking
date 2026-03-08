<p>{$FullName},</p>

<p>Δημιουργήθηκε ένας λογαριασμός για εσάς για το {$AppTitle} με τις εξής πληροφορίες:<br/>
Email: {$EmailAddress}<br/>
Όνομα: {$FullName}<br/>
Τηλέφωνο: {$Phone}<br/>
Οργανισμός: {$Organization}<br/>
Θέση: {$Position}<br/>
Συνθηματικό: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	Δημιουργήθηκε από: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Κάνετε είσοδο στο {$AppTitle}</a>
