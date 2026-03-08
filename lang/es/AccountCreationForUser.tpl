<p>{$FullName},</p>

<p>Se ha creado una cuenta para {$AppTitle} a tu nombre con los siguientes datos:<br/>
Correo electrónico: {$EmailAddress}<br/>
Nombre: {$FullName}<br/>
Teléfono: {$Phone}<br/>
Organización: {$Organization}<br/>
Cargo: {$Position}<br/>
Contraseña: {$Password|escape:'html'}</p>
{if !empty($CreatedBy)}
	Creado por: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Iniciar sesión en {$AppTitle}</a>
