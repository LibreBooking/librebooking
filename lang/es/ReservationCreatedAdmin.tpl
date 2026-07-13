<p><strong>Detalles de la reserva:</strong></p>

<p>
	<strong>Usuario:</strong> {$UserName}<br/>
    {if !empty($CreatedBy)}
		<strong>Creado por:</strong>
        {$CreatedBy}
		<br/>
    {/if}
	<strong>Inicio:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Fin:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Título:</strong> {$Title}<br/>
	<strong>Descripción:</strong> {$Description|nl2br}
    {if $Attributes|default:array()|count > 0}
	<br/>
    {foreach from=$Attributes item=attribute}
	<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
    {/foreach}
{/if}
</p>

<p>

    {if $Resources|default:array()|count > 1}
    <strong>Recursos ({$Resources|default:array()|count}):</strong>
    <br/>
    {else}
    <strong>Recurso:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>Horario:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>ID del recurso:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>Ubicación:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>Contacto:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>Descripción:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
        {if $resource.notes}<strong>Notas:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
        {if $resource.resourceAdministrator}<strong>Administrador del recurso:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

        {if $resource.attributeRows|default:array()|count > 0}
            <strong>Detalles del recurso:</strong><br/>
            <table cellpadding="4" cellspacing="0" border="1" style="border-collapse: collapse; margin-top: 4px;">
                {foreach from=$resource.attributeRows item=row}
                    <tr>
                        <th scope="row" valign="top" style="text-align: left;"><strong>{$row.label|escape}</strong></th>
                        <td valign="top">{$row.displayValue|escape|nl2br}</td>
                    </tr>
                {/foreach}
            </table>
        {/if}

        {if $resource.image}
            <div class="resource-image"><img alt="{$resource.name|escape}" src="{$ScriptUrl}/{$resource.image|escape}"/></div>
        {/if}

        {if !$smarty.foreach.resourceLoop.last}<br/>{/if}
    {/foreach}
</p>


{if $RequiresApproval}
	<p>* Al menos uno de los recursos reservados requiere aprobación antes de su uso. Asegúrate de que esta solicitud de reserva sea aprobada o rechazada. *</p>
{/if}

{if $CheckInEnabled}
	<p>
	Al menos uno de los recursos reservados requiere que el usuario realice el registro de entrada y salida de la reserva.
        {if $AutoReleaseMinutes != null}
			Esta reserva se cancelará a menos que el usuario realice el registro de entrada dentro de los {$AutoReleaseMinutes} minutos después de la hora de inicio programada.
        {/if}
	</p>
{/if}

{if count($RepeatRanges) gt 0}
	<p>
	La reserva ocurre en las siguientes fechas ({$RepeatRanges|default:array()|count}):
		<br/>
        {foreach from=$RepeatRanges item=date name=dates}
            {formatdate date=$date->GetBegin()}
            {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
			<br/>
        {/foreach}
	</p>
{/if}

{if $Participants|default:array()|count >0}
	<br/>
	<strong>Participantes ({$Participants|default:array()|count + $ParticipatingGuests|default:array()|count}):</strong>
	<br/>
    {foreach from=$Participants item=user}
        {$user->FullName()}
		<br/>
    {/foreach}
{/if}

{if $ParticipatingGuests|default:array()|count >0}
    {foreach from=$ParticipatingGuests item=email}
        {$email}
		<br/>
    {/foreach}
{/if}

{if $Invitees|default:array()|count >0}
	<br/>
	<strong>Invitados ({$Invitees|default:array()|count + $InvitedGuests|default:array()|count}):</strong>
	<br/>
    {foreach from=$Invitees item=user}
        {$user->FullName()}
		<br/>
    {/foreach}
{/if}

{if $InvitedGuests|default:array()|count >0}
    {foreach from=$InvitedGuests item=email}
        {$email}
		<br/>
    {/foreach}
{/if}

{if $Accessories|default:array()|count > 0}
	<br/>
	<strong>Accesorios ({$Accessories|default:array()|count}):</strong>
	<br/>
    {foreach from=$Accessories item=accessory}
		({$accessory->QuantityReserved}) {$accessory->Name}
		<br/>
    {/foreach}
{/if}

<p><strong>Número de referencia:</strong> {$ReferenceNumber}</p>

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Ver esta reserva</a> | <a href="{$ScriptUrl}">Iniciar sesión en {$AppTitle}</a>
</p>