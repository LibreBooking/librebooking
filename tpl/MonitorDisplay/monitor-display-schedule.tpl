{function name=displayGeneralReserved}
    {if $Slot->IsPending()}
        {assign var=class value='pending'}
    {/if}
    {if $Slot->HasCustomColor()}
        {assign var=color value='style="background-color:'|cat:$Slot->Color()|cat:' !important;color:'|cat:$Slot->TextColor()|cat:' !important;"'}
    {/if}
    <td {$spantype|default:'col'}span="{$Slot->PeriodSpan()}" class="reserved {$class} {$OwnershipClass} clickres slot"
        resid="{$Slot->Id()}" {$color} {if $Draggable}draggable="true" {/if} data-resourceId="{$ResourceId}"
        id="{$Slot->Id()}|{$Slot->Date()->Format('Ymd')}">{$Slot->Label($SlotLabelFactory)|escapequotes}</td>
{/function}

{function name=displayMyReserved}
    {call name=displayGeneralReserved Slot=$Slot Href=$Href SlotRef=$SlotRef OwnershipClass='mine' Draggable=true ResourceId=$ResourceId}
{/function}

{function name=displayAdminReserved}
    {call name=displayGeneralReserved Slot=$Slot Href=$Href SlotRef=$SlotRef OwnershipClass='admin' Draggable=true ResourceId=$ResourceId}
{/function}

{function name=displayMyParticipating}
    {call name=displayGeneralReserved Slot=$Slot Href=$Href SlotRef=$SlotRef OwnershipClass='participating' ResourceId=$ResourceId}
{/function}

{function name=displayReserved}
    {call name=displayGeneralReserved Slot=$Slot Href=$Href SlotRef=$SlotRef OwnershipClass='' Draggable="{$CanViewAdmin}"
    ResourceId=$ResourceId}
{/function}

{function name=displayPastTime}
    <td {$spantype|default:'col'}span="{$Slot->PeriodSpan()}" ref="{$SlotRef}" class="pasttime slot"
        draggable="{$CanViewAdmin}" resid="{$Slot->Id()}" data-resourceId="{$ResourceId}">
        {$Slot->Label($SlotLabelFactory)|escapequotes}</td>
{/function}

{function name=displayReservable}
    <td {$spantype|default:'col'}span="{$Slot->PeriodSpan()}" ref="{$SlotRef}" class="reservable clickres slot"
        data-href="{$Href}" data-start="{$Slot->BeginDate()->Format('Y-m-d H:i:s')|escape:url}"
        data-end="{$Slot->EndDate()->Format('Y-m-d H:i:s')|escape:url}" data-resourceId="{$ResourceId}">&nbsp;
    </td>
{/function}

{function name=displayRestricted}
    <td {$spantype|default:'col'}span="{$Slot->PeriodSpan()}" class="restricted slot">&nbsp;</td>
{/function}

{function name=displayUnreservable}
    <td {$spantype|default:'col'}span="{$Slot->PeriodSpan()}" class="unreservable slot">
        {$Slot->Label($SlotLabelFactory)|escape}</td>
{/function}

<h1 id="scheduleName" class="text-center"></h1>

{if $Format == 1}
    {include file="Schedule/schedule-reservations-grid-static.tpl"}
{else}
    {assign var=TodaysDate value=Date::Now()}
    {foreach from=$BoundDates item=date}
        <div class="alert alert-light">
            <div class="monitor-display-date fw-bold fs-5">{formatdate date=$date}</div>
            {foreach from=$Resources item=resource name=resource_loop}
                {if $resource->HasColor()}{assign var=style value="color:{$resource->GetTextColor()}!important;background-color:{$resource->GetColor()}
    !important;"}{/if}
                <div class="monitor-display-resource-name fw-bold fs-6">{$resource->Name}</div>
                {assign var=slots value=$DailyLayout->GetLayout($date, $resource->Id)}
                {foreach from=$slots item=slot}
                    {if $slot->IsReserved()}
                        <div class="reserved rounded-1 p-1 mb-1" style="{$style}">
                            {formatdate date=$slot->BeginDate() key=period_time} -
                            {assign var=slotformat value=period_time}
                            {if !$slot->BeginDate()->DateEquals($slot->EndDate())}
                                {assign var=slotformat value=short_reservation_date}
                            {/if}
                            {formatdate date=$slot->EndDate() key=$slotformat}
                            {$slot->Label($SlotLabelFactory)|escapequotes}
                        </div>
                    {/if}
                {/foreach}
            {/foreach}
        </div>
    {/foreach}
{/if}