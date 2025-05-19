<!-- schedule-mobile.tpl -->
{extends file="Schedule/schedule.tpl"}

{block name="legend"}{/block}

{block name="reservations"}

    {function name=displayGeneralReservedMobile}
        {assign var=badge value=''}
        {if $Slot->IsNew()}{assign var=badge value='<span class="reservation-new">'|cat:{translate key="New"}|cat:'</span>'}{/if}
        {if $Slot->IsUpdated()}{assign var=badge value='<span class="reservation-updated">'|cat:{translate key="Updated"}|cat:'</span>'}{/if}

        {if $Slot->IsPending()}
            {assign var=class value='pending'}
        {elseif $Slot->HasCustomColor()}
            {assign var=color value='style="background-color:'|cat:$Slot->Color()|cat:' !important;color:'|cat:$Slot->TextColor()|cat:' !important;"'}
        {/if}
        <div class="reserved {$class} {$OwnershipClass} clickres" resid="{$Slot->Id()}" {$color}
            id="{$Slot->Id()}|{$Slot->Date()->Format('Ymd')}"><i class="bi bi-info-circle-fill"></i>
            {formatdate date=$Slot->BeginDate() key=period_time} - {formatdate date=$Slot->EndDate() key=period_time}
            {$badge}{$Slot->Label($SlotLabelFactory)|escapequotes}</div>
    {/function}

    {function name=displayAdminReservedMobile}
        {call name=displayGeneralReservedMobile Slot=$Slot Href=$Href OwnershipClass='admin'}
    {/function}

    {function name=displayMyReservedMobile}
        {call name=displayGeneralReservedMobile Slot=$Slot Href=$Href SlotRef=$SlotRef OwnershipClass='mine'}
    {/function}

    {function name=displayMyParticipatingMobile}
        {call name=displayGeneralReservedMobile Slot=$Slot Href=$Href SlotRef=$SlotRef OwnershipClass='participating'}
    {/function}

    {function name=displayReservedMobile}
        {call name=displayGeneralReservedMobile Slot=$Slot Href=$Href SlotRef=$SlotRef OwnershipClass=''}
    {/function}

    {function name=displayPastTimeMobile}
    {/function}

    {function name=displayReservableMobile}
    {/function}

    {function name=displayRestrictedMobile}
    {/function}

    {function name=displayUnreservableMobile}
        <div class="unreservable" resid="{$Slot->Id()}" {$color} id="{$Slot->Id()}|{$Slot->Date()->Format('Ymd')}"><i
                class="bi bi-info-circle-fill"></i>
            {formatdate date=$Slot->BeginDate() key=period_time} - {formatdate date=$Slot->EndDate() key=period_time}
            {$Slot->Label($SlotLabelFactory)|escapequotes}</div>
    {/function}

    {function name=displaySlotMobile}
        {call name=$DisplaySlotFactory->GetFunction($Slot, $AccessAllowed, 'Mobile') Slot=$Slot Href=$Href SlotRef=$SlotRef}
    {/function}

    {assign var=TodaysDate value=Date::Now()}
    <table class="reservations mobile" border="1" cellpadding="0" style="width:100%;">

        {foreach from=$BoundDates item=date}
            {assign var=ts value=$date->Timestamp()}
            {$periods.$ts = $DailyLayout->GetPeriods($date, false)}
            {assign var=count value=$periods[$ts]|default:array()|count}
            {if $count== 0}{continue}{*dont show if there are no slots*}{/if}
            {assign var=min value=$periods[$ts][0]->BeginDate()->TimeStamp()}
            {assign var=max value=$periods[$ts][$count-1]->EndDate()->TimeStamp()}
            {assign var=resourceId value=$resource->Id}
            <tr>
                {assign var=class value=""}
                {if $TodaysDate->DateEquals($date) eq true}
                    {assign var=class value="today"}
                {/if}
                <td class="resdate {$class}" colspan="2">{formatdate date=$date key="schedule_daily"}</td>
            </tr>
            {foreach from=$Resources item=resource name=resource_loop}
                <tr>
                    {assign var=resourceId value=$resource->Id}
                    {assign var=href value="{Pages::RESERVATION}?rid={$resourceId}&sid={$ScheduleId}&rd={formatdate date=$date key=url}"}
                    <td class="resourcename" {if $resource->HasColor()}style="background-color:{$resource->GetColor()} !important"
                        {/if}>
                        {if $resource->CanAccess}
                            <i resourceId="{$resourceId}" class="resourceNameSelector bi bi-info-circle-fill" data-show-event="click"
                                {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important" {/if}></i>
                            <a href="{$href}" resourceId="{$resourceId}"
                                {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"
                                {/if}>{$resource->Name}</a>
                        {else}
                            <i resourceId="{$resourceId}" class="resourceNameSelector bi bi-info-circle-fill"
                                {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important" {/if}></i>
                            <span {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"
                                {/if}>{$resource->Name}</span>
                        {/if}
                    </td>
                    {assign var=href value="{$CreateReservationPage}?rid={$resource->Id}&sid={$ScheduleId}&rd={formatdate date=$date key=url}"}
                    <td class="" data-href="{$href}" data-start="{$date->Format('Y-m-d H:i:s')|escape:url}"
                        data-resourceId="{$resourceId}">
                        <div class="reservable clickres" ref="{$href}&rd={formatdate date=$date key=url}" data-href="{$href}"
                            data-start="{$date->Format('Y-m-d H:i:s')|escape:url}"
                            data-end="{$date->Format('Y-m-d H:i:s')|escape:url}">
                            <i class="bi bi-plus-circle-fill"></i> {translate key=CreateReservation}
                            <input type="hidden" class="href" value="{$href}" />
                        </div>
                        <div class="reservations" data-min="{$min}" data-max="{$max}" data-resourceid="{$resource->Id}"></div>
                    </td>
                </tr>
            {/foreach}
        {/foreach}
    </table>
{/block}
