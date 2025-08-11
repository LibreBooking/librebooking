Bokningsdetaljer:
<br/>
<br/>

Er bokning börjar: {formatdate date=$StartDate key=reservation_email}<br/>
Er bokning slutar: {formatdate date=$EndDate key=reservation_email}<br/>
Bokning: {$ResourceName}<br/>

{if $ResourceImage}
    <div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
{/if}

Rubrik: {$Title}<br/>
Beskrivning: {$Description|nl2br}<br/>
{$DeleteReason|nl2br}<br/>


{if count($RepeatDates) gt 0}
    <br/>
    Följande bokning har ångrats:
    <br/>
{/if}

{foreach from=$RepeatDates item=date name=dates}
    {formatdate date=$date}
    <br/>
{/foreach}

<a href="{$ScriptUrl}">Logga in i Bokningsprogrammet</a>

