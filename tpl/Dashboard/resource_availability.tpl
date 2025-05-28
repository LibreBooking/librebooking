<div class="dashboard accordion-item shadow mb-2 availabilityDashboard" id="availabilityDashboard">
    <div class="accordion-header dashboardHeader">
        <button class="accordion-button collapsed link-primary fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#ResourceAvailabilityContents" aria-expanded="false"
            aria-controls="ResourceAvailabilityContents">
            {translate key="ResourceAvailability"}
        </button>
    </div>
    <div id="ResourceAvailabilityContents" class="dashboardContents accordion-collapse collapse">
        <div class="accordion-body">
            <div class="header fw-bold fs-5">{translate key=Available}</div>
            {foreach from=$Schedules item=s}
                {assign var=availability value=$Available[$s->GetId()]}
                {if is_array($availability) && $availability|default:array()|count > 0}
                    <div class="text-body-secondary fs-4 mt-3 text-center fw-bold">{$s->GetName()}</div>
                    {foreach from=$availability item=i}
                        <div class="availabilityItem row gy-2 p-2 border-bottom align-items-center">
                            <div class="col-12 col-sm-5">
                                <span class="resourceName px-2 py-1 rounded-1"
                                    {if $i->GetColor()}style="background-color:{$i->GetColor()};color:{$i->GetTextColor()};" {/if}>
                                    <i resource-id="{$i->ResourceId()}" class="resourceNameSelector bi bi-info-circle-fill"></i>
                                    <a href="{$Path}{Pages::RESERVATION}?{QueryStringKeys::RESOURCE_ID}={$i->ResourceId()}"
                                        resource-id="{$i->ResourceId()}" class="resourceNameSelector" {if $i->GetColor()}
                                        style="color:{$i->GetTextColor()} {/if}">{$i->ResourceName()}</a>
                                </span>
                            </div>
                            <div class="availability col-12 col-sm-4">
                                {if $i->NextTime() != null}
                                    {translate key=AvailableUntil}
                                    {format_date date=$i->NextTime() timezone=$Timezone key=dashboard}
                                {else}
                                    <span class="no-data fst-italic">{translate key=AllNoUpcomingReservations args=30}</span>
                                {/if}
                            </div>
                            <div class="reserveButton col-12 col-sm-3 d-grid">
                                <button class="btn btn-sm btn-primary"
                                    onclick="window.location.href='{$Path}{Pages::RESERVATION}?{QueryStringKeys::RESOURCE_ID}={$i->ResourceId()}'">
                                    {translate key=Reserve}
                                </button>
                            </div>
                        </div>
                    {/foreach}
                {/if}
            {/foreach}

            {if empty($availability)}
                <div class="no-data text-center fst-italic fs-5">{translate key=None}</div>
            {/if}

            <div class="header fw-bold fs-5 mt-3">{translate key=Unavailable}</div>

            {foreach from=$Schedules item=s}
                {assign var=availability value=$Unavailable[$s->GetId()]}
                {if is_array($availability) && $availability|default:array()|count > 0}
                    <div class="text-body-secondary fs-4 mt-3 text-center fw-bold">{$s->GetName()}</div>
                    {foreach from=$availability item=i}
                        <div class="availabilityItem row py-2 border-bottom align-items-center">
                            <div class="col-12 col-sm-5">
                                <span class="resourceName px-2 rounded-1 {if !$i->GetColor()}bg-success bg-opacity-10{/if}"
                                    {if $i->GetColor()}style="background-color:{$i->GetColor()};color:{$i->GetTextColor()};" {/if}>
                                    <i resource-id="{$i->ResourceId()}" class="resourceNameSelector bi bi-info-circle-fill
                                    {if !$i->GetColor()}link-success{/if}"></i>
                                    <a href="{$Path}{Pages::RESERVATION}?{QueryStringKeys::RESOURCE_ID}={$i->ResourceId()}"
                                        resource-id="{$i->ResourceId()}"
                                        class="resourceNameSelector
                                    {if !$i->GetColor()}link-success link-underline-opacity-0 link-underline-opacity-100-hover{/if}"
                                        style="color:{$i->GetTextColor()}">{$i->ResourceName()}</a>
                                </span>
                            </div>
                            <div class="availability col-12 col-sm-4">
                                {translate key=AvailableBeginningAt}
                                {format_date date=$i->ReservationEnds() timezone=$Timezone key=dashboard}
                            </div>
                            <div class="reserveButton col-12 col-sm-3 d-grid gap-2">
                                <button class="btn btn-sm btn-primary"
                                    onclick="window.location.href='{$Path}{Pages::RESERVATION}?{QueryStringKeys::RESOURCE_ID}={$i->ResourceId()}&{QueryStringKeys::START_DATE}={format_date date=$i->ReservationEnds() timezone=$Timezone key=url_full}'">
                                    {translate key=Reserve}
                                </button>
                            </div>
                        </div>
                    {/foreach}
                {/if}
            {/foreach}

            {if empty($Unavailable)}
                <div class="no-data text-center fst-italic fs-5">{translate key=None}</div>
            {/if}

            <div class="header fw-bold fs-5 mt-3">{translate key=UnavailableAllDay}</div>
            {foreach from=$Schedules item=s}
                {assign var=availability value=$UnavailableAllDay[$s->GetId()]}
                {if is_array($availability) && $availability|default:array()|count > 0}
                    <div class="text-body-secondary fs-4 mt-3 text-center fw-bold">{$s->GetName()}</div>
                    {foreach from=$availability item=i}
                        <div class="availabilityItem row py-2 border-bottom align-items-center">
                            <div class="col-12 col-sm-5">
                                <span class="resourceName px-2 rounded-1 {if !$i->GetColor()}bg-success bg-opacity-10{/if}"><i
                                        resource-id="{$i->ResourceId()}" class="resourceNameSelector bi bi-info-circle-fill
                                        {if !$i->GetColor()}link-success{/if}"></i>
                                    <a href="{$Path}{Pages::RESERVATION}?{QueryStringKeys::RESOURCE_ID}={$i->ResourceId()}"
                                        resource-id="{$i->ResourceId()}"
                                        class="resourceNameSelector
                                    {if !$i->GetColor()}link-success link-underline-opacity-0 link-underline-opacity-100-hover{/if}"
                                        style="color:{$i->GetTextColor()}">{$i->ResourceName()}</a>
                                </span>
                            </div>
                            <div class="availability col-12 col-sm-4">
                                {translate key=AvailableAt}
                                {format_date date=$i->ReservationEnds() timezone=$Timezone key=dashboard}
                            </div>
                            <div class="reserveButton col-12 col-sm-3 d-grid gap-2">
                                <button class="btn btn-sm btn-primary"
                                    onclick="window.location.href='{{$Path}}{{Pages::RESERVATION}}?{{QueryStringKeys::RESOURCE_ID}}={{$i->ResourceId()}}&{{QueryStringKeys::START_DATE}}={{format_date date=$i->ReservationEnds() timezone=$Timezone key=url_full}}'">
                                    {{translate key=Reserve}}
                                </button>
                            </div>
                        </div>
                    {/foreach}
                {/if}
            {/foreach}

            {if empty($UnavailableAllDay)}
                <div class="no-data text-center fst-italic fs-5">{translate key=None}</div>
            {/if}
        </div>
    </div>
</div>