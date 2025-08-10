<p><strong>Detalles de la reserva:</strong></p>

<p>
	<strong>Inicio:</strong> {formatdate date=$StartDate key=reservation_email}<br />
	<strong>Fin:</strong> {formatdate date=$EndDate key=reservation_email}<br />
	{if $Title}
		<strong>Título:</strong> {$Title}<br />
	{/if}
	<strong>Descripción:</strong> {$Description|nl2br}
	{if $Attributes|default:array()|count > 0}
		<br />
		{foreach from=$Attributes item=attribute}
		<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
	{/foreach}
{/if}
</p>

<p>
	{if $ResourceNames|default:array()|count > 1}
		<strong>Recursos ({$ResourceNames|default:array()|count}):</strong>
		<br />
		{foreach from=$ResourceNames item=resourceName}
			{$resourceName}
			<br />
		{/foreach}
	{else}
		<strong>Recurso:</strong>
		{$ResourceName}
		<br />
	{/if}
</p>

{if $ResourceImage}
	<div class="resource-image"><img alt="{$ResourceName|escape}" src="{$ScriptUrl}/{$ResourceImage}" /></div>
{/if}

{if count($RepeatRanges) gt 0}
	<br />
	<strong>La reserva tiene lugar en las siguientes fechas ({$RepeatRanges|default:array()|count}):</strong>
	<br />
	{foreach from=$RepeatRanges item=date name=dates}
		{formatdate date=$date->GetBegin()}
		{if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
		<br />
	{/foreach}
{/if}

<p>
	{if !empty($CreatedBy)}
		<strong>Eliminado por:</strong>
		{$CreatedBy}
		<br />
		<strong>Motivo de eliminación: {$DeleteReason|nl2br}</strong>
	{/if}
</p>

<p><strong>Número de referencia:</strong> {$ReferenceNumber}</p>

<a href="{$ScriptUrl}">Iniciar sesión en Siris - Reservas de Prácticas</a>