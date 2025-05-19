<!-- schedule-reservations-grid-static.tpl -->
{function name=displaySlot}
    {call name=$DisplaySlotFactory->GetFunction($Slot, $AccessAllowed) Slot=$Slot Href=$Href SlotRef=$SlotRef ResourceId=$ResourceId}
{/function}

{assign var=TodaysDate value=Date::Now()}
{foreach from=$BoundDates item=date}
    {assign var=ts value=$date->Timestamp()}
    {$periods.$ts = $DailyLayout->GetPeriods($date, true)}
    {if $periods[$ts]|default:array()|count == 0}{continue}{*dont show if there are no slots*}{/if}
    <div style="position:relative;">
        <table class="reservations" border="1" cellpadding="0" width="100%">
            <thead>
            {if $date->DateEquals($TodaysDate)}
            <tr class="today">
                {else}
            <tr>
                {/if}
                <td class="resdate">{formatdate date=$date key="schedule_daily"}</td>
                {foreach from=$periods.$ts item=period}
                    <td class="reslabel"
                        colspan="{$period->Span()}">{$period->Label($date)}</td>
                {/foreach}
            </tr>
            </thead>
            <tbody>
            {foreach from=$Resources item=resource name=resource_loop}
                {assign var=resourceId value=$resource->Id}
                {assign var=slots value=$DailyLayout->GetLayout($date, $resourceId)}
                {assign var=href value="{$CreateReservationPage}?rid={$resource->Id}&sid={$ScheduleId}&rd={formatdate date=$date key=url}"}
                <tr class="slots">
                    <td class="resourcename"
                        {if $resource->HasColor()}style="background-color:{$resource->GetColor()} !important"{/if}>
                        {if $resource->CanAccess && $DailyLayout->IsDateReservable($date)}
                            <a href="{$href}" resourceId="{$resource->Id}"
                               class="resourceNameSelector"
                               {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"{/if}>{$resource->Name}</a>
                        {else}
                            <span resourceId="{$resource->Id}" resourceId="{$resource->Id}"
                                  class="resourceNameSelector"
                                  {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"{/if}>{$resource->Name}</span>
                        {/if}
                    </td>
                    {foreach from=$slots item=slot}
                        {assign var=slotRef value="{$slot->BeginDate()->Format('YmdHis')}{$resourceId}"}
                        {displaySlot Slot=$slot Href="$href" AccessAllowed=$resource->CanAccess SlotRef=$slotRef ResourceId=$resourceId}
                    {/foreach}
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    {flush}
{/foreach}
