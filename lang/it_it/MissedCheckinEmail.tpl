<p>Non ha effettuato il check-in.</p>
<p>Dettagli prenotazione:</p>
<p>
    <strong>Start:</strong> {formatdate date=$StartDate key=reservation_email}<br />
    <strong>End:</strong> {formatdate date=$EndDate key=reservation_email}<br />
    <strong>Resource:</strong> {$ResourceName}<br />
    <strong>Title:</strong> {$Title}<br />
    <strong>Description:</strong> {$Description|nl2br}
</p>
{if $IsAutoRelease}
    <p>Se non effettua il check-in, questa prenotazione verr&agrave; cancellata il {formatdate date=$AutoReleaseTime key=reservation_email}</p>
{/if}
<p>&nbsp;</p>
<p>
    <a href="{$ScriptUrl}/{$ReservationUrl}">Dettagli di questa prenotazione</a> |
    <a href="{$ScriptUrl}">Login su Booking Scheduler</a>
</p>
