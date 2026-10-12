<div class="dashboard accordion-item shadow mb-2 availabilityDashboard" id="availabilityDashboard">
    <div class="accordion-header dashboardHeader">
        <button class="accordion-button collapsed link-primary fw-bold" type="button" data-bs-toggle="collapse"
            data-bs-target="#ResourceAvailabilityContents" aria-expanded="false"
            aria-controls="ResourceAvailabilityContents">
            Zarezerwuj salę
        </button>
    </div>

    <div id="ResourceAvailabilityContents" class="dashboardContents accordion-collapse collapse">
        <div class="accordion-body">

            {assign var=allFlatItems value=[]}
            {foreach from=$Schedules item=s}
                {if isset($Available[$s->GetId()]) && is_array($Available[$s->GetId()])}
                    {foreach from=$Available[$s->GetId()] item=i}
                        {$allFlatItems[] = $i}
                    {/foreach}
                {/if}
                {if isset($Unavailable[$s->GetId()]) && is_array($Unavailable[$s->GetId()])}
                    {foreach from=$Unavailable[$s->GetId()] item=i}
                        {$allFlatItems[] = $i}
                    {/foreach}
                {/if}
                {if isset($UnavailableAllDay[$s->GetId()]) && is_array($UnavailableAllDay[$s->GetId()])}
                    {foreach from=$UnavailableAllDay[$s->GetId()] item=i}
                        {$allFlatItems[] = $i}
                    {/foreach}
                {/if}
            {/foreach}

            {if $allFlatItems|count > 0}
                <div class="row g-2">
                    {foreach from=$allFlatItems item=i}
                        <div class="col-12 col-sm-6">
                            <div class="availabilityItem p-2 border rounded align-items-center h-100">
                                <span class="resourceName px-2 py-1 rounded-1 d-block text-truncate"
                                    {if $i->GetColor()}style="background-color:{$i->GetColor()};color:{$i->GetTextColor()};" {/if}>

                                    <i resource-id="{$i->ResourceId()}" class="resourceNameSelector bi bi-info-circle-fill"></i>
                                    <a resource-id="{$i->ResourceId()}" class="resourceNameSelector text-decoration-none"
                                        {if $i->GetColor()}style="color:{$i->GetTextColor()};" {/if}
                                        href="{$Path}{Pages::RESERVATION}?{QueryStringKeys::RESOURCE_ID}={$i->ResourceId()}">
                                        {$i->ResourceName()}
                                    </a>
                                </span>
                            </div>
                        </div>
                    {/foreach}
                </div>
            {else}
                <div class="no-data text-center fst-italic fs-5 py-3">
                    {translate key=None}
                </div>
            {/if}

        </div>
    </div>

