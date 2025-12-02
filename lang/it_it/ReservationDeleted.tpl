<p>{$UserName},</p>
<p>la seguente prenotazione &egrave; stata cancellata.</p>
<p>Dettagli della prenotazione:</p>
<p>
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
		    <strong>Risorse ({$ResourceNames|default:array()|count}):</strong>
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
    <div class="resource-image"><img alt="{$ResourceName|escape}" src="{$ScriptUrl}/{$ResourceImage}"/></div>
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
{if !empty($CreatedBy)}
    <p>
		    <strong>Cancellata da:</strong> {$CreatedBy}
		    <br />
		    <strong>Motivazione:</strong> <em>{$DeleteReason|nl2br}</em>
    </p>
{/if}
<p><strong>Numero riferimento:</strong> {$ReferenceNumber}</p>
<p>&nbsp;</p>
<p><a href="{$ScriptUrl}">Login su LibreBooking</a></p>
