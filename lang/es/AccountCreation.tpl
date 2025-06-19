<p>{$To},</p>

<p>Se ha registrado un nuevo usuario con la siguiente información:<br/>
Correo electrónico: {$EmailAddress}<br/>
Nombre: {$FullName}<br/>
Teléfono: {$Phone}<br/>
Proyecto Curricular: {$Organization}<br/>
Cargo: {$Position}</p>
{if !empty($CreatedBy)}
	Creado por: {$CreatedBy}
{/if}