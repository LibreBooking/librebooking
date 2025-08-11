<p>Tu reserva termina pronto.</p>
<p><strong>Detalles de la reserva:</strong></p>

<p>
	<strong>Inicio:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Fin:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Recurso:</strong> {$ResourceName}<br/>
	<strong>Título:</strong> {$Title}<br/>
	<strong>Descripción:</strong> {$Description|nl2br}<br/>
</p>
<a href="{$ScriptUrl}/{$ReservationUrl}">Ver esta reserva</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Agregar a un calendario</a> |
<a href="{$ScriptUrl}">Iniciar sesión en {$AppTitle}</a>
