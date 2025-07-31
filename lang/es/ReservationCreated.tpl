<p><strong>Detalles de la Reserva:</strong></p>

<p>
    <strong>Inicio:</strong> {formatdate date=$StartDate key=reservation_email}<br />
    <strong>Fin:</strong> {formatdate date=$EndDate key=reservation_email}<br />
    {if $Title}
        <strong>Título:</strong> {$Title}<br />
    {/if}
    <strong>Descripción:</strong> {$Description|nl2br}<br />
    <strong>Localización:</strong> {$Localizacion}<br />
    <strong>Notas:</strong> {$Note}
    {if $Attributes|default:array()|count > 0}
        <br />
        {foreach from=$Attributes item=attribute}
        <div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
    {/foreach}
{/if}
</p>

<p>
    {if $ResourceNames|default:array()|count > 1}
        <strong>Recursos ({$ResourceNames|default:array()|count}):</strong> <br />
        {foreach from=$ResourceNames item=resourceName}
            {$resourceName}<br />
        {/foreach}
    {else}
        <strong>Recurso:</strong> {$ResourceName}<br />
    {/if}
</p>

{if $ResourceImage}
    <div class="resource-image"><img alt="{$ResourceName|escape}" src="{$ScriptUrl}/{$ResourceImage}" /></div>
{/if}

{if $RequiresApproval}
    <p>* Al menos uno de los recursos reservados requiere aprobación antes de su uso. Esta reserva estará pendiente hasta
        que sea aprobada. *</p>
{/if}

{if $CheckInEnabled}
    <p>
        Al menos uno de los recursos reservados requiere que realices el registro de entrada y salida de tu reserva.
        {if $AutoReleaseMinutes != null}
            Esta reserva se cancelará a menos que realices el registro de entrada dentro de los {$AutoReleaseMinutes} minutos
            después de la hora de inicio programada.
        {/if}
    </p>
{/if}

{if count($RepeatRanges) gt 0}
    <br />
    <strong>La reserva ocurre en las siguientes fechas ({$RepeatRanges|default:array()|count}):</strong>
    <br />
    {foreach from=$RepeatRanges item=date name=dates}
        {formatdate date=$date->GetBegin()}
        {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
        <br />
    {/foreach}
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

{if $CreditsCurrent > 0}
    <br />
    Esta reserva tiene un costo de {$CreditsCurrent} créditos.
    {if $CreditsCurrent != $CreditsTotal}
        Esta serie completa de reservas tiene un costo de {$CreditsTotal} créditos.
    {/if}
{/if}


{if !empty($CreatedBy)}
    <p><strong>Creado por:</strong> {$CreatedBy}</p>
{/if}

{if !empty($ApprovedBy)}
    <p><strong>Aprobado por:</strong> {$ApprovedBy}</p>
{/if}

<p><strong>Número de referencia:</strong> {$ReferenceNumber}</p>

{if !$Deleted}
    <a href="{$ScriptUrl}/{$ReservationUrl}">Ver esta reserva</a>
    |
    <a href="{$ScriptUrl}/{$ICalUrl}">Agregar a un calendario</a>
    |
    <a href="{$GoogleCalendarUrl}" target="_blank" rel="nofollow">Agregar a Google Calendar</a>
    |
{/if}
<a href="{$ScriptUrl}">Iniciar sesión en Siris - Reservas</a>