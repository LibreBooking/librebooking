Hallo,<br/>
die folgende Reservierung wurde aktualisiert.<br/>
<p>{$ParticipantDetails}
    {if ($InvitationAction == InvitationAction::Decline || $InvitationAction == InvitationAction::CancelAll || $InvitationAction == InvitationAction::CancelInstance)}
		hat Ihre Einladung abgelehnt.
    {elseif ($InvitationAction == InvitationAction::Join || $InvitationAction == InvitationAction::JoinAll)}
		ist Ihrer Reservierung beigetreten.
    {else}
		hat Ihrer Einladung zugestimmt.
    {/if}
</p>
<p><strong>Reservierungsdetails:</strong></p>

<p>
	<strong>Start:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Ende:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Titel:</strong> {$Title}<br/>
	<strong>Beschreibung:</strong> {$Description|nl2br}
    {if $Attributes|default:array()|count > 0}
	<br/>
    {foreach from=$Attributes item=attribute}
	<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
    {/foreach}
{/if}
</p>

<p>
    {if $ResourceNames|default:array()|count > 1}
		<strong>Ressourcen ({$ResourceNames|default:array()|count}):</strong>
		<br/>
        {foreach from=$ResourceNames item=resourceName}
            {$resourceName}
			<br/>
        {/foreach}
    {else}
		<strong>Ressource:</strong>
        {$ResourceName}
		<br/>
    {/if}
</p>

{if $ResourceImage}
	<div class="resource-image"><img alt="{$ResourceName|escape}" src="{$ScriptUrl}/{$ResourceImage}"/></div>
{/if}

<p>
    {if $Accessories|default:array()|count > 0}
        <strong>Zubeh&ouml;r:</strong>
        <br/>
        {foreach from=$Accessories item=accessory}
            ({$accessory->QuantityReserved}) {$accessory->Name}
            <br/>
        {/foreach}
    {/if}
</p>

<p><strong>Referenznummer:</strong> {$ReferenceNumber}</p>

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Reservierung ansehen</a> |
	<a href="{$ScriptUrl}">{$AppTitle}-Anmeldung</a>
</p>
