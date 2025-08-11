Varauksen tiedot:
<br/>
<br/>

Alkaa: {formatdate date=$StartDate key=reservation_email}<br/>
Päättyy: {formatdate date=$EndDate key=reservation_email}<br/>
Resurssi: {$ResourceName}<br/>

{if $ResourceImage}
    <div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
{/if}

Otsikko: {$Title}<br/>
Kuvaus: {$Description|nl2br}<br/>
{$DeleteReason|nl2br}<br/>


{if count($RepeatDates) gt 0}
    <br/>
    Varaus toistuu seuraavina päivinä:
    <br/>
{/if}

{foreach from=$RepeatDates item=date name=dates}
    {formatdate date=$date}
    <br/>
{/foreach}

<a href="{$ScriptUrl}">Kirjaudu sovellukseen LibreBooking</a>


