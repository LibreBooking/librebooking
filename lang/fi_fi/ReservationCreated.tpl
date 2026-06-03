	Varauksen tiedot:
	<br/>
	<br/>

	Alkaa: {formatdate date=$StartDate key=reservation_email}<br/>
	Päättyy: {formatdate date=$EndDate key=reservation_email}<br/>

	{if $Resources|default:array()|count > 1}
		<strong>Resurssit ({$Resources|default:array()|count}):</strong> <br />
	{else}
		<strong>Resurssi:</strong><br/>
	{/if}
	{foreach from=$Resources item=resource name=resourceLoop}
		<strong>{$resource.name|escape}</strong><br/>
		{if $resource.scheduleName}<strong>Kalenteri:</strong> {$resource.scheduleName|escape}<br/>{/if}
		<strong>Resurssin tunnus:</strong> {$resource.id}<br/>
		{if $resource.location}<strong>Sijainti:</strong> {$resource.location|escape}<br/>{/if}
		{if $resource.contact}<strong>Yhteystieto:</strong> {$resource.contact|escape}<br/>{/if}
		{if $resource.description}<strong>Kuvaus:</strong> {$resource.description|escape|nl2br}<br/>{/if}
		{if $resource.notes}<strong>Huomautukset:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
		{if $resource.resourceAdministrator}<strong>Resurssin ylläpitäjä:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

		{if $resource.attributeRows|default:array()|count > 0}
			<strong>Resurssin tiedot:</strong><br/>
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

	Otsikko: {$Title}<br/>
	Kuvaus: {$Description|nl2br}<br/>

	{if count($RepeatDates) gt 0}
		<br/>
		Varaus toistuu seuraavina päivinä:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $RequiresApproval}
		<br/>
		Yksi tai useampi varattu resurssi vaatii hyväksynnän ennen käyttöä.  Ole hyvä ja varmista, hyväksytäänkö vai hylätäänkö tämä varauspyyntö.
	{/if}

	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Näytä varaus</a> |
	<a href="{$ScriptUrl}/{$ICalUrl}">Lisää kalenteriisi</a> |
	<a href="{$ScriptUrl}">Kirjaudu sovellukseen LibreBooking</a>

