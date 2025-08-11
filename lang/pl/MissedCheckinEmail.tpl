<p>Nie zameldowano w terminie rezerwacji.</p>
<p><strong>Szczegóły rezerwacji:</strong></p>
<p>
	<strong>Początek:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Koniec:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Zasób:</strong> {$ResourceName}<br/>
	<strong>Tytuł:</strong> {$Title}<br/>
	<strong>Opis:</strong> {$Description|nl2br}
</p>

{if $IsAutoRelease}
	<p>Jeśli się nie zameldujesz, rezerwacja zostanie automatycznie anulowana o {formatdate date=$AutoReleaseTime key=reservation_email}</p>
{/if}

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Pokaż rezerwację</a> |
	<a href="{$ScriptUrl}">Zaloguj się do {$AppTitle}</a>
</p>
