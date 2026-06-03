<p>{$FullName},</p>

<p>Uma conta em {$AppTitle} foi criada para si com os seguintes detalhes:<br/>
Email: {$EmailAddress}<br/>
Nome: {$FullName}<br/>
Telefone: {$Phone}<br/>
Organização: {$Organization}<br/>
Posição: {$Position}<br/>
Senha: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	Criado por: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Entrar em {$AppTitle}</a>
