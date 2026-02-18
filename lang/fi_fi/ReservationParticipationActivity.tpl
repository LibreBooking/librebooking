<p>{$ParticipantDetails} on
    {if ($InvitationAction == InvitationAction::Decline || $InvitationAction == InvitationAction::CancelAll || $InvitationAction == InvitationAction::CancelInstance)}
		hylännyt varauskutsusi.
    {elseif ($InvitationAction == InvitationAction::Join || $InvitationAction == InvitationAction::JoinAll)}
		liittynyt varaukseesi.
    {else}
		hyväksynyt varauskutsusi.
    {/if}
</p>
<p><strong>Varauksen tiedot:</strong></p>

<p>
	<strong>Alkaa:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Päättyy:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Otsikko:</strong> {$Title}<br/>
	<strong>Kuvaus:</strong> {$Description|nl2br}
    {if $Attributes|default:array()|count > 0}
	<br/>
    {foreach from=$Attributes item=attribute}
	<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
    {/foreach}
{/if}
</p>

<p>
    {if $ResourceNames|default:array()|count > 1}
		<strong>Resurssit ({$ResourceNames|default:array()|count}):</strong>
		<br/>
        {foreach from=$ResourceNames item=resourceName}
            {$resourceName}
			<br/>
        {/foreach}
    {else}
		<strong>Resurssi:</strong>
        {$ResourceName}
		<br/>
    {/if}
</p>

{if $ResourceImage}
	<div class="resource-image"><img alt="{$ResourceName|escape}" src="{$ScriptUrl}/{$ResourceImage}"/></div>
{/if}

<p><strong>Varausnumero:</strong> {$ReferenceNumber}</p>

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Näytä varaus</a> |
	<a href="{$ScriptUrl}">Kirjaudu sovellukseen {$AppTitle}</a>
</p>
