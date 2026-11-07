Foglalás részletei:
<br/>
<br/>

Kezdés: {formatdate date=$StartDate key=reservation_email}<br/>
Befejezés: {formatdate date=$EndDate key=reservation_email}<br/>
{if $ResourceNames|default:array()|count > 1}
    Elemel:
    <br/>
    {foreach from=$ResourceNames item=resourceName}
        {$resourceName}
        <br/>
    {/foreach}
{else}
    Elem: {$ResourceName}
    <br/>
{/if}

{if $ResourceImage}
    <div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
{/if}

Megnevezés: {$Title}<br/>
Leírás: {$Description|nl2br}

{if count($RepeatRanges) gt 0}
    <br/>
    A foglaás az alábbi dátumokon érvényes:
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
        {$user->FullName()}
        <br/>
    {/foreach}
{/if}

{if $ParticipatingGuests|default:array()|count >0}
    {foreach from=$ParticipatingGuests item=email}
        {$email}
        <br/>
    {/foreach}
{/if}

{if $Invitees|default:array()|count >0}
    <br/>
    Meghívottak:
    {foreach from=$Invitees item=user}
        {$user->FullName()}
        <br/>
    {/foreach}
{/if}

{if $InvitedGuests|default:array()|count >0}
    {foreach from=$InvitedGuests item=email}
        {$email}
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

{if $RequiresApproval}
    <br/>
    A foglalt elemek legalább egyike jóváhagyást igényel. A foglalás függőben marad jóváhagyásáig.
{/if}

<br/>
Részt vesz? <a href="{$ScriptUrl}/{$AcceptUrl}">Igen</a> <a href="{$ScriptUrl}/{$DeclineUrl}">Nem</a>
<br/>
<br/>

<a href="{$ScriptUrl}/{$ReservationUrl}">A foglalás megtekintése</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Naptárhoz adás</a> |
<a href="{$GoogleCalendarUrl}" target="_blank" rel="nofollow">Hozzáadás a Google Naptárhoz</a> |
<a href="{$ScriptUrl}">Bejelentkezés ide: {$AppTitle}</a>
