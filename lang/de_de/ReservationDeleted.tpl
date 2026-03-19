Hallo {$FirstName},<br/>
die folgende Reservierung wurde gel&ouml;scht.<br/>
<br/>
Reservierungsdetails:
<br/>
<br/>

Benutzer: {$UserName}
Beginn: {formatdate date=$StartDate key=reservation_email}<br/>
Ende: {formatdate date=$EndDate key=reservation_email}<br/>
{if $ResourceNames|default:array()|count > 1}
    Ressourcen:
    <br/>
    {foreach from=$ResourceNames item=resourceName}
        {$resourceName}
        <br/>
    {/foreach}
{else}
    Ressource: {$ResourceName}
    <br/>
{/if}

{if $ResourceImage}
    <div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
{/if}

Titel: {$Title}<br/>
Beschreibung: {$Description|nl2br}<br/>
{$DeleteReason|nl2br}<br/>


{if count($RepeatDates) gt 0}
    <br/>
    Die folgenden Reservierungen wurden entfernt:
    <br/>
{/if}

{foreach from=$RepeatDates item=date name=dates}
    {formatdate date=$date}
    <br/>
{/foreach}

{if $Accessories|default:array()|count > 0}
    <br/>
    Zubehör:
    <br/>
    {foreach from=$Accessories item=accessory}
        ({$accessory->QuantityReserved}) {$accessory->Name}
        <br/>
    {/foreach}
{/if}
<br/>
<a href="{$ScriptUrl}">{$AppTitle}-Login</a>
