<p><strong>Detalles de la reserva:</strong></p>

<p>
	<strong>Usuario:</strong> {$UserName}<br />
	{if !empty($CreatedBy)}
		<strong>Creado por:</strong>
		{$CreatedBy}
		<br />
	{/if}
	<strong>Inicio:</strong> {formatdate date=$StartDate key=reservation_email}<br />
	<strong>Fin:</strong> {formatdate date=$EndDate key=reservation_email}<br />
	{if $Title}
		<strong>Título:</strong> {$Title}<br />
	{/if}
	<strong>Descripción:</strong> {$Description|nl2br}
	{if $Attributes|default:array()|count > 0}
		<br />
		{foreach from=$Attributes item=attribute}
		<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
	{/foreach}
{/if}
</p>

<p>
	{if $ResourceNames|default:array()|count > 1}
		<strong>Recursos:</strong>
		<br />
		{foreach from=$ResourceNames item=resourceName}
			{$resourceName}
			<br />
		{/foreach}
	{else}
		<strong>Recurso:</strong>
		{$ResourceName}
	{/if}
</p>

{if $ResourceImage}
	<div class="resource-image"><img alt="{$ResourceName}" src="{$ScriptUrl}/{$ResourceImage}" /></div>
{/if}


{if $RequiresApproval}
	<p>* Al menos uno de los recursos reservados requiere aprobación antes de su uso. Asegúrate de que esta solicitud de
		reserva sea aprobada o rechazada. *</p>
{/if}

{if $CheckInEnabled}
	<p>
		Al menos uno de los recursos reservados requiere que el usuario realice el registro de entrada y salida de la
		reserva.
		{if $AutoReleaseMinutes != null}
			Esta reserva se cancelará a menos que el usuario realice el registro de entrada dentro de los {$AutoReleaseMinutes}
			minutos después de la hora de inicio programada.
		{/if}
	</p>
{/if}

{if count($RepeatRanges) gt 0}
	<p>
		La reserva ocurre en las siguientes fechas ({$RepeatRanges|default:array()|count}):
		<br />
		{foreach from=$RepeatRanges item=date name=dates}
			{formatdate date=$date->GetBegin()}
			{if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
			<br />
		{/foreach}
	</p>
{/if}

{if $Participants|default:array()|count >0}
	<br />
	<strong>Participantes ({$Participants|default:array()|count + $ParticipatingGuests|default:array()|count}):</strong>
	<br />
	{foreach from=$Participants item=user}
		{$user->FullName()}
		<br />
	{/foreach}
{/if}

{if $ParticipatingGuests|default:array()|count >0}
	{foreach from=$ParticipatingGuests item=email}
		{$email}
		<br />
	{/foreach}
{/if}

{if $Invitees|default:array()|count >0}
	<br />
	<strong>Invitados ({$Invitees|default:array()|count + $InvitedGuests|default:array()|count}):</strong>
	<br />
	{foreach from=$Invitees item=user}
		{$user->FullName()}
		<br />
	{/foreach}
{/if}

{if $InvitedGuests|default:array()|count >0}
	{foreach from=$InvitedGuests item=email}
		{$email}
		<br />
	{/foreach}
{/if}

{if $Accessories|default:array()|count > 0}
	<br />
	<strong>Accesorios ({$Accessories|default:array()|count}):</strong>
	<br />
	{foreach from=$Accessories item=accessory}
		({$accessory->QuantityReserved}) {$accessory->Name}
		<br />
	{/foreach}
{/if}

<p><strong>Número de referencia:</strong> {$ReferenceNumber}</p>

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Ver esta reserva</a> | <a href="{$ScriptUrl}">Iniciar sesión en Siris -
		Reservas de Prácticas</a>
</p>