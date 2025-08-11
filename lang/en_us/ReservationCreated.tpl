<p><strong>Reservation Details:</strong></p>

<p>
	<strong>Start:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>End:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Title:</strong> {$Title}<br/>
	<strong>Description:</strong> {$Description|nl2br}
	{if $Attributes|default:array()|count > 0}
		<br/>
	    {foreach from=$Attributes item=attribute}
			<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
	    {/foreach}
	{/if}
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

{if $RequiresApproval}
	<p>* At least one of the resources reserved requires approval before usage. This reservation will be pending until it is approved. *</p>
{/if}

{if $CheckInEnabled}
	<p>
	At least one of the resources reserved requires you to check in and out of your reservation.
    {if $AutoReleaseMinutes != null}
		This reservation will be cancelled unless you check in within {$AutoReleaseMinutes} minutes after the scheduled start time.
    {/if}
	</p>
{/if}

{if count($RepeatRanges) gt 0}
    <br/>
    <strong>The reservation occurs on the following dates ({$RepeatRanges|default:array()|count}):</strong>
    <br/>
	{foreach from=$RepeatRanges item=date name=dates}
	    {formatdate date=$date->GetBegin()}
	    {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
	    <br/>
	{/foreach}
{/if}

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

{if $CreditsCurrent > 0}
	<br/>
	This reservation costs {$CreditsCurrent} credits.
    {if $CreditsCurrent != $CreditsTotal}
		This entire reservation series costs {$CreditsTotal} credits.
    {/if}
{/if}


{if !empty($CreatedBy)}
	<p><strong>Created by:</strong> {$CreatedBy}</p>
{/if}

{if !empty($ApprovedBy)}
	<p><strong>Approved by:</strong> {$ApprovedBy}</p>
{/if}

<p><strong>Reference Number:</strong> {$ReferenceNumber}</p>

{if !$Deleted}
	<a href="{$ScriptUrl}/{$ReservationUrl}">View this reservation</a>
	|
	<a href="{$ScriptUrl}/{$ICalUrl}">Add to Calendar</a>
	|
	<a href="{$GoogleCalendarUrl}" target="_blank" rel="nofollow">Add to Google Calendar</a>
	|
{/if}
<a href="{$ScriptUrl}">Log in to {$AppTitle}</a>

