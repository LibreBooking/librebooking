Oplysninger om reservation:
<br/>
<br/>

Begynder: {formatdate date=$StartDate key=reservation_email}<br/>
Slutter: {formatdate date=$EndDate key=reservation_email}<br/>
<p>
    {if $Resources|default:array()|count > 1}
        <strong>Faciliteter ({$Resources|default:array()|count}):</strong> <br />
    {else}
        <strong>Facilitet:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>Tidsplan:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>Facilitets-ID:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>Placering:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>Kontakt:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>Beskrivelse:</strong> {$resource.description|escape|nl2br}<br/>{/if}
        {if $resource.notes}<strong>Noter:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
        {if $resource.resourceAdministrator}<strong>Facilitetsadministrator:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

        {if $resource.attributeRows|default:array()|count > 0}
            <strong>Facilitetsdetaljer:</strong><br/>
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
</p>

Overskrift: {$Title}<br/>
Beskrivelse: {$Description|nl2br}

{if count($RepeatRanges) gt 0}
	<br/>
	Reservationen gælder for følgende datoer:
	<br/>
{/if}

{foreach from=$RepeatRanges item=date name=dates}
	{formatdate date=$date->GetBegin()}
    {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
	<br/>
{/foreach}

{if $Participants|default:array()|count >0}
    <br/>
    Deltagere:
    {foreach from=$Participants item=user}
        {$user->FullName()} <a href="mailto:{$user->EmailAddress()}">{$user->EmailAddress()}</a>
        <br/>
    {/foreach}
{/if}

{if $ParticipatingGuests|default:array()|count >0}
    {foreach from=$ParticipatingGuests item=email}
        <a href="mailto:{$email}">{$email}</a>
        <br/>
    {/foreach}
{/if}

{if $Invitees|default:array()|count >0}
    <br/>
    Inviterede:
    {foreach from=$Invitees item=user}
        {$user->FullName()} <a href="mailto:{$user->EmailAddress()}">{$user->EmailAddress()}</a>
        <br/>
    {/foreach}
{/if}

{if $InvitedGuests|default:array()|count >0}
    {foreach from=$InvitedGuests item=email}
        <a href="mailto:{$email}">{$email}</a>
        <br/>
    {/foreach}
{/if}

{if $Accessories|default:array()|count > 0}
	<br/>
	Udstyr:
	<br/>
	{foreach from=$Accessories item=accessory}
		({$accessory->QuantityReserved}) {$accessory->Name}
		<br/>
	{/foreach}
{/if}

{if $CreditsCurrent > 0}
    <br/>
    Denne reservation koster {$CreditsCurrent} kreditter.
    {if $CreditsCurrent != $CreditsTotal}
        Denne serie af reservationer koster {$CreditsTotal} kreditter.
    {/if}
{/if}

{if $Attributes|default:array()|count > 0}
	<br/>
	{foreach from=$Attributes item=attribute}
		<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
	{/foreach}
{/if}

{if $RequiresApproval}
	<br/>
	Mindst én af dine reservationer skal godkendes. En reservation er først endelig, når den er godkendt.
{/if}

{if $CheckInEnabled}
	<br/>
	For mindst én af dine reservationer, er det påkrævet, at du tjekker ind og ud.
	{if $AutoReleaseMinutes != null}
		Reservationen annulleres, hvis der ikke foretages tjek ind, senest {$AutoReleaseMinutes} minutter efter det planlagte starttidspunkt.
	{/if}
{/if}

{if !empty($ApprovedBy)}
	<br/>
	Godkendt af: {$ApprovedBy}
{/if}


{if !empty($CreatedBy)}
	<br/>
	Oprettet af: {$CreatedBy}
{/if}

<br/>
Referencenummer: {$ReferenceNumber}

<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">Se denne reservation</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Tilføj til kalender</a> |
<a href="http://www.google.com/calendar/event?action=TEMPLATE&text={$Title|escape:'url'}&dates={formatdate date=$StartDate->ToUtc() key=google}/{formatdate date=$EndDate->ToUtc() key=google}&ctz={$StartDate->Timezone()}&details={$Description|escape:'url'}&location={$ResourceName|escape:'url'}&trp=false&sprop=&sprop=name:"
   target="_blank" rel="nofollow">Tilføj til Google Kalender</a> |
<a href="{$ScriptUrl}">Log på {$AppTitle}</a>
