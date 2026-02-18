<p>{$To},</p>

<p>uusi käyttäjä on rekisteröitynyt seuraavilla tiedoilla:<br/>
Sähköposti: {$EmailAddress}<br/>
Nimi: {$FullName}<br/>
Puhelin: {$Phone}<br/>
Organisaatio: {$Organization}<br/>
Rooli: {$Position}</p>
{if !empty($CreatedBy)}
	Luonut: {$CreatedBy}
{/if}
