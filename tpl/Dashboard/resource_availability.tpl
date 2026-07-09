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

            {* Section settings *}
            {assign var=sections value=[
                [
                    'title' => 'Available',
                    'data' => $Available,
                    'dateField' => 'NextTime',
                    'label' => 'AvailableUntil',
                    'urlDate' => true
                ],
                [
                    'title' => 'Unavailable',
                    'data' => $Unavailable,
                    'dateField' => 'ReservationEnds',
                    'label' => 'AvailableBeginningAt',
                    'urlDate' => false
                ],
                [
                    'title' => 'UnavailableAllDay',
                    'data' => $UnavailableAllDay,
                    'dateField' => 'ReservationEnds',
                    'label' => 'AvailableAt',
                    'urlDate' => false
                ]
            ]}

            {* Main loop *}
            {foreach from=$sections item=section}

                <div class="header fw-bold fs-5 mt-3">
                    {translate key=$section.title}
                </div>

                {assign var=hasData value=false}

                {foreach from=$Schedules item=s}
                    {assign var=availability value=$section.data[$s->GetId()]}

                    {if is_array($availability) && $availability|count > 0}
                        {assign var=hasData value=true}

                        <div class="text-body-secondary fs-4 mt-3 text-center fw-bold">
                            {$s->GetName()}
                        </div>

                        {foreach from=$availability item=i}
                            {if $section.dateField == 'NextTime'}
                                {assign var=date value=$i->NextTime()}
                            {else}
                                {assign var=date value=$i->ReservationEnds()}
                            {/if}

                            {* Determine date to display considering schedule availability *}
                            {assign var=dateToDisplay value=$date}
                            {if $s->HasAvailability()}
                                {assign var=scheduleBegin value=$s->GetAvailabilityBegin()}
                                {if $date == null || $date->LessThan($scheduleBegin)}
                                    {assign var=dateToDisplay value=$scheduleBegin}
                                {/if}
                            {/if}

                            {assign var=reservationHref value="{$Path}{Pages::RESERVATION}?{QueryStringKeys::RESOURCE_ID}={$i->ResourceId()|escape:'url'}"}
                            {if $section.urlDate && $dateToDisplay}
                                {assign var=formattedUrlDate value={formatdate date=$dateToDisplay timezone=$Timezone format='Y-m-d'}}
                                {assign var=reservationHref value="{$reservationHref}&{QueryStringKeys::START_DATE}={$formattedUrlDate|escape:'url'}&{QueryStringKeys::END_DATE}={$formattedUrlDate|escape:'url'}"}
                            {/if}

                            <div class="availabilityItem row gy-2 p-2 border-bottom align-items-center">

                                {* Resource name *}
                                <div class="col-12 col-sm-5">
                                    <span class="resourceName px-2 py-1 rounded-1"
                                        {if $i->GetColor()}style="background-color:{$i->GetColor()};color:{$i->GetTextColor()};" {/if}>

                                        <i resource-id="{$i->ResourceId()}" class="resourceNameSelector bi bi-info-circle-fill"></i>
                                        <a resource-id="{$i->ResourceId()}" class="resourceNameSelector"
                                            {if $i->GetColor()}style="color:{$i->GetTextColor()};" {/if}
                                            href="{$reservationHref}">
                                            {$i->ResourceName()}
                                        </a>
                                    </span>
                                </div>

                                {* Availability *}
                                <div class="availability col-12 col-sm-4">
                                    {if $date != null}
                                        {translate key=$section.label}
                                        {format_date date=$date timezone=$Timezone key=dashboard}
                                    {else}
                                        <span class="no-data fst-italic">
                                            {translate key=AllNoUpcomingReservations args=30}
                                        </span>
                                    {/if}
                                </div>

                                {* Button *}
                                <div class="reserveButton col-12 col-sm-3 d-grid">
                                    <button class="btn btn-sm btn-primary"
                                        onclick="window.location.href='{$reservationHref}'">
                                        {translate key=Reserve}
                                    </button>
                                </div>

                            </div>
                        {/foreach}
                    {/if}
                {/foreach}

                {* No data *}
                {if !$hasData}
                    <div class="no-data text-center fst-italic fs-5">
                        {translate key=None}
                    </div>
                {/if}

            {/foreach}

        </div>
    </div>
</div>
