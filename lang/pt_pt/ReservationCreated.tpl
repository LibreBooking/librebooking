<p><strong>Detalhes da Reserva:</strong></p>

<p>
	<strong>Início:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Fim:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Título:</strong> {$Title}<br/>
	<strong>Descrição:</strong> {$Description|nl2br}
	{if $Attributes|default:array()|count > 0}
		<br/>
	    {foreach from=$Attributes item=attribute}
			<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
	    {/foreach}
	{/if}
</p>

<p>

    {if $Resources|default:array()|count > 1}
        <strong>Recursos ({$Resources|default:array()|count}):</strong> <br />
    {else}
        <strong>Recurso:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>Agenda:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>ID do recurso:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>Localização:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>Contacto:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>Descrição:</strong> {$resource.description|escape|nl2br}<br/>{/if}
        {if $resource.notes}<strong>Notas:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
        {if $resource.resourceAdministrator}<strong>Administrador do recurso:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

        {if $resource.attributeRows|default:array()|count > 0}
            <strong>Detalhes do recurso:</strong><br/>
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
	<p>* Pelo menos um dos recursos reservados requer aprovação antes do uso. Esta reserva ficará pendente até que seja aprovada. *</p>
{/if}

{if $CheckInEnabled}
	<p>
	Pelo menos um dos recursos reservados requer que seja efetuado o check-in/check-out da sua reserva.
    {if $AutoReleaseMinutes != null}
		Esta reserva será cancelada a não ser que o check-in seja efetuado dentro de {$AutoReleaseMinutes} minutos após a hora marcada de ínicio da reserva.
    {/if}
	</p>
{/if}

{if count($RepeatRanges) gt 0}
    <br/>
    <strong>A reserva ocorre nas seguintes datas ({$RepeatRanges|default:array()|count}):</strong>
    <br/>
	{foreach from=$RepeatRanges item=date name=dates}
	    {formatdate date=$date->GetBegin()}
	    {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
	    <br/>
	{/foreach}
{/if}

{if $Participants|default:array()|count >0}
    <br />
    <strong>Participantes ({$Participants|default:array()|count + $ParticipatingGuests|default:array()|count}):</strong>
    <br />
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
    <br />
    <strong>Convidados ({$Invitees|default:array()|count + $InvitedGuests|default:array()|count}):</strong>
    <br />
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
    <br />
       <strong>Acessórios ({$Accessories|default:array()|count}):</strong>
       <br />
    {foreach from=$Accessories item=accessory}
        ({$accessory->QuantityReserved}) {$accessory->Name}
        <br/>
    {/foreach}
{/if}

{if $CreditsCurrent > 0}
	<br/>
	Esta reserva custa {$CreditsCurrent} créditos.
    {if $CreditsCurrent != $CreditsTotal}
		Esta reserva custa, na sua totalidadem {$CreditsTotal} créditos.
    {/if}
{/if}


{if !empty($CreatedBy)}
	<p><strong>Criado por:</strong> {$CreatedBy}</p>
{/if}

{if !empty($ApprovedBy)}
	<p><strong>Aprovado por:</strong> {$ApprovedBy}</p>
{/if}

<p><strong>Número de referência:</strong> {$ReferenceNumber}</p>

{if !$Deleted}
	<a href="{$ScriptUrl}/{$ReservationUrl}">Ver esta reservan</a>
	|
	<a href="{$ScriptUrl}/{$ICalUrl}">Adicionar ao calendário</a>
	|
	<a href="{$GoogleCalendarUrl}" target="_blank" rel="nofollow">Adicionar ao Google Calendar</a>
	|
{/if}
<a href="{$ScriptUrl}">Entrar em {$AppTitle}</a>

