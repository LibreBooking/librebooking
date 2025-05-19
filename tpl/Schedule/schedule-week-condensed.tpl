<!-- schedule-week-condensed.tpl -->
{extends file="Schedule/schedule.tpl"}

{block name="legend"}{/block}

{block name="reservations"}

    {function name=displayGeneralReservedCondensed}
        {if $Slot->IsPending()}
            {assign var=class value='pending'}
        {/if}
        {if $Slot->HasCustomColor()}
            {assign var=color value='style="background-color:'|cat:$Slot->Color()|cat:' !important;color:'|cat:$Slot->TextColor()|cat:' !important;border-color:'|cat:$Slot->BorderColor()|cat:' !important;"'}
        {/if}
        <div class="reserved {$class} {$OwnershipClass} clickres" resid="{$Slot->Id()}" {$color}
            id="{$Slot->Id()}|{$Slot->Date()->Format('Ymd')}">
            {$DisplaySlotFactory->GetCondensedPeriodLabel($Periods, $Slot->BeginDate(), $Slot->EndDate())}
            {$Slot->Label($SlotLabelFactory)|escapequotes}</div>
    {/function}

    {function name=displayAdminReservedCondensed}
        {call name=displayGeneralReservedCondensed Slot=$Slot Href=$Href OwnershipClass='admin'}
    {/function}

    {function name=displayMyReservedCondensed}
        {call name=displayGeneralReservedCondensed Slot=$Slot Href=$Href OwnershipClass='mine'}
    {/function}

    {function name=displayMyParticipatingCondensed}
        {call name=displayGeneralReservedCondensed Slot=$Slot Href=$Href OwnershipClass='participating'}
    {/function}

    {function name=displayReservedCondensed}
        {call name=displayGeneralReservedCondensed Slot=$Slot Href=$Href OwnershipClass=''}
    {/function}

    {function name=displayPastTimeCondensed}
    {/function}

    {function name=displayReservableCondensed}
    {/function}

    {function name=displayRestrictedCondensed}
    {/function}

    {function name=displayUnreservableCondensed}
        <div class="unreservable" resid="{$Slot->Id()}" {$color} id="{$Slot->Id()}|{$Slot->Date()->Format('Ymd')}">
            {formatdate date=$Slot->BeginDate() key=period_time} - {formatdate date=$Slot->EndDate() key=period_time}
            {$Slot->Label($SlotLabelFactory)|escapequotes}</div>
    {/function}

    {function name=displaySlotCondensed}
        {call name=$DisplaySlotFactory->GetFunction($Slot, $AccessAllowed, 'Condensed') Slot=$Slot Href=$Href Periods=$Periods}
    {/function}

    {assign var=TodaysDate value=Date::Now()}
    {assign var=columnWidth value=(1/($BoundDates|default:array()|count+1))*100}
    <div id="reservations">
        <table class="reservations condensed w-100" cellpadding="0">
            <thead>
                <tr>
                    <td style="width:{$columnWidth}%">&nbsp;</td>
                    {foreach from=$BoundDates item=date}
                        {assign var=class value=""}
                        {assign var=tdclass value=""}
                        {assign var=ts value=$date->Timestamp()}
                        {$periods.$ts = $DailyLayout->GetPeriods($date)}
                        {if $periods[$ts]|default:array()|count == 0}{continue}{*dont show if there are no slots*}{/if}
                        {if $date->DateEquals($TodaysDate)}
                            {assign var=tdclass value="today"}
                        {/if}
                        <td class="resdate-custom resdate {$tdclass}" style="width:{$columnWidth}%">
                            {formatdate date=$date key="schedule_daily"}</td>
                    {/foreach}
                </tr>
            </thead>
            <tbody>
                {foreach from=$Resources item=resource name=resource_loop}
                    {assign var=resourceId value=$resource->Id}
                    {assign var=href value="{Pages::RESERVATION}?rid={$resourceId}&sid={$ScheduleId}"}
                    <tr class="slots">
                        <td class="resourcename"
                            {if $resource->HasColor()}style="background-color:{$resource->GetColor()} !important" {/if}>
                            {if $resource->CanAccess}
                                <a href="{$href}" resourceId="{$resourceId}" class="resourceNameSelector"
                                    {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"
                                    {/if}>{$resource->Name}</a>
                            {else}
                                <span resourceId="{$resource->Id}" resourceId="{$resource->Id}" class="resourceNameSelector"
                                    {if $resource->HasColor()}style="color:{$resource->GetTextColor()} !important"
                                    {/if}>{$resource->Name}</span>
                            {/if}
                        </td>
                        {foreach from=$BoundDates item=date}
                            {assign var=ts value=$date->Timestamp()}
                            {$periods.$ts = $DailyLayout->GetPeriods($date, false)}
                            {assign var=count value=$periods[$ts]|default:array()|count}
                            {if $count== 0}{continue}{*dont show if there are no slots*}{/if}
                            {assign var=min value=$periods[$ts][0]->BeginDate()->TimeStamp()}
                            {assign var=max value=$periods[$ts][$count-1]->EndDate()->TimeStamp()}
                            {assign var=resourceId value=$resource->Id}
                            {assign var=href value="{Pages::RESERVATION}?rid={$resourceId}&sid={$ScheduleId}"}
                            {*                        {assign var=slots value=$DailyLayout->GetLayout($date, $resourceId)}*}
                            {*                        {assign var=summary value=$DailyLayout->GetSummary($date, $resourceId)}*}
                            {*                        {if $summary->NumberOfItems() > 0}*}
                                {*                            <td style="vertical-align: top;" class="reservable clickres"*}
                                {*                                ref="{$href}&rd={formatdate date=$date key=url}" data-href="{$href}"*}
                                {*                                data-start="{$date->Format('Y-m-d H:i:s')|escape:url}"*}
                                {*                                data-end="{$date->Format('Y-m-d H:i:s')|escape:url}">*}
                                {*                                <div class="reservable clickres" ref="{$href}&rd={formatdate date=$date key=url}"*}
                                {*                                     data-href="{$href}" data-start="{$date->Format('Y-m-d H:i:s')|escape:url}"*}
                                {*                                     data-end="{$date->Format('Y-m-d H:i:s')|escape:url}">*}
                                {*                                    <i class="fa fa-plus-circle"></i> {translate key=CreateReservation}*}
                                {*                                    <input type="hidden" class="href" value="{$href}"/>*}
                                {*                                </div>*}
                                {*                                {foreach from=$slots item=slot}*}
                                    {*                                    {call name=displaySlotCondensed Slot=$slot Href="$href" AccessAllowed=$resource->CanAccess Periods=$periods.$ts }*}
                                {*                                {/foreach}*}
                                {*                            </td>*}
                            {*                        {else}*}
                                {assign var=href value="{$CreateReservationPage}?rid={$resourceId}&sid={$ScheduleId}&rd={formatdate date=$date key=url}"}
                                <td style="vertical-align: top;" class="" ref="{$href}&rd={formatdate date=$date key=url}"
                                    data-href="{$href}" data-start="{$date->Format('Y-m-d H:i:s')|escape:url}"
                                    data-end="{$date->Format('Y-m-d H:i:s')|escape:url}">

                                    {assign var=addButton value=$TodaysDate->LessThanOrEqual($date) || $TodaysDate->DateEquals($date) || $AllowCreatePastReservationsButton}
                                    {if $addButton}
                                        <div class="reservable clickres" ref="{$href}&rd={formatdate date=$date key=url}"
                                            data-href="{$href}" data-start="{$date->Format('Y-m-d H:i:s')|escape:url}"
                                            data-end="{$date->Format('Y-m-d H:i:s')|escape:url}">
                                            <i class="bi bi-plus-circle-fill"></i> {translate key=CreateReservation}
                                            <input type="hidden" class="href" value="{$href}" />
                                        </div>
                                    {/if}
                                    <div class="reservations" data-min="{$min}" data-max="{$max}" data-resourceid="{$resourceId}"></div>
                                </td>
                            {*                        {/if}*}
                        {/foreach}
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
{/block}

{block name="scripts"}
    <script type="text/javascript">
        $(document).ready(function() {
            var $td = $('td.reserved', $('#reservations'));
            $td.unbind('click');

            $td.click(function(e) {
                e.stopPropagation();
                var date = $(this).attr('date').split('-');
                var year = date[0];
                var month = date[1];
                var day = date[2];
                var resourceId = $(this).attr('resourceId');

                window.location = "{Pages::CALENDAR}?{QueryStringKeys::CALENDAR_TYPE}=day&{QueryStringKeys::RESOURCE_ID}=" + resourceId + "&y=" + year + "&m=" + month + "&d=" + day;
            });
        });
    </script>
{/block}Z
