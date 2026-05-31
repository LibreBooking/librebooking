<p>Hallo {$FullName},</p>

<p>f&uuml;r Sie wurde ein {$AppTitle}-Benutzerkonto mit den folgenden Daten erstellt:<br/>
E-Mail: {$EmailAddress}<br/>
Name: {$FullName}<br/>
Telefon: {$Phone}<br/>
Organisation: {$Organization}<br/>
Position: {$Position}<br/>
Passwort: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	Erstellt von: {$CreatedBy}
{/if}
<br/>
Bitte melden Sie sich an und &auml;ndern Ihr Passwort.<br/>
<p><a href="{$ScriptUrl}">{$AppTitle}-Anmeldung</a></p>
