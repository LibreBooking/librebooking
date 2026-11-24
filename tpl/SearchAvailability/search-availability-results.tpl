{if $Openings|default:array()|count == 0}
    <div class="card bg-warning-subtle shadow">
        <div class="card-body d-flex justify-content-center align-items-center py-4">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            {translate key=NoAvailableMatchingTimes}
        </div>
    </div>
{else}
    <div class="card shadow">
        <div class="card-body py-4">
            {assign var=groupedOpenings value=[]}
            {foreach from=$Openings item=opening}
                {$resourceId = $opening->Resource()->Id}
                {capture assign=dateKey}{format_date date=$opening->Start() format='Y-m-d'}{/capture}

                {if !isset($groupedOpenings[$resourceId])}
                    {$groupedOpenings[$resourceId] = []}
                {/if}
                {if !isset($groupedOpenings[$resourceId][$dateKey])}
                    {$groupedOpenings[$resourceId][$dateKey] = []}
                {/if}
                {$groupedOpenings[$resourceId][$dateKey][] = $opening}
            {/foreach}

            <ul class="list-group list-group-flush">
                {foreach from=$groupedOpenings key=resourceId item=days}
                    {foreach from=$days item=firstDay}{$resource = $firstDay[0]->Resource()}{break}{/foreach}
                    <li class="list-group-item py-3">
                        <div class="mb-3">
                            <span class="resourceName badge rounded-pill fs-6" role="heading" aria-level="2"
                                data-resourceId="{$resource->Id}" {if $resource->HasColor()}
                                style="background-color: {$resource->Color};color:{$resource->TextColor};" {/if}>
                                <i class="bi bi-info-circle-fill pe-1"></i>{$resource->Name}
                            </span>
                        </div>

                        {foreach from=$days item=dayOpenings}
                            <div class="row g-2 align-items-center{if !$dayOpenings@last} mb-2 border-bottom pb-2{/if}">
                                <div class="col-12 col-md-2 col-lg-2 fw-semibold">
                                    {format_date date=$dayOpenings[0]->Start() key=schedule_daily}
                                </div>
                                <div class="col-12 col-md-10 col-lg-10">
                                    <div class="d-flex flex-wrap gap-2">
                                        {foreach from=$dayOpenings item=opening}
                                            {assign var=key value=short_reservation_date}
                                            {if $opening->SameDate()}
                                                {assign var=key value=period_time}
                                            {/if}

                                            <span
                                                class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle fs-6 opening"
                                                data-resourceid="{$resource->Id}"
                                                data-startdate="{format_date date=$opening->Start() key=system_datetime}"
                                                data-enddate="{format_date date=$opening->End() key=system_datetime}">
                                                {format_date date=$opening->Start() key=period_time} -
                                                {format_date date=$opening->End() key=$key}
                                            </span>
                                        {/foreach}
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}
