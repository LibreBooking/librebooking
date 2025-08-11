<p>Promemoria prima dell&apos;orario di fine della sua prenotazione</p>
<p>Dettagli della prenotazione:</p>
<p>
    <strong>Inizio:</strong> {formatdate date=$StartDate key=reservation_email}<br />
    <strong>Fine:</strong> {formatdate date=$EndDate key=reservation_email}<br />
    <strong>Titolo:</strong> {$Title}<br />
    <strong>Descrizione:</strong> {$Description|nl2br}
</p>
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
<p>&nbsp;</p>
<p>
    <a href="{$ScriptUrl}/{$ReservationUrl}">Dettagli di questa prenotazione</a> |
    <a href="{$ScriptUrl}/{$ICalUrl}">Aggiungere prenotazione al calendario</a> |
    <a href="{$ScriptUrl}">Login su LibreBooking</a>
</p>
