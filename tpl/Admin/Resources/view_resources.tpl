{include file='globalheader.tpl' DataTable=true}

<div id="page-manage-resources" class="admin-page">
    <div class="clearfix border-bottom mb-3">
        <h1 class="float-start">{translate key='ManageResources'}</h1>
    </div>

    <div class="accordion">
        <div class="accordion-item shadow mb-3 panel-default filterTable" id="filter-resources-panel">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed link-primary fw-bold" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filter-resources-content" aria-expanded="false"
                    aria-controls="filter-resources-content">
                    <i class="bi bi-funnel-fill me-1"></i>{translate key="Filter"}
                </button>
            </h2>
            <div id="filter-resources-content" class="accordion-collapse collapse">
                <form id="filterForm" class="horizontal-list" role="form" method="get">
                    <div class="accordion-body">
                        <div class="row gy-2 mb-2">
                            {assign var=groupClass value="col-12 col-sm-4 col-md-3"}

                            <div class="form-group {$groupClass}">
                                <label for="filterResourceName" class="fw-bold">{translate key=Resource}</label>
                                <div class="position-relative">
                                    <input type="text" id="filterResourceName" class="form-control"
                                        {formname key=RESOURCE_NAME} value="{$ResourceNameFilter}"
                                        placeholder="{translate key=Name}" />
                                    <span class="searchclear searchclear-label bi bi-x-circle-fill"
                                        ref="filterResourceName"></span>
                                </div>
                            </div>
                            <div class="form-group {$groupClass}">
                                <label for="filterScheduleId" class="fw-bold">{translate key=Schedule}</label>
                                <select id="filterScheduleId" {formname key=SCHEDULE_ID} class="form-select">
                                    <option value="">{translate key=AllSchedules}</option>
                                    {object_html_options options=$AllSchedules key='GetId' label="GetName" selected=$ScheduleIdFilter}
                                </select>
                            </div>

                            <div class="form-group {$groupClass}">
                                <label for="filterResourceType" class="fw-bold">{translate key=ResourceType}</label>
                                <select id="filterResourceType" class="form-select" {formname key=RESOURCE_TYPE_ID}>
                                    <option value="">{translate key=AllResourceTypes}</option>
                                    {object_html_options options=$ResourceTypes key='Id' label="Name" selected=$ResourceTypeFilter}
                                </select>
                            </div>
                            <div class="form-group {$groupClass}">
                                <label for="resourceStatusIdFilter"
                                    class="fw-bold">{translate key=ResourceStatus}</label>
                                <div class="d-flex flex-wrap">
                                    <select id="resourceStatusIdFilter" class="form-select inline w-auto"
                                        {formname key=RESOURCE_STATUS_ID}>
                                        <option value="">{translate key=AllResourceStatuses}</option>
                                        <option value="{ResourceStatus::AVAILABLE}">{translate key=Available}</option>
                                        <option value="{ResourceStatus::UNAVAILABLE}">{translate key=Unavailable}
                                        </option>
                                        <option value="{ResourceStatus::HIDDEN}">{translate key=Hidden}</option>
                                    </select>
                                    <label for="resourceReasonIdFilter"
                                        class="visually-hidden">{translate key=Reason}</label>
                                    <select id="resourceReasonIdFilter" class="form-select w-auto inline"
                                        {formname key=RESOURCE_STATUS_REASON_ID}>
                                        <option value="">-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group {$groupClass}">
                                <label for="filterCapacity" class="fw-bold">{translate key=MinimumCapacity}</label>
                                <input type="number" min="0" id="filterCapacity" class="form-control"
                                    {formname key=MAX_PARTICIPANTS} value="{$CapacityFilter}"
                                    placeholder="{translate key=MinimumCapacity}" />
                            </div>
                            <div class="form-group {$groupClass}">
                                <label for="filterRequiresApproval"
                                    class="fw-bold">{translate key=ResourceRequiresApproval}</label>
                                <select id="filterRequiresApproval" class="form-select" {formname key=REQUIRES_APPROVAL}
                                    title="{translate key='ResourceRequiresApproval'}">
                                    <option value="">{translate key='ResourceRequiresApproval'}</option>
                                    {html_options options=$YesNoOptions selected=$RequiresApprovalFilter}
                                </select>
                            </div>
                            <div class="form-group {$groupClass}">
                                <label for="filterAutoAssign"
                                    class="fw-bold">{translate key=ResourcePermissionAutoGranted}</label>
                                <select id="filterAutoAssign" class="form-select" {formname key=AUTO_ASSIGN}
                                    title="{translate key='ResourcePermissionAutoGranted'}">
                                    <option value="">{translate key='ResourcePermissionAutoGranted'}</option>
                                    {html_options options=$YesNoOptions selected=$AutoPermissionFilter}
                                </select>
                            </div>
                            <div class="form-group {$groupClass}">
                                <label for="filterAllowMultiDay"
                                    class="fw-bold">{translate key=ResourceAllowMultiDay}</label>
                                <select id="filterAllowMultiDay" class="form-select" {formname key=ALLOW_MULTIDAY}
                                    title="{translate key=ResourceAllowMultiDay}">
                                    <option value="">{translate key=ResourceAllowMultiDay}</option>
                                    {html_options options=$YesNoOptions selected=$AllowMultiDayFilter}
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            {foreach from=$AttributeFilters item=attribute}
                                {control type="AttributeControl" idPrefix="search" attribute=$attribute searchmode=true class="customAttribute filter-customAttribute{$attribute->Id()}
                            {$groupClass}"}
                            {/foreach}
                        </div>

                        <div class="card-footer border-top pt-3">
                            {filter_button id="filter" class="btn-sm"}
                            {reset_button id="clearFilter" class="btn-sm"}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="globalError" class="error d-none"></div>

    <div class="card shadow panel-default admin-panel" id="list-resources-panel">
        <div class="card-body" id="resourceList"> {* this div creates the containter for the accordion. Before class="card-body accordion" *}
            {if !empty($Resources)}
                {assign var=tableId value=resourcesTable}
                <table class="table table-striped table-hover w-100" id="{$tableId}" data-no-accordion="true"> {* Before <table class="table table-borderless w-100" id="{$tableId}" add data no accordion because dom does not show>*}
                    <thead> {* Before: <thead class="d-none">*}
                        <tr>
                            <th>{translate key="Name"}</th>
                            <th>{translate key="ResourceType"}</th>
                            <th>{translate key="Status"}</th>
                            <th>{translate key="Location"}</th>
                            <th>{translate key="Actions"}</th>                        
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$Resources item=resource}
                            {assign var=id value=$resource->GetResourceId()}
                            <tr>
                                <td>
                                    {*1st column: Resource name and color*}
                                    <span class="badge"  style="background-color:{if $resource->HasColor()}}{$resource->GetColor()}{else}#e0e0e0{/if}">
                                        {$resource->GetName()}
                                    </span>
                                </td>
                                <td>
                                    {*2nd column: Resource type*}
                                    {if $resource->HasResourceType()}
                                        {$ResourceTypes[$resource->GetResourceTypeID()]->Name()}
                                    {else}
                                        <span>-</span>
                                    {/if}
                                </td>
                                <td>
                                    {*3nd column: Resource Status with color badges*}
                                    {if $resource->IsAvailable()}
                                        <span class="badge bg-success">{translate key='Available'}</span>
                                    {elseif $resource->IsUnavailable()}
                                        <span class="badge bg-warning">{translate key='Unavailable'}</span>
                                    {else}
                                        <span class="badge bg-danger">{translate key='Hidden'}</span>
                                    {/if}
                                </td>
                                <td>
                                    {*4th column: Resource Location*}
                                    {if $resource->HasLocation()}
                                        {$resource->GetLocation()}
                                    {else}
                                        <span>-</span>
                                    {/if}
                                </td>
                                <td>
                                    {*5th column: Actions*}
                                    <a href="resource_detail.php?id={$resource->GetResourceId()}" class="btn btn-sm btn-primary"> {*Button to the individual page: coming soon, for now it is disabled*}
                                        <i class="bi bi-eye"></i> {translate key="ViewDetails"}
                                    </a>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {else}
                <h3 class="text-center">{translate key='NoResourcesToView'}</h3>
            {/if}
        </div>
    </div>

    {csrf_token}


    {include file="javascript-includes.tpl" DataTable=true}
    {datatable tableId=$tableId}
    {jsfile src="search-clear.js"}
</div>

{include file='globalfooter.tpl'}