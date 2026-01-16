<!-- schedule-reservations-grid.tpl -->
{function name=displaySlot}
    {call name=$DisplaySlotFactory->GetFunction($Slot, $AccessAllowed) Slot=$Slot Href=$Href SlotRef=$SlotRef ResourceId=$ResourceId}
{/function}

{assign var=TodaysDate value=Date::Now()}
{* Is this a view page and guests are allowed to book *}
{assign var=GuestViewBookable value=($LoadViewOnly && $AllowGuestBooking)}
{foreach from=$BoundDates item=date}
    {assign var=ts value=$date->Timestamp()}
    {$periods.$ts = $DailyLayout->GetPeriods($date, true)}
    {$slots.$ts = $DailyLayout->GetPeriods($date, false)}
    {assign var=count value=$periods[$ts]|default:array()|count}
    {assign var=slotCount value=$slots[$ts]|default:array()|count}
    {if $count== 0}{continue}{*dont show if there are no slots*}{/if}
    {assign var=min value=$slots[$ts][0]->BeginDate()->TimeStamp()}
    {assign var=max value=$slots[$ts][$slotCount-1]->EndDate()->TimeStamp()}
    <table class="reservations" data-min="{$min}" data-max="{$max}">
        <thead>
            {if $date->DateEquals($TodaysDate)}
                <tr class="today">
                {else}
                <tr>
                {/if}
                <td class="resdate">{formatdate date=$date key="schedule_daily"}</td>
                {foreach from=$periods.$ts item=period}
                    <td class="reslabel text-center" colspan="{$period->Span()}">{$period->Label($date)}</td>
                {/foreach}
            </tr>
        </thead>
        <tbody>
            {foreach from=$Resources item=resource name=resource_loop}
                {assign var=resourceId value=$resource->Id}
                {assign var=href value="{$CreateReservationPage}?rid={$resource->Id}&sid={$ScheduleId}&rd={formatdate date=$date key=url}"}
                <tr class="slots" data-resourceid="{$resource->GetId()}">
                    <td class="resourcename"
                        {if $resource->HasColor()}style="background-color:{$resource->GetColor()} !important" {/if}>
                        {if ($resource->CanBook || $GuestViewBookable) && $DailyLayout->IsDateReservable($date)}
                            <span resourceId="{$resourceId}"
                                class="d-sm-inline-block d-md-none resourceNameSelector bi bi-info-circle-fill"
                                data-show-event="click"
                                {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important" {/if}></span>
                            <a href="{$href}" resourceId="{$resource->Id}" class="resourceNameSelector"
                                {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"
                                {/if}>{$resource->Name}</a>
                        {else}
                            <span resourceId="{$resource->Id}" resourceId="{$resource->Id}" class="resourceNameSelector"
                                {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"
                                {/if}>{$resource->Name}</span>
                        {/if}
                    </td>
                    {foreach from=$slots.$ts item=Slot}
                        {assign var=slotRef value="{$Slot->BeginDate()->Format('YmdHis')}{$resourceId}"}
                        {displaySlot Slot=$Slot Href="$href" AccessAllowed=($resource->CanBook || $GuestViewBookable) SlotRef=$slotRef ResourceId=$resourceId}
                    {/foreach}
                </tr>
            {/foreach}
        </tbody>
    </table>
    {*    {flush}*}
{/foreach}
