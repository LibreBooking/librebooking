{if $Deleted}
    <p>{$UserName} has deleted a reservation</p>
    {else}
    <p>{$UserName} has added you to a reservation</p>
{/if}

{if !empty($DeleteReason)}
    <p><strong>Delete Reason:</strong>{$DeleteReason|nl2br}</p>
{/if}

<p><strong>Reservation Details:</strong></p>

<p>
    <strong>Start:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
    <strong>End:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
</p>

<p>
{if $ResourceNames|default:array()|count > 1}
    <strong>Resources ({$ResourceNames|default:array()|count}):</strong> <br />
    {foreach from=$ResourceNames item=resourceName}
        {$resourceName}<br/>
    {/foreach}
{else}
    <strong>Resource:</strong> {$ResourceName}<br/>
{/if}
</p>

{if $ResourceImage}
    <div class="resource-image"><img alt="{$ResourceName|escape}" src="{$ScriptUrl}/{$ResourceImage}"/></div>
{/if}

{if $RequiresApproval && !$Deleted}
    <p>* One or more of the resources reserved require approval before usage. This reservation will be pending until it is approved. *</p>
{/if}

<p>
    <strong>Title:</strong> {$Title}<br/>
    <strong>Description:</strong> {$Description|nl2br}
</p>

{if count($RepeatRanges) gt 0}
    <br/>
    <strong>The reservation occurs on the following dates ({$RepeatRanges|default:array()|count}):</strong>
    <br/>
{/if}

{foreach from=$RepeatRanges item=date name=dates}
    {formatdate date=$date->GetBegin()}
    {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
    <br/>
{/foreach}

{if $Participants|default:array()|count >0}
    <br />
    <strong>Participants ({$Participants|default:array()|count + $ParticipatingGuests|default:array()|count}):</strong>
    <br />
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
    <br />
    <strong>Invitees ({$Invitees|default:array()|count + $InvitedGuests|default:array()|count}):</strong>
    <br />
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
    <br />
       <strong>Accessories ({$Accessories|default:array()|count}):</strong>
       <br />
    {foreach from=$Accessories item=accessory}
        ({$accessory->QuantityReserved}) {$accessory->Name}
        <br/>
    {/foreach}
{/if}

{if !$Deleted && !$Updated}
<p>
    <strong>Attending?</strong> <a href="{$ScriptUrl}/{$AcceptUrl}">Yes</a> <a href="{$ScriptUrl}/{$DeclineUrl}">No</a>
</p>
{/if}

{if !$Deleted}
<a href="{$ScriptUrl}/{$ReservationUrl}">View this reservation</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Add to Calendar</a> |
<a href="{$GoogleCalendarUrl}" target="_blank" rel="nofollow">Add to Google Calendar</a> |
{/if}
<a href="{$ScriptUrl}">Log in to {$AppTitle}</a>
