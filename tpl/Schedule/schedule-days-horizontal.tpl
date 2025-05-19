<!-- schedule-days-horizontal.tpl -->
{extends file="Schedule/schedule.tpl"}

{function name=displaySlot}
    {call name=$DisplaySlotFactory->GetFunction($Slot, $AccessAllowed) Slot=$Slot Href=$Href SlotRef=$SlotRef ResourceId=$ResourceId}
{/function}

{block name="reservations"}
    {assign var=TodaysDate value=Date::Now()}
    {assign var=min value=$BoundDates[0]->TimeStamp()}
    {assign var=firstPeriods value=$DailyLayout->GetPeriods($BoundDates[0])}
    {assign var=lastPeriods value=$DailyLayout->GetPeriods($BoundDates[$BoundDates|default:array()|count-1])}
    {assign var=min value=$firstPeriods[0]->BeginDate()->TimeStamp()}
    {assign var=max value=$lastPeriods[$lastPeriods|default:array()|count-1]->EndDate()->TimeStamp()}
    <table class="reservations" border="1" cellpadding="0" style="width:auto;" data-min="{$min}" data-max="{$max}">
        <thead>
        <tr>
            <td rowspan="2">&nbsp;</td>
            {foreach from=$BoundDates item=date}
                {assign var=class value=""}
                {assign var=ts value=$date->Timestamp()}
                {$periods.$ts = $DailyLayout->GetPeriods($date, false)}
                {$slots.$ts = $DailyLayout->GetPeriods($date, false)}
                {if $periods[$ts]|default:array()|count == 0}{continue}{*dont show if there are no slots*}{/if}
                {if $date->DateEquals($TodaysDate)}
                    {assign var=class value="today"}
                {/if}
                <td class="resdate {$class}"
                    colspan="{$periods[$ts]|default:array()|count}">{formatdate date=$date key="schedule_daily"}</td>
            {/foreach}
        </tr>
        <tr>
            {foreach from=$BoundDates item=date}
                {assign var=ts value=$date->Timestamp()}
                {assign var=datePeriods value=$periods[$ts]}
                {foreach from=$datePeriods item=period}
                    <td class="reslabel" colspan="{$period->Span()}">{$period->Label($date)}</td>
                {/foreach}
            {/foreach}
        </tr>
        </thead>
        <tbody>

        {foreach from=$Resources item=resource name=resource_loop}
            {assign var=resourceId value=$resource->Id}
            {assign var=href value="{Pages::RESERVATION}?rid={$resource->Id}&sid={$ScheduleId}"}
            <tr class="slots">
                <td class="resourcename"
                    {if $resource->HasColor()}style="background-color:{$resource->GetColor()} !important"{/if}>
                    {if $resource->CanAccess}
                        <a href="{$href}" resourceId="{$resource->Id}"
                           class="resourceNameSelector"
                           {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"{/if}>{$resource->Name}</a>
                    {else}
                        <span resourceId="{$resourceId}" resourceId="{$resourceId}" class="resourceNameSelector"
                              {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"{/if}>{$resource->Name}</span>
                    {/if}
                </td>
                {foreach from=$BoundDates item=date}
                    {assign var=ts value=$date->Timestamp()}
                    {foreach from=$slots.$ts item=Slot}
                        {assign var=href value="{Pages::RESERVATION}?rid={$resource->Id}&sid={$ScheduleId}&rd={formatdate date=$date key=url}"}
                        {assign var=slotRef value="{$Slot->BeginDate()->Format('YmdHis')}{$resourceId}"}
                        {displaySlot Slot=$Slot Href="$href" AccessAllowed=$resource->CanAccess SlotRef=$slotRef ResourceId=$resourceId}
                    {/foreach}
                {/foreach}
            </tr>
        {/foreach}
        </tbody>
    </table>
{/block}

{block name="scripts-before"}

{/block}
