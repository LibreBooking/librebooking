	Reserverings Details:
	<br/>
	<br/>

	Start: {formatdate date=$StartDate key=reservation_email}<br/>
	Eindigd: {formatdate date=$EndDate key=reservation_email}<br/>
	{if $ResourceNames|default:array()|count > 1}
		Bronnen:<br/>
		{foreach from=$ResourceNames item=resourceName}
			{$resourceName}<br/>
		{/foreach}
		{else}
		Bron: {$ResourceName}<br/>
	{/if}

	{if $ResourceImage}
		<div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
	{/if}

	Titel: {$Title}<br/>
	Beschrijving: {$Description|nl2br}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		De reservering zal zijn op de volgende data:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Benodigdheden:<br/>
		{foreach from=$Accessories item=accessory}
			({$accessory->QuantityReserved}) {$accessory->Name}<br/>
		{/foreach}
	{/if}

	{if $Attributes|default:array()|count > 0}
		<br/>
		{foreach from=$Attributes item=attribute}
			<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
		{/foreach}
	{/if}

	{if $RequiresApproval}
		<br/>
		E�n of meerdere bronnen die gereserveerd zijn hebben goedkeuring nodig voor gebruik. Deze reservering wordt in behandeling genomen totdat hij is goedgekeurd.
	{/if}

	{if !empty($ApprovedBy)}
		<br/>
		Geaccepteerd door: {$ApprovedBy}
	{/if}

	<br/>
	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Bekijk deze reservering</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Voeg toe aan agenda</a> |
	<a href="{$ScriptUrl}">Login in LibreBooking</a>

