<p>
    {$FullName},
</p>

<p>
    Uma conta em {$AppTitle} foi criada para você com os seguintes detalhes:
    <br />
    E-mail: {$EmailAddress}
    <br />
    Nome: {$FullName}
    <br />
    Telefone: {$Phone}
    <br />
    Organização: {$Organization}
    <br />
    Posição: {$Position}
    <br />
    Senha: {$Password|escape:'html'}
    {if !empty($CreatedBy)}
        <br />
        Criada por: {$CreatedBy}
    {/if}
</p>

<p>
    <a href="{$ScriptUrl}">Acessar {$AppTitle}</a>
</p>
