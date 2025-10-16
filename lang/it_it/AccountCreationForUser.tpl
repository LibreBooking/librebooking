<p>{$FullName},</p>
<p>&egrave; stato creato un account per lei su LibreBooking con le seguenti informazioni:</p>
<p>
    <strong>Email:</strong> {$EmailAddress}<br />
    <strong>Nome:</strong> {$FullName}<br />
    <strong>Telefono:</strong> {$Phone}<br />
    <strong>Dipartimento:</strong> {$Organization}<br />
    <strong>Posizione:</strong> {$Position}<br />
    <strong>Password:</strong> <em>{$Password}</em>
</p>
<p>
    {if !empty($CreatedBy)}
        <strong>Created by:</strong> {$CreatedBy}
    {/if}
</p>
<p>&nbsp;</p>
<p><a href="{$ScriptUrl}">Login su LibreBooking</a></p>
