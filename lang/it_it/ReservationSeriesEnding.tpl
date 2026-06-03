<p>La sua serie di prenotazioni per {$ResourceName} terminerà il {formatdate date=$StartDate key=reservation_email}.</p>
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
        <strong>Risorse ({$ResourceNames|default:array()|count}):</strong><br />
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
<p>&nbsp;</p>
<p>
    <a href="{$ScriptUrl}/{$ReservationUrl}">Dettagli di questa prenotazione</a> |
    <a href="{$ScriptUrl}">Login su LibreBooking</a>
</p>
