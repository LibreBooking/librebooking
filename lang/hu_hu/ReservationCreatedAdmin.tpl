A foglalás részletei:
<br/>
<br/>

Felhasználó: {$UserName}<br/>
{if !empty($CreatedBy)}
	Created by: {$CreatedBy}
	<br/>
{/if}
Kezdés: {formatdate date=$StartDate key=reservation_email}<br/>
Befejezés: {formatdate date=$EndDate key=reservation_email}<br/>
<p>
    {if $Resources|default:array()|count > 1}
    <strong>Erőforrások ({$Resources|default:array()|count}):</strong>
    <br/>
    {else}
    <strong>Erőforrás:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>Ütemezés:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>Erőforrás azonosító:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>Helyszín:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>Kapcsolat:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>Leírás:</strong> {$resource.description|escape|nl2br}<br/>{/if}
        {if $resource.notes}<strong>Megjegyzések:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
        {if $resource.resourceAdministrator}<strong>Erőforrás rendszergazda:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

        {if $resource.attributeRows|default:array()|count > 0}
            <strong>Erőforrás részletei:</strong><br/>
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

Megnevezés: {$Title}<br/>
Leírás: {$Description|nl2br}

{if count($RepeatRanges) gt 0}
    <br/>
    A foglalás az alábbi dátumokra esik:
    <br/>
{/if}

{foreach from=$RepeatRanges item=date name=dates}
    {formatdate date=$date->GetBegin()}
    {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
    <br/>
{/foreach}

{if $Participants|default:array()|count >0}
    <br/>
    Résztvevők:
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
    Meghívottak:
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
	Kiegészítők:
	<br/>
	{foreach from=$Accessories item=accessory}
		({$accessory->QuantityReserved}) {$accessory->Name}
		<br/>
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
	A foglalt elemek legalább egyike jóváhagyást igényel. Kérjük, biztosítsa a foglalás jóváhagyását vagy elvetését.
{/if}

{if $CheckInEnabled}
	<br/>
	A foglalt elemek legalább egyike ki- és bejelentkezést igényel a fogalásba/ból.
	{if $AutoReleaseMinutes != null}
		Eza foglalás törlésre kerül, amennyiben nem történik bejelentkezés {$AutoReleaseMinutes} perccel az ütemezett kezdést követően.
	{/if}
{/if}

<br/>
Referenciaszám: {$ReferenceNumber}

<br/>
<br/>
<a href="{$ScriptUrl}/{$ReservationUrl}">Foglalás megtekintése</a> | <a href="{$ScriptUrl}">Bejelentkezés ide: {$AppTitle}</a>
