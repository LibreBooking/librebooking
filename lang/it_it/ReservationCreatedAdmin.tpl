<p>È stata effettuata una nuova prenotazione.</p>
<p>Dettagli della prenotazione:</p>
<p>
    <strong>Utente:</strong> {$UserName}<br />
    {if !empty($CreatedBy)}
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
<p>
    {if $Resources|default:array()|count > 1}
    <strong>Risorse ({$Resources|default:array()|count}):</strong>
    <br/>
    {else}
    <strong>Risorsa:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>Pianificazione:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>ID risorsa:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>Posizione:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>Contatto:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>Descrizione:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
        {if $resource.notes}<strong>Note:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
        {if $resource.resourceAdministrator}<strong>Amministratore della risorsa:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

        {if $resource.attributeRows|default:array()|count > 0}
            <strong>Dettagli risorsa:</strong><br/>
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
	<p>* Almeno una delle risorse prenotate richiede approvazione. Verificare questa prenotazione per approvarla o rifiutarla. *</p>
{/if}
{if $CheckInEnabled}
    <p>
		Almeno una delle risorse prenotate richiede il check-in e il check-out della prenotazione.
    {if $AutoReleaseMinutes != null}
		    <br />Questa prenotazione verrà cancellata a meno che l'utente non faccia il check-in entro {$AutoReleaseMinutes} minuti entro l'inizio della prenotazione.
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
