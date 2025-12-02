{if $authorized}
    {*CHECK IF USER HAS PERMISSIONS TO THE RESOURCES OF THE RESERVATIONS, HIDE DETAILS IF HE DOESN'T HAVE PERMISSIONS TO ALL OF THEM*}
    {assign var=isResourcePermitted value=false}
    {foreach from=$resources item=checkResourcePermission}
        {if in_array($checkResourcePermission->Id(), $CanViewResourceReservations)}
            {assign var=isResourcePermitted value=true}
            {break};
        {/if}
    {{/foreach}}
    {*HOWEVER THE USER CAN SEE THE RESERVATION IF HE IS A OWNER, PARTICIPANT OR INVITEE*}
    {if $isResourcePermitted == false}
        {if $UserId == $OwnerId || $IAmParticipating || $IAmInvited}
            {assign var=isResourcePermitted value=true}
        {/if}
    {/if}

    <div class="res_popup_details mb-0">

        {capture "name"}
            <div class="user fw-bold">
                {if ($hideUserInfo || $hideDetails) || !$isResourcePermitted}
                    {translate key=Private}
                {else}
                    {$fullName}
                {/if}
            </div>
        {/capture}
        {$formatter->Add('name', $smarty.capture.name)}

        {capture "email"}
            <div class="email">
                {if !$hideUserInfo && !$hideDetails && $isResourcePermitted}
                    {$email}
                {/if}
            </div>
        {/capture}
        {$formatter->Add('email', $smarty.capture.email)}

        {capture "phone"}
            <div class="phone">
                {if !$hideUserInfo && !$hideDetails && $isResourcePermitted}
                    {$phone}
                {/if}
            </div>
        {/capture}
        {$formatter->Add('phone', $smarty.capture.phone)}

        {capture "dates"}
            {assign var="key" value="res_popup"}
            {if $startDate->DateEquals($endDate)}
                {assign var="key" value="res_popup_time"}
            {/if}
            <div class="dates">{formatdate date=$startDate key=res_popup} - {formatdate date=$endDate key=$key}</div>
        {/capture}
        {$formatter->Add('dates', $smarty.capture.dates)}

        {capture "title"}
            {if !$hideDetails && $isResourcePermitted}
                <div class="title">{if $title neq ''}{$title|escape:'html'}{else}{translate key=NoTitleLabel}{/if}</div>
            {/if}
        {/capture}
        {$formatter->Add('title', $smarty.capture.title)}

        {capture "resources"}
            <div class="resources">
                {translate key="Resources"} ({$resources|@count}):
                {foreach from=$resources item=resource name=resource_loop}
                    {$resource->Name()}

                    {if !$smarty.foreach.resource_loop.last}, {/if}
                {/foreach}
                {if !$isResourcePermitted}
                    <p class="text-danger">{translate key='NoResourcePermissions'}</p>
                {/if}
            </div>
        {/capture}
        {$formatter->Add('resources', $smarty.capture.resources)}

        {capture "participants"}
            {if !$hideUserInfo && !$hideDetails && $isResourcePermitted}
                <div class="users">
                    {translate key="Participants"} ({$participants|@count}):
                    {foreach from=$participants item=user name=participant_loop}
                        {if !$user->IsOwner()}
                            {fullname first=$user->FirstName|unescape:'html' last=$user->LastName|unescape:'html'}
                        {/if}
                        {if !$smarty.foreach.participant_loop.last}, {/if}
                    {/foreach}
                </div>
            {/if}
        {/capture}
        {$formatter->Add('participants', $smarty.capture.participants)}

        {capture "accessories"}
            {if !$hideDetails && $isResourcePermitted}
                <div class="accessories">
                    {translate key="Accessories"} ({$accessories|@count}):
                    {foreach from=$accessories item=accessory name=accessory_loop}
                        {$accessory->Name} ({$accessory->QuantityReserved})
                        {if !$smarty.foreach.accessory_loop.last}, {/if}
                    {/foreach}
                </div>
            {/if}
        {/capture}
        {$formatter->Add('accessories', $smarty.capture.accessories)}

        {capture "description"}
            {if !$hideDetails && $isResourcePermitted}
                <div class="summary">
                    {if $summary neq ''}{$summary|truncate:300:"..."|escape:'html'|nl2br}{else}{translate key=NoDescriptionLabel}{/if}</div>
            {/if}
        {/capture}
        {$formatter->Add('description', $smarty.capture.description)}

        {capture "attributes"}
            {if !$hideDetails && $isResourcePermitted}
                {if $attributes|default:array()|count > 0}
                    <br />
                    {foreach from=$attributes item=attribute}
                        {assign var=attr value="att`$attribute->Id()`"}
                        {capture name="attr"}
                            <div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
                        {/capture}
                        {$smarty.capture.attr}
                        {$formatter->Add($attr, $smarty.capture.attr)}
                    {/foreach}
                {/if}
            {/if}
        {/capture}
        {$formatter->Add('attributes', $smarty.capture.attributes)}

        {capture "pending"}
            {if $requiresApproval}
                <div class="pendingApproval text-warning">{translate key=PendingApproval}</div>
            {/if}
        {/capture}
        {$formatter->Add('pending', $smarty.capture.pending)}

        {capture "duration"}
            <div class="duration">{$duration}</div>
        {/capture}
        {$formatter->Add('duration', $smarty.capture.duration)}
        <!-- {$ReservationId} -->

        {$formatter->Display()}
    </div>
{else}
    {translate key='InsufficientPermissionsError'}
{/if}
