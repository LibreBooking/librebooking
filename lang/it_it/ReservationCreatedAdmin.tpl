<p>&Egrave; stata effettuata una nuova prenotazione.</p>
<p>Dettagli della prenotazione:</p>
<p>
    <strong>Utente:</strong> {$UserName}<br />
    {if preg_match("/[a-zA-Z]+/",$CreatedBy)}
        <strong>Creata da:</strong> {$CreatedBy}<br />
    {/if}
    <strong>Inizio:</strong> {formatdate date=$StartDate key=reservation_email}<br />
    <strong>Fine:</strong> {formatdate date=$EndDate key=reservation_email}<br />
    <strong>Titolo:</strong> {$Title}<br />
    <strong>Descrizione:</strong> {$Description|nl2br}
</p>
{if $Attributes|default:array()|count > 0}
	  <p>
    {foreach from=$Attributes item=attribute}
	      <div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
    {/foreach}
	  </p>
{/if}
{if $ResourceNames|default:array()|count > 1}
    <p>
		    <strong>Risorse({$ResourceNames|default:array()|count}):</strong><br />
        {foreach from=$ResourceNames item=resourceName}
            {$resourceName}<br />
        {/foreach}
		</p>
{else}
		<p>
        <strong>Risorsa:</strong> {$ResourceName}<br />
	  </p>
{/if}
{if $ResourceImage}
    <div class="resource-image"><img alt="{$ResourceName}" src="{$ScriptUrl}/{$ResourceImage}"/></div>
{/if}
{if $RequiresApproval}
	<p>* Almeno una delle risorse prenotate richiede approvazione. Verificare questa prenotazione per approvarla o rifiutarla. *</p>
{/if}
{if $CheckInEnabled}
    <p>
		Almeno una delle risorse prenotate richiede il check-in e il check-out della prenotazione.
    {if $AutoReleaseMinutes != null}
		    <br />Questa prenotazione verr&agrave; cancellata a meno che l&apos;utente non faccia il check-in entro {$AutoReleaseMinutes} minuti entro l&apos;inizio della prenotazione.
    {/if}
	  </p>
{/if}
{if count($RepeatRanges) gt 0}
    <p>
    La prenotazione riguarda le seguenti ({$RepeatRanges|default:array()|count}) date:
		{foreach from=$RepeatRanges item=date name=dates}
		    <br />
        {formatdate date=$date->GetBegin()}
        {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
    {/foreach}
    </p>
{/if}
{if $Participants|default:array()|count >0}
    <p>
	  <strong>Partecipanti ({$Participants|default:array()|count + $ParticipatingGuests|default:array()|count}):</strong>
    {foreach from=$Participants item=user}
		    <br />
        {$user->FullName()}
    {/foreach}
	  </p>
{/if}
{if $ParticipatingGuests|default:array()|count >0}
    <p>
    {foreach from=$ParticipatingGuests item=email}
		    <br />
        {$email}
    {/foreach}
	  </p>
{/if}
{if $Invitees|default:array()|count >0}
    <p>
	  <strong>Invitati ({$Invitees|default:array()|count + $InvitedGuests|default:array()|count}):</strong>
    {foreach from=$Invitees item=user}
		    <br />
        {$user->FullName()}
    {/foreach}
	  </p>
{/if}
{if $InvitedGuests|default:array()|count >0}
    <p>
    {foreach from=$InvitedGuests item=email}
		    <br />
        {$email}
    {/foreach}
	  </p>
{/if}
{if $Accessories|default:array()|count > 0}
    <p>
    <strong>Accessori ({$Accessories|default:array()|count}):</strong>
    {foreach from=$Accessories item=accessory}
		    <br />
		    ({$accessory->QuantityReserved}) {$accessory->Name}
    {/foreach}
	  </p>
{/if}
<p><strong>Numero riferimento:</strong> {$ReferenceNumber}</p>
<p>&nbsp;</p>
<p>
	  <a href="{$ScriptUrl}/{$ReservationUrl}">Dettagli di questa prenotazione</a> |
	  <a href="{$ScriptUrl}">Login su LibreBooking</a>
</p>
