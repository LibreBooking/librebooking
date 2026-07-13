<p>
    <strong>Detalhes da Reserva:</strong>
</p>

<p>
	<strong>Usuário:</strong> {$UserName}
    <br/>
    {if !empty($CreatedBy)}
		<strong>Criada por:</strong> {$CreatedBy}
		<br/>
    {/if}
	<strong>Início:</strong> {formatdate date=$StartDate key=reservation_email}
    <br/>
	<strong>Fim:</strong> {formatdate date=$EndDate key=reservation_email}
    <br/>
	<strong>Título:</strong> {$Title}
    <br/>
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
    <strong>Recursos ({$Resources|default:array()|count}):</strong>
    <br/>
    {else}
    <strong>Recurso:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>Agenda:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>ID do recurso:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>Localização:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>Contato:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>Descrição:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
        {if $resource.notes}<strong>Notas:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
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
    <p>
        Pelo menos um dos recursos reservados requer aprovação antes do uso.
        Esta solicitação de ficará pendente até que seja aprovada ou rejeitada.
    </p>
{/if}

{if $CheckInEnabled}
	<p>
        Pelo menos um dos recursos reservados exige check-in e check-out da reserva.
        {if $AutoReleaseMinutes != null}
			Essa reserva será cancelada caso o usuário não faça o check-in dentro de {$AutoReleaseMinutes} minutos após o horário de início programado.
        {/if}
	</p>
{/if}

{if count($RepeatRanges) gt 0}
    <p>
        A reserva ocorre nas seguintes datas ({$RepeatRanges|default:array()|count})
        {foreach from=$RepeatRanges item=date name=dates}
            <br />
            {formatdate date=$date->GetBegin()}
            {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
        {/foreach}
    </p>
{/if}

{if ($Participants|default:array()|count > 0) or ($ParticipatingGuests|default:array()|count > 0)}
    <p>
        <strong>Participantes ({$Participants|default:array()|count + $ParticipatingGuests|default:array()|count}):</strong>
        {foreach from=$Participants item=user}
            <br />
            {$user->FullName()}
        {/foreach}
        {foreach from=$ParticipatingGuests item=email}
            <br />
            {$email}
        {/foreach}
    </p>
{/if}

{if ($Invitees|default:array()|count > 0) or ($InvitedGuests|default:array()|count > 0)}
    <p>
        <strong>Convidados ({$Invitees|default:array()|count + $InvitedGuests|default:array()|count}):</strong>
        {foreach from=$Invitees item=user}
            <br />
            {$user->FullName()}
        {/foreach}
        {foreach from=$InvitedGuests item=email}
            <br />
            {$email}
        {/foreach}
    </p>
{/if}

{if $Accessories|default:array()|count > 0}
    <p>
        <strong>Acessórios ({$Accessories|default:array()|count}):</strong>
        {foreach from=$Accessories item=accessory}
            <br />
            ({$accessory->QuantityReserved}) {$accessory->Name}
        {/foreach}
    </p>
{/if}

<p>
    <strong>Número de Referência:</strong> {$ReferenceNumber}
</p>

<p>
    <a href="{$ScriptUrl}/{$ReservationUrl}">Ver esta reserva</a> |
    <a href="{$ScriptUrl}">Acessar {$AppTitle}</a>
</p>
