<p>Vous avez oublié d'effectuer votre check in.</p>
<p><strong>Détails de la réservation:</strong></p>
<p>
	<strong>Début:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Fin:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Ressource:</strong> {$ResourceName}<br/>
	<strong>Libellé:</strong> {$Title}<br/>
	<strong>Description:</strong> {$Description|nl2br}
</p>

{if $IsAutoRelease}
	<p>Si vous n'effectuez pas le check in, cette réservation sera automatiquement supprimée à {formatdate date=$AutoReleaseTime key=reservation_email}</p>
{/if}

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Voir cette réservation</a> |
	<a href="{$ScriptUrl}">Connexion à {$AppTitle}</a>
</p>
