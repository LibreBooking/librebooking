<p>Si &egrave; registrato un nuovo utente con le seguenti informazioni:</p>
<p>
    <strong>Email:</strong> {$EmailAddress}<br />
    <strong>Nome:</strong> {$FullName}<br />
    <strong>Telefono:</strong> {$Phone}<br />
    <strong>Dipartimento:</strong> {$Organization}<br />
    <strong>Posizione:</strong> {$Position}
</p>
<p>
    {if preg_match("/[a-zA-Z]+/",$CreatedBy)}
        <strong>Creato da:</strong> {$CreatedBy}
    {/if}
</p>
