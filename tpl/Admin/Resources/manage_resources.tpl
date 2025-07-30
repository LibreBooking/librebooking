{include file='globalheader.tpl' InlineEdit=true DataTable=true Trumbowyg=true}

<div id="page-manage-resources" class="admin-page">
	<div class="clearfix border-bottom mb-3">
		<div class="dropdown admin-header-more float-end">
			<div class="btn-group btn-group-sm" role="group">
				<button href="#" role="button" class="add-link add-resource btn btn-primary">
					<span class="bi bi-plus-circle-fill icon add"></span>
					{translate key="AddResource"}
				</button>
				<button class="btn btn-primary dropdown-toggle" type="button" id="moreResourceActions"
					data-bs-toggle="dropdown">
					{translate key="MoreResourceActions"}
				</button>
				<ul class="dropdown-menu" role="menu" aria-labelledby="moreResourceActions">

					<li role="presentation">
						<a role="menuitem" href="{$Path}admin/manage_resource_groups.php"
							class="dropdown-item">{translate key="ManageResourceGroups"}</a>
					</li>
					<li role="presentation">
						<a role="menuitem" href="{$Path}admin/manage_resource_types.php"
							class="dropdown-item">{translate key="ManageResourceTypes"}</a>
					</li>
					<li role="presentation">
						<a role="menuitem" href="{$Path}admin/manage_resource_status.php"
							class="dropdown-item">{translate key="ManageResourceStatus"}</a>
					</li>
					<li>
						<hr class="dropdown-divider">
					</li>
					<li role="presentation">
						<a role="menuitem" href="#" class="import-resources dropdown-item" id="import-resources">
							<i class="bi bi-box-arrow-in-down"></i>
							{translate key="ImportResources"}
						</a>
					</li>
					<li role="presentation">
						<a role="menuitem" href="{$ExportUrl}" download="{$ExportUrl}"
							class="export-resources dropdown-item" id="export-resources" target="_blank">
							<i class="bi bi-box-arrow-in-up"></i>
							{translate key="ExportResources"}
						</a>
					</li>
					<li>
						<hr class="dropdown-divider">
					</li>
					{if !empty($Resources)}
						<li role="presentation">
							<a role="menuitem" href="#" class="dropdown-item"
								id="bulkUpdatePromptButton">{translate key=BulkResourceUpdate}</a>
						</li>
					{/if}
					{if !empty($Resources)}
						<li role="presentation">
							<a role="menuitem" href="#" class="dropdown-item"
								id="bulkDeletePromptButton">{translate key=BulkResourceDelete}</a>
						</li>
					{/if}
				</ul>
			</div>
		</div>

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
									<input id="filterResourceName" class="form-control" {formname key=RESOURCE_NAME}
										value="{$ResourceNameFilter}" placeholder="{translate key=Name}" />
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
								<div class="position-relative">
									<input type="number" min="0" id="filterCapacity" class="form-control"
										{formname key=MAX_PARTICIPANTS} value="{$CapacityFilter}"
										placeholder="{translate key=MinimumCapacity}" />
									<span class="searchclear searchclear-label bi bi-x-circle-fill me-4"
										ref="filterCapacity"></span>
								</div>
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
		<div class="no-padding card-body accordion" id="resourceList">
			{assign var=tableId value=resourcesTable}
			<table class="table table-borderless w-100" id="{$tableId}">
				<thead class="d-none">
					<tr>
						<th>{translate key="Resources"}</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$Resources item=resource}
						{assign var=id value=$resource->GetResourceId()}
						<tr>
							<td>
								<div class="resourceItem accordion-item shadow mb-2">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#id{$resource->GetResourceId()}" aria-expanded="false"
											aria-controls="id{$resource->GetResourceId()}">
											{$resource->GetName()}
										</button>
									</h2>

									<div id="id{$id}" class="accordion-collapse collapse">
										<div class="accordion-body resourceDetails" data-resourceId="{$id}">
											<input type="hidden" class="id" value="{$id}" />
											<div class="row">
												<div class="col-12 col-sm-2 p-2">
													<div class="resourceImage">
														{if $resource->HasImage()}
															<div id="resourceImageCarousel{$id}" class="carousel slide">
																<div
																	class="carousel-inner h-100 ratio ratio-1x1 border rounded-3 shadow-sm">
																	<div class="carousel-item active">
																		<img src="{resource_image image=$resource->GetImage()}"
																			alt="{$resource->GetName()}"
																			class="rounded d-block w-100 h-100 object-fit-cover" />
																	</div>
																	{foreach from=$resource->GetImages() item=image}
																		<div class="carousel-item">
																			<img src="{resource_image image=$image}"
																				alt="{$resource->GetName()}"
																				class="rounded d-block w-100 h-100 object-fit-cover" />
																		</div>
																	{/foreach}
																</div>
																<div class="carousel-indicators mb-0">
																	{if $resource->GetImages()|count > 0}
																		{assign var=slide value=1}
																		<button type="button"
																			data-bs-target="#resourceImageCarousel{$id}"
																			data-bs-slide-to="0" class="active"></button>
																		{foreach from=$resource->GetImages() item=image}
																			<button type="button"
																				data-bs-target="#resourceImageCarousel{$id}"
																				data-bs-slide-to="{$slide}"></button>
																			{assign var=slide value=$slide+1}
																		{/foreach}
																	{/if}

																</div>
															</div>
														{else}
															<div class="noImageContainer text-center">
																<div
																	class="noImage d-flex align-items-center justify-content-center w-100 bg-light border rounded-3 mx-auto">
																	<span class="bi bi-image fs-1"></span>
																</div>
															</div>
														{/if}
														<div class="edit-image d-inline-block rounded p-2 bg-body"
															style="position: relative; top: -36px; left:1px; width: 35px; height: 35px;">
															<a href="#" class="update imageButton link-primary "
																title="{translate key='Change'}"><span
																	class="bi bi-pencil-square"></span></a>
														</div>
													</div>
													<div class="text-center">
														<div>{translate key=ResourceColor}</div>
														<input type="color"
															class="resourceColorPicker w-50 mx-auto border form-control form-control-color"
															value='{if $resource->HasColor()}{$resource->GetColor()}{else}#ffffff{/if}'
															alt="{translate key=ResourceColor}"
															title="{translate key=ResourceColor}" />
														<div>
															<a href="#" class="update clearColor link-danger"
																title="{translate key=Remove}"><i
																	class="bi bi-trash3-fill icon delete"></i></a>
														</div>
													</div>
												</div>
												<div class="col-12 col-sm-10">
													<div class="row row-cols-auto row-cols-sm-3 ">
														<div class="col d-block">
															<div>
																<div class="resourceNameField title fs-5 fw-bold me-1"
																	data-type="text" data-pk="{$id}"
																	data-name="{FormKeys::RESOURCE_NAME}">
																	{$resource->GetName()}</div>
																<div>
																	<a class="update renameButton link-primary me-1"
																		href="#" title="{translate key='Rename'}">
																		<span
																			class="visually-hidden">{translate key=Rename}</span>
																		<i class="bi bi-pencil-square me-1"></i></a>
																	<div class="vr me-1"></div>
																	<a class="update copyButton link-primary me-1" href="#"
																		title="{translate key='Copy'}">
																		<span
																			class="visually-hidden">{translate key=Copy}</span>
																		<i class="bi bi-copy"></i></a>
																	<div class="vr me-1"></div>
																	<a class="update deleteButton link-danger me-1" href="#"
																		title="{translate key='Delete'}">
																		<span
																			class="visually-hidden">{translate key=Delete}</span>
																		<i class="bi bi-trash3-fill icon delete"></i>
																	</a>
																</div>
															</div>
															<div>
																<label class="inline fw-bold">ResourceId:</label>
																<span>{$id}</span>
															</div>
															<div>
																<label
																	class="inline fw-bold">{translate key='Status'}</label>
																{if $resource->IsAvailable()}
																	<a class="update changeStatus link-primary" href="#"
																		data-popover-content="#statusDialog">{translate key='Available'}</a>
																	<i class="bi bi-check-circle-fill text-success"></i>
																{elseif $resource->IsUnavailable()}
																	<a class="update changeStatus link-primary" href="#"
																		data-popover-content="#statusDialog">{translate key='Unavailable'}</a>
																	<i class="bi bi-exclamation-circle-fill text-warning"></i>
																{else}
																	<a class="update changeStatus link-primary" href="#"
																		data-popover-content="#statusDialog">{translate key='Hidden'}</a>
																	<i class="bi bi-x-circle-fill text-danger"></i>
																{/if}
																{if array_key_exists($resource->GetStatusReasonId(),$StatusReasons)}
																	<span
																		class="statusReason">{$StatusReasons[$resource->GetStatusReasonId()]->Description()}</span>
																{/if}
															</div>
															<div>
																<label
																	class="inline fw-bold">{translate key='Schedule'}</label>
																<span class="propertyValue scheduleName" data-type="select"
																	data-pk="{$id}"
																	data-value="{$resource->GetScheduleId()}"
																	data-name="{FormKeys::SCHEDULE_ID}">{$Schedules[$resource->GetScheduleId()]}</span>
																<a class="update changeScheduleButton link-primary"
																	title="{translate key='Edit'}" href="#"><span
																		class="bi bi-pencil-square"></span></a>
															</div>
															<div>
																<label
																	class="inline fw-bold">{translate key='ResourceType'}</label>
																<span class="propertyValue resourceTypeName"
																	data-type="select" data-pk="{$id}"
																	data-value="{$resource->GetResourceTypeId()}"
																	data-name="{FormKeys::RESOURCE_TYPE_ID}">
																	{if $resource->HasResourceType()}
																		{$ResourceTypes[$resource->GetResourceTypeId()]->Name()}
																	{else}
																		{translate key='NoResourceTypeLabel'}
																	{/if}
																</span>
																<a class="update changeResourceType link-primary"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=ResourceType}</span>
																	<span class="bi bi-pencil-square"></span>
																</a>
															</div>
															<div>
																<label
																	class="inline fw-bold">{translate key=SortOrder}</label>
																<span class="propertyValue sortOrderValue"
																	data-type="number" data-pk="{$id}"
																	data-name="{FormKeys::RESOURCE_SORT_ORDER}">
																	{$resource->GetSortOrder()|default:"0"}
																</span>
																<a class="update changeSortOrder link-primary"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=SortOrder}</span>
																	<span class="bi bi-pencil-square"></span>
																</a>
															</div>
															<div class="mt-2">
																<label
																	class="inline fw-bold">{translate key='Location'}</label>
																<span class="propertyValue locationValue" data-type="text"
																	data-pk="{$id}" data-value="{$resource->GetLocation()}"
																	data-name="{FormKeys::RESOURCE_LOCATION}">
																	{if $resource->HasLocation()}
																		{$resource->GetLocation()}
																	{else}
																		{translate key='NoLocationLabel'}
																	{/if}
																</span>
																<a class="update changeLocation link-primary"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=Location}</span>
																	<span class="bi bi-pencil-square"></span>
																</a>
															</div>
															<div>
																<label
																	class="inline fw-bold">{translate key='Contact'}</label>
																{if $ResourceContactIsUser}
																	<span class="propertyValue contactValue" data-type="select"
																{else}
																	<span class="propertyValue contactValue" data-type="text"
																{/if}
																	data-pk="{$id}" data-value="{$resource->GetContact()}"
																	data-name="{FormKeys::RESOURCE_CONTACT}">
																	{if $resource->HasContact()}
																		{$resource->GetContact()}
																	{else}
																		{translate key='NoContactLabel'}
																	{/if}
																</span>
																<a class="update changeContact link-primary"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=Contact}</span>
																	<span class="bi bi-pencil-square"></span></a>
															</div>
															<div>
																<label
																	class="inline fw-bold">{translate key='Description'}</label>
																<a class="update changeDescription link-primary"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=Description}</span>
																	<span class="bi bi-pencil-square"></span></a>
																{if $resource->HasDescription()}
																	{assign var=description value=$resource->GetDescription()}
																{else}
																	{assign var=description value=''}
																{/if}
																{strip}
																	<div class="descriptionValue" data-type="trumbowyg"
																		data-pk="{$id}" data-value="{$description}"
																		data-name="{FormKeys::RESOURCE_DESCRIPTION}">
																		{if $resource->HasDescription()}
																			{$description|unescape:'html'}
																		{else}
																			{translate key='NoDescriptionLabel'}
																		{/if}
																	</div>
																{/strip}
															</div>
															<div>
																<label
																	class="inline fw-bold">{translate key='Notes'}</label>
																<a class="update changeNotes link-primary"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=Notes}</span>
																	<span class="bi bi-pencil-square"></span>
																</a>
																{if $resource->HasNotes()}
																	{assign var=notes value=$resource->GetNotes()}
																{else}
																	{assign var=notes value=''}
																{/if}
																{strip}
																	<div class="notesValue" data-type="trumbowyg"
																		data-pk="{$id}" data-value="{$notes}"
																		data-name="{FormKeys::RESOURCE_NOTES}">
																		{if $resource->HasNotes()}
																			{$notes|unescape:'html'}
																		{else}
																			{translate key='NoNotesLabel'}
																		{/if}
																	</div>
																{/strip}
															</div>
														</div>
														<div class="col">
															<div class="">
																<span class="fs-6 fw-bold">{translate key=Access}</span>
																<a class="inline update changeAccess link-primary"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=Access}</span>
																	<span class="bi bi-pencil-square"></span>
																</a>

																<div class="accessPlaceHolder">
																	{include file="Admin/Resources/manage_resources_access.tpl" resource=$resource}
																</div>
															</div>
															<div class="mt-2">
																<span
																	class="fs-6 fw-bold">{translate key='ResourceAdministrator'}</span>
																{if $AdminGroups|default:array()|count > 0}
																	<a class="update changeResourceAdmin link-primary"
																		title="{translate key='Edit'}" href="#">
																		<span
																			class="visually-hidden">{translate key=ResourceAdministrator}</span>
																		<span class="bi bi-pencil-square"></span></a>
																{/if}
																<div>
																	<span class="propertyValue resourceAdminValue"
																		data-type="select" data-pk="{$id}"
																		data-value="{$resource->GetAdminGroupId()}"
																		data-name="{FormKeys::RESOURCE_ADMIN_GROUP_ID}">{$GroupLookup[$resource->GetAdminGroupId()]->Name}</span>
																</div>
															</div>
															<div class="mt-2">
																<span
																	class="fs-6 fw-bold">{translate key='Permissions'}</span>
																<div>
																	<a href="#"
																		class="update changeUserPermission link-primary">{translate key=Users}</a>
																	<div class="vr mx-1"></div>
																	<a href="#"
																		class="update changeGroupPermissions link-primary">{translate key=Groups}</a>
																</div>
															</div>
														</div>
														<div class="col">
															<div class="">
																<span class="fs-6 fw-bold">{translate key=Duration}</span>
																<a class="inline update changeDuration link-primary"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=Duration}</span>
																	<span class="bi bi-pencil-square"></span>
																</a>

																<div class="durationPlaceHolder">
																	{include file="Admin/Resources/manage_resources_duration.tpl" resource=$resource}
																</div>
															</div>

															{if $CreditsEnabled}
																<div class="mt-2">
																	<span class="fs-6 fw-bold">{translate key='Credits'}</span>
																	<a class="inline update changeCredits link-primary"
																		title="{translate key='Edit'}" href="#">
																		<span
																			class="visually-hidden">{translate key=Credits}</span>
																		<span class="bi bi-pencil-square"></span>
																	</a>
																	<div class="creditsPlaceHolder">
																		{include file="Admin/Resources/manage_resources_credits.tpl" resource=$resource}
																	</div>
																</div>
															{/if}

															<div class="mt-2">
																<span class="fs-6 fw-bold">{translate key='Capacity'}</span>
																<a class="link-primary update changeCapacity"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=Capacity}</span>
																	<span class="bi bi-pencil-square"></span>
																</a>
																<div class="capacityPlaceHolder">
																	{include file="Admin/Resources/manage_resources_capacity.tpl" resource=$resource}
																</div>
															</div>
															<div class="mt-2">
																<span
																	class="fs-6 fw-bold">{translate key='ResourceGroups'}</span>
																<a class="link-primary update changeResourceGroups"
																	title="{translate key='Edit'}" href="#">
																	<span
																		class="visually-hidden">{translate key=ResourceGroups}</span>
																	<span class="bi bi-pencil-square"></span>
																</a>

																<div class="resourceGroupsPlaceHolder">
																	{include file="Admin/Resources/manage_resources_groups.tpl" resource=$resource}
																</div>
															</div>
														</div>
													</div>
													<hr class="hr" />
													{if $AttributeList|default:array()|count > 0}
														{assign var="hasResults" value=false}
														{foreach from=$AttributeList item=attribute name=attrLoop}
															{if $attribute->AppliesToEntity($id)}
																{if !$hasResults}
																	{assign var="hasResults" value=true}
																	{* Content at the start of the iteration *}
																	<div class="mb-4">
																		<span class="fs-6 fw-bold">{translate key='CustomAttributes'}</span>
																		<a id="customAttributesControl" class="link-primary" href="#"
																			data-bs-toggle="collapse"
																			data-bs-target="#customAttributes{$id}">
																			<i id="customAttributesIcon{$id}"
																				class="bi bi-chevron-down"></i>
																		</a>
																		<div id="customAttributes{$id}" class="collapse show">
																			<div class="row">
																			{/if}
																			{include file='Admin/InlineAttributeEdit.tpl' id=$id attribute=$attribute value=$resource->GetAttributeValue($attribute->Id())}
																		{/if}
																	{/foreach}
																	{if $hasResults}
																		{* Content at the end of the iteration *}
																	</div>
																</div>
															</div>
														{/if}
													{/if}
													<div class="">
														<div class="fs-6 fw-bold me-2">{translate key='Public'}</div>
														<div class="publicSettingsPlaceHolder">
															{include file="Admin/Resources/manage_resources_public.tpl" resource=$resource}
														</div>
													</div>
													<div class="">
														<a href="{$smarty.server.SCRIPT_NAME}?action={ManageResourcesActions::ActionPrintQR}&rid={$id}"
															target="_blank" class="link-primary">{translate key=PrintQRCode}
															<i class="bi bi-qr-code"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>

	<div id="add-resource-dialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="addResourceModalLabel"
		aria-hidden="true">
		<form id="addResourceForm" class="form" role="form" method="post"
			ajaxAction="{ManageResourcesActions::ActionAdd}" enctype="multipart/form-data">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addResourceModalLabel">{translate key=AddNewResource}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div id="addResourceResults" class="alert alert-danger no-show"></div>

						<div class="form-group mb-2">
							<label class="fw-bold" for="resourceName">{translate key='Name'}<i
									class="bi bi-asterisk text-danger align-top" style="font-size: 0.5rem;"></i>
							</label>
							<input type="text" class="form-control required has-feedback " maxlength="85"
								id="resourceName" {formname key=RESOURCE_NAME} />

						</div>
						<div class="form-group mb-2">
							<label class="fw-bold" for="scheduleId">{translate key='Schedule'}</label>
							<select class="form-select" {formname key=SCHEDULE_ID} id="scheduleId">
								{foreach from=$Schedules item=scheduleName key=scheduleId}
									<option value="{$scheduleId}">{$scheduleName}</option>
								{/foreach}
							</select>
						</div>
						<div class="form-group mb-2">
							<label class="fw-bold" for="permissions">{translate key='ResourcePermissions'}</label>
							<select class="form-select" {formname key=AUTO_ASSIGN} id="permissions">
								<option value="0">{translate key="ResourcePermissionNotAutoGranted"}</option>
								<option value="1">{translate key="ResourcePermissionAutoGranted"}</option>
							</select>
						</div>
						<div class="form-group mb-2">
							<label class="fw-bold"
								for="resourceAdminGroupId">{translate key='ResourceAdministrator'}</label>
							<select class="form-select" {formname key=RESOURCE_ADMIN_GROUP_ID}
								id="resourceAdminGroupId">
								{if $CanViewAdmin}
								<option value="">{translate key=None}</option>{/if}
								{foreach from=$AdminGroups item=adminGroup}
									<option value="{$adminGroup->Id}">{$adminGroup->Name}</option>
								{/foreach}
							</select>
						</div>
						<label class="fw-bold mb-2" for="resourceImageAdd">{translate key=Image}</label>
						<div class="dropzone text-center border border-2 rounded-3 bg-light" id="addResourceImage">
							<div>
								<i class="bi bi-image fs-1"></i><br />
								{translate key=ChooseOrDropFile}
							</div>
							<input id="resourceImageAdd" type="file" {formname key=RESOURCE_IMAGE}
								accept="image/*;capture=camera" />
						</div>
						<div class="note fst-italic">.gif, .jpg, or .png</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{add_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<input type="hidden" id="activeId" value="" />

	<div id="imageDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
		aria-hidden="true">
		<form id="imageForm" method="post" enctype="multipart/form-data"
			ajaxAction="{ManageResourcesActions::ActionChangeImage}">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="imageModalLabel">{translate key=ResourceImages}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="clearfix">
							<div class="row " id="resource-images">

							</div>
						</div>

						<label for="resourceImage" class="visually-hidden">{translate key=Image}</label>
						<div class="dropzone text-center border border-2 rounded-3 bg-light" id="changeResourceImage">
							<div class="dropzone-empty">
								<i class="bi bi-image fs-1"></i><br />
								{translate key=ChooseOrDropFile}
							</div>
							<div class="dropzone-preview"></div>
							<input id="resourceImage" type="file" {formname key=RESOURCE_IMAGE}
								accept="image/*;capture=camera" />
						</div>

						<div class="note fst-italic">.gif, .jpg, .png</div>
					</div>

					<div class="modal-footer">
						{cancel_button key=Done}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<form id="removeImageForm" method="post" ajaxAction="{ManageResourcesActions::ActionRemoveImage}">
		<input type="hidden" id="removeImageName" {formname key=RESOURCE_IMAGE} />
	</form>

	<form id="defaultImageForm" method="post" ajaxAction="{ManageResourcesActions::ActionDefaultImage}">
		<input type="hidden" id="defaultImageName" {formname key=RESOURCE_IMAGE} />
	</form>

	<div id="copyDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="copyModalLabel" aria-hidden="true">
		<form id="copyForm" method="post" enctype="multipart/form-data"
			ajaxAction="{ManageResourcesActions::ActionCopyResource}">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="copyModalLabel">{translate key=Copy}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="fw-bold" for="copyResourceName">{translate key='Name'}<i
									class="bi bi-asterisk text-danger align-top" style="font-size: 0.5rem;"></i></label>
							<input type="text" class="form-control required has-feedback" maxlength="85"
								id="copyResourceName" {formname key=RESOURCE_NAME} />
						</div>
					</div>

					<div class="modal-footer">
						{cancel_button}
						{add_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="durationDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="durationModalLabel"
		aria-hidden="true">
		<form id="durationForm" method="post" role="form" ajaxAction="{ManageResourcesActions::ActionChangeDuration}">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="durationModalLabel">{translate key=Duration}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="editMinDuration mb-2">
							<div class="form-check">
								<input type="checkbox" id="noMinimumDuration" class="noMinimumDuration form-check-input"
									data-related-inputs="#minDurationInputs" />
								<label for="noMinimumDuration"
									class="form-check-label">{translate key=ResourceMinLengthNone}</label>
							</div>
							{capture name="txtMinDuration" assign="txtMinDuration"}
								<div class='input-group-sm d-flex align-items-center flex-wrap gap-1'>
									<input type='number' size='3' id='minDurationDays' class='days form-control w-auto'
										maxlength='3' title='Days' max='999' min='0' placeholder='{translate key=days}' />
									<input type='number' size='2' id='minDurationHours' class='hours form-control w-auto'
										maxlength='2' title='Hours' max='99' min='0' placeholder='{translate key=hours}' />
									<input type='number' size='2' id='minDurationMinutes'
										class='minutes form-control w-auto' maxlength='2' title='Minutes' max='99' min='0'
										placeholder='{translate key=minutes}' />
									<input type='hidden' id='minDuration' class='interval minDuration'
										{formname key=MIN_DURATION} />
								</div>
							{/capture}
							<div id="minDurationInputs" class="collapse">
								<div class="d-flex align-items-center flex-wrap gap-1 ms-2">
									{translate key='ResourceMinLength' args=$txtMinDuration}
								</div>
							</div>
						</div>

						<div class="editMaxDuration mb-2">
							<div class="form-check">
								<input type="checkbox" id="noMaximumDuration" data-related-inputs="#maxDurationInputs"
									class="form-check-input" />
								<label for="noMaximumDuration"
									class="form-check-label">{translate key=ResourceMaxLengthNone}</label>
							</div>
							{capture name="txtMaxDuration" assign="txtMaxDuration"}
								<div class='input-group-sm d-flex align-items-center flex-wrap gap-1'>
									<input type='number' id='maxDurationDays' size='3' class='days form-control w-auto'
										maxlength='3' title='Days' max='999' min='0' placeholder='{translate key=days}' />
									<input type='number' id='maxDurationHours' size='2' class='hours form-control w-auto'
										maxlength='2' title='Hours' max='99' min='0' placeholder='{translate key=hours}' />
									<input type='number' id='maxDurationMinutes' size='2'
										class='minutes form-control w-auto' maxlength='2' title='Minutes' max='99' min='0'
										placeholder='{translate key=minutes}' />
									<input type='hidden' id='maxDuration' class='interval' {formname key=MAX_DURATION} />
								</div>
							{/capture}
							<div id='maxDurationInputs' class="collapse">
								<div class="d-flex align-items-center flex-wrap gap-1 ms-2">
									{translate key=ResourceMaxLength args=$txtMaxDuration}
								</div>
							</div>
						</div>

						<div class="editBuffer mb-2">
							<div class="form-check">
								<input type="checkbox" id="noBufferTime" data-related-inputs="#bufferInputs"
									class="form-check-input" />
								<label for="noBufferTime"
									class="form-check-label">{translate key=ResourceBufferTimeNone}</label>
							</div>

							{capture name="txtBufferTime" assign="txtBufferTime"}
								<div class='input-group-sm d-flex align-items-center flex-wrap gap-1'>
									<input type='number' id='bufferTimeDays' size='3' class='days form-control w-auto'
										maxlength='3' title='Days' max='999' min='0' placeholder='{translate key=days}' />
									<input type='number' id='bufferTimeHours' size='2' class='hours form-control w-auto'
										maxlength='2' title='Hours' max='99' min='0' placeholder='{translate key=hours}' />
									<input type='number' id='bufferTimeMinutes' size='2' class='minutes form-control w-auto'
										maxlength='2' title='Minutes' max='99' min='0'
										placeholder='{translate key=minutes}' />
									<input type='hidden' id='bufferTime' class='interval' {formname key=BUFFER_TIME} />
								</div>
							{/capture}
							<div id="bufferInputs" class="collapse">
								<div class="d-flex align-items-center flex-wrap gap-1 ms-2">
									{translate key=ResourceBufferTime args=$txtBufferTime}
								</div>
							</div>
						</div>

						<div class="editMultiDay">
							<div class="form-check">
								<input type="checkbox" {formname key=ALLOW_MULTIDAY} id="allowMultiDay"
									class="form-check-input" />
								<label for="allowMultiDay"
									class="form-check-label">{translate key=ResourceAllowMultiDay}</label>
							</div>
						</div>

					</div>

					<div class="modal-footer">
						{cancel_button}
						{update_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="capacityDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="capacityModalLabel"
		aria-hidden="true">
		<form id="capacityForm" method="post" role="form" ajaxAction="{ManageResourcesActions::ActionChangeCapacity}">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="capacityModalLabel">{translate key=Capacity}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="editCapacity">
							<div class="form-check">
								<input type="checkbox" id="unlimitedCapacity" class="unlimitedCapacity form-check-input"
									data-related-inputs="#maxCapacityInputs" />
								<label for="unlimitedCapacity"
									class="form-check-label">{translate key=ResourceCapacityNone}</label>
							</div>
							<div id="maxCapacityInputs" class="collapse">
								<div class="d-flex align-items-center gap-1 ms-4">
									{capture name="txtMaxCapacity" assign="txtMaxCapacity"}
										<label for='maxCapacity' class='visually-hidden'>{translate key=Capacity}</label>
										<input type='number' id='maxCapacity'
											class='form-control form-control-sm w-auto mid-number' min='0' max='9999'
											size='5' {formname key=MAX_PARTICIPANTS} />
									{/capture}
									{translate key='ResourceCapacity' args=$txtMaxCapacity}
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						{cancel_button}
						{update_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="accessDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="accessModalLabel"
		aria-hidden="true">
		<form id="accessForm" method="post" role="form" ajaxAction="{ManageResourcesActions::ActionChangeAccess}">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="accessModalLabel">{translate key=Access}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="editStartNoticeAdd">
							<div class="form-check">
								<input type="checkbox" id="noStartNoticeAdd" class="noStartNoticeAdd form-check-input"
									data-related-inputs="#startNoticeInputsAdd" />
								<label for="noStartNoticeAdd"
									class="form-check-label">{translate key=ResourceMinNoticeNone}</label>
							</div>
							{capture name="txtStartNoticeAdd" assign="txtStartNoticeAdd"}
								<div class='d-flex align-items-center flex-wrap gap-1'>
									<input type='number' id='startNoticeAddDays' size='3'
										class='days form-control form-control-sm w-auto' maxlength='3' title='Days'
										max='999' min='0' placeholder='{translate key=days}' />
									<input type='number' id='startNoticeAddHours' size='2'
										class='hours form-control form-control-sm w-auto' maxlength='2' max='99' min='0'
										title='Hours' placeholder='{translate key=hours}' />
									<input type='number' id='startNoticeAddMinutes' size='2'
										class='minutes form-control form-control-sm w-auto' maxlength='2' max='99' min='0'
										title='Minutes' placeholder='{translate key=minutes}' />
									<input type='hidden' id='startNoticeAdd' class='interval'
										{formname key=MIN_NOTICE_ADD} />
								</div>
							{/capture}
							<div id='startNoticeInputsAdd' class='collapse'>
								<div class="d-flex align-items-center flex-wrap gap-1 ms-4 mb-2">
									{translate key='ResourceMinNotice' args=$txtStartNoticeAdd}
								</div>
							</div>
						</div>

						<div class="editStartNoticeUpdate">
							<div class="form-check">
								<input type="checkbox" id="noStartNoticeUpdate"
									class="noStartNoticeUpdate form-check-input"
									data-related-inputs="#startNoticeInputsUpdate" />
								<label for="noStartNoticeUpdate"
									class="form-check-label">{translate key=ResourceMinNoticeNoneUpdate}</label>
							</div>
							{capture name="txtStartNoticeUpdate" assign="txtStartNoticeUpdate"}
								<div class='d-flex align-items-center flex-wrap gap-1'>
									<input type='number' id='startNoticeUpdateDays' size='3'
										class='days form-control form-control-sm w-auto' maxlength='3' title='Days'
										max='999' min='0' placeholder='{translate key=days}' />
									<input type='number' id='startNoticeUpdateHours' size='2'
										class='hours form-control form-control-sm w-auto' maxlength='2' max='99' min='0'
										title='Hours' placeholder='{translate key=hours}' />
									<input type='number' id='startNoticeUpdateMinutes' size='2'
										class='minutes form-control form-control-sm w-auto' maxlength='2' max='99' min='0'
										title='Minutes' placeholder='{translate key=minutes}' />
									<input type='hidden' id='startNoticeUpdate' class='interval'
										{formname key=MIN_NOTICE_UPDATE} />
								</div>
							{/capture}
							<div id='startNoticeInputsUpdate' class='collapse'>
								<div class="d-flex align-items-center flex-wrap gap-1 ms-4 mb-2">
									{translate key='ResourceMinNoticeUpdate' args=$txtStartNoticeUpdate}
								</div>
							</div>
						</div>

						<div class="editStartNoticeDelete">
							<div class="form-check">
								<input type="checkbox" id="noStartNoticeDelete"
									class="noStartNoticeDelete form-check-input"
									data-related-inputs="#startNoticeInputsDelete" />
								<label for="noStartNoticeDelete"
									class="form-check-label">{translate key=ResourceMinNoticeNoneDelete}</label>
							</div>
							{capture name="txtStartNoticeDelete" assign="txtStartNoticeDelete"}
								<div class='d-flex align-items-center flex-wrap gap-1'>
									<input type='number' id='startNoticeDeleteDays' size='3'
										class='days form-control form-control-sm w-auto' maxlength='3' title='Days'
										max='999' min='0' placeholder='{translate key=days}' />
									<input type='number' id='startNoticeDeleteHours' size='2'
										class='hours form-control form-control-sm w-auto' maxlength='2' max='99' min='0'
										title='Hours' placeholder='{translate key=hours}' />
									<input type='number' id='startNoticeDeleteMinutes' size='2'
										class='minutes form-control form-control-sm w-auto' maxlength='2' max='99' min='0'
										title='Minutes' placeholder='{translate key=minutes}' />
									<input type='hidden' id='startNoticeDelete' class='interval'
										{formname key=MIN_NOTICE_DELETE} />
								</div>
							{/capture}
							<div id='startNoticeInputsDelete' class='collapse'>
								<div class="d-flex align-items-center flex-wrap gap-1 ms-4 mb-2">
									{translate key='ResourceMinNoticeDelete' args=$txtStartNoticeDelete}
								</div>
							</div>
						</div>

						<div class="editEndNotice">
							<div class="form-check">
								<input type="checkbox" id="noEndNotice" data-related-inputs="#endNoticeInputs"
									class="form-check-input" />
								<label for="noEndNotice"
									class="form-check-label">{translate key=ResourceMaxNoticeNone}</label>
							</div>
							{capture name="txtEndNotice" assign="txtEndNotice"}
								<div class='d-flex align-items-center flex-wrap gap-1'>
									<input type='number' id='endNoticeDays' size='3'
										class='days form-control form-control-sm w-auto' maxlength='3' title='Days'
										max='999' min='0' placeholder='{translate key=days}' />
									<input type='number' id='endNoticeHours' size='2'
										class='hours form-control form-control-sm w-auto' maxlength='2' title='Hours'
										max='99' min='0' placeholder='{translate key=hours}' />
									<input type='number' id='endNoticeMinutes' size='2'
										class='minutes form-control form-control-sm w-auto' maxlength='2' max='99' min='0'
										title='Minutes' placeholder='{translate key=minutes}' />
									<input type='hidden' id='endNotice' class='interval' {formname key=MAX_NOTICE} />
								</div>
							{/capture}
							<div id='endNoticeInputs' class='collapse'>
								<div class="d-flex align-items-center flex-wrap gap-1 ms-4 mb-2">
									{translate key='ResourceMaxNotice' args=$txtEndNotice}
								</div>
							</div>
						</div>

						<div class="editRequiresApproval">
							<div class="form-check">
								<input type="checkbox" {formname key=REQUIRES_APPROVAL} id="requiresApproval"
									class="form-check-input" />
								<label for="requiresApproval"
									class="form-check-label">{translate key=ResourceRequiresApproval}</label>
							</div>
						</div>

						<div class="editAutoAssign">
							<div class="form-check">
								<input type="checkbox" {formname key=AUTO_ASSIGN} id="autoAssign" value="1"
									class="form-check-input" />
								<label for="autoAssign"
									class="form-check-label">{translate key=ResourcePermissionAutoGranted}</label>
							</div>
						</div>

						<div class="ms-4" id="autoAssignRemoveAllPermissions">
							<div class="form-check">
								<input type="checkbox" {formname key=AUTO_ASSIGN_CLEAR}
									id="autoAssignRemoveAllPermissionsChk" value="1" class="form-check-input" />
								<label for="autoAssignRemoveAllPermissionsChk"
									class="form-check-label">{translate key=RemoveExistingPermissions}</label>
							</div>
						</div>

						<div class="editCheckin">
							<div class="form-check">
								<input type="checkbox" {formname key=ENABLE_CHECK_IN} id="enableCheckIn"
									class="form-check-input" />
								<label for="enableCheckIn"
									class="form-check-label">{translate key=RequiresCheckInNotification}</label>
							</div>
							<div class="ms-4 collapse" id="autoReleaseMinutesDiv">
								{capture name="txtAutoRelease" assign="txtAutoRelease"}
									<label for='autoReleaseMinutes' class='visually-hidden'>Auto Release Minutes</label>
									<input type='number' max='99' min='0' id='autoReleaseMinutes'
										class='minutes form-control form-control-sm w-auto'
										{formname key=AUTO_RELEASE_MINUTES} />
								{/capture}
								<div class="d-flex align-items-center gap-1">
									{translate key='AutoReleaseNotification' args=$txtAutoRelease}
								</div>
							</div>
						</div>

						<div class="editConcurrent mb-2">
							<div class="form-check">
								<input type="checkbox" {formname key=ALLOW_CONCURRENT_RESERVATIONS}
									id="allowConcurrentChk" value="1" class="form-check-input" />
								<label for="allowConcurrentChk"
									class="form-check-label">{translate key=AllowConcurrentReservations}</label>
							</div>

							<div class="ms-4 collapse" id="allowConcurrentDiv">
								{capture name="txtConcurrentReservations" assign="txtConcurrentReservations"}
									<label for='maxConcurrentReservations' class='visually-hidden'>Maximum Concurrent
										Reservations</label>
									<input type='number' max='99' min='2' id='maxConcurrentReservations'
										class='form-control form-control-sm w-auto minutes'
										{formname key=MAX_CONCURRENT_RESERVATIONS} />
								{/capture}
								<div class="d-flex align-items-center gap-1">
									{translate key='ResourceConcurrentReservations' args=$txtConcurrentReservations}
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						{cancel_button}
						{update_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="statusDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
		aria-hidden="true">
		<form id="statusForm" method="post" role="form" ajaxAction="{ManageResourcesActions::ActionChangeStatus}">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="statusModalLabel">{translate key=ChangeResourceStatus}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="control-group form-group">
							<div class="form-group mb-2">
								<label class="fw-bold">{translate key=Status}
									<select {formname key=RESOURCE_STATUS_ID} class="statusId form-select">
										<option value="{ResourceStatus::AVAILABLE}">{translate key=Available}</option>
										<option value="{ResourceStatus::UNAVAILABLE}">{translate key=Unavailable}
										</option>
										<option value="{ResourceStatus::HIDDEN}">{translate key=Hidden}</option>
									</select>
								</label>
							</div>

							<div class="form-group d-none newStatusReason mb-3">
								<label class="clearfix">
									<div class="fw-bold float-start">{translate key=ReasonText}</div>
									<a href="#" class="float-end addStatusReason">
										<span class="visually-hidden">{translate key=ReasonText}</span>
										<span
											class="addStatusIcon bi bi-list-columns-reverse link-primary icon add"></span>
									</a>
									<input type="text" class="form-control resourceStatusReason"
										{formname key=RESOURCE_STATUS_REASON} />
								</label>
							</div>
							<div class="form-group existingStatusReason mb-3">
								<label class="clearfix">
									<div class="fw-bold float-start">{translate key=Reason}</div>
									<a href="#" class="float-end link-primary addStatusReason">
										<span class="visually-hidden">{translate key=Reason}</span>
										<span class="addStatusIcon bi bi-plus-lg icon add"></span>
									</a>
									<select {formname key=RESOURCE_STATUS_REASON_ID}
										class="form-select reasonId"></select>
								</label>
							</div>

							<div class="form-group">
								<div class="form-check">
									<input type="checkbox" id="toggleStatusChangeMessage" class="form-check-input"
										{formname key=SEND_AS_EMAIL} />
									<label for="toggleStatusChangeMessage"
										class="form-check-label">{translate key=NotifyUsers}</label>
								</div>
							</div>

							<div id="sendStatusChangeMessageContent" class="d-none border rounded bg-light p-3">
								<div class="form-group mb-2">
									<label class="fw-bold"
										for="statusMessageSendDays">{translate key=AllUsersWhoHaveAReservationInTheNext}</label>
									<div class="input-group">
										<input type="number" min="1" max="365" step="1" value="30"
											id="statusMessageSendDays" class="form-control" {formname key=DAY} />
										<div class="input-group-text">{translate key=days}</div>
									</div>
								</div>

								<div class="form-group">
									<label class="fw-bold" for="statusMessageContent">{translate key=Message}</label>
									<textarea id="statusMessageContent" class="form-control"
										{formname key=EMAIL_CONTENTS}></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{update_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="deletePrompt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteResourceDialogLabel"
		aria-hidden="true">
		<form id="deleteForm" method="post" ajaxAction="{ManageResourcesActions::ActionDelete}">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteResourceDialogLabel">{translate key=Delete}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-warning">
							<div>{translate key=DeleteWarning}</div>
							{translate key=DeleteResourceWarning}:
							<ul>
								<li>{translate key=DeleteResourceWarningReservations}</li>
								<li>{translate key=DeleteResourceWarningPermissions}</li>
							</ul>

							{translate key=DeleteResourceWarningReassign}
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{delete_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="bulkUpdateDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bulkUpdateLabel"
		aria-hidden="true">

		<div class="modal-dialog modal-lg modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="bulkUpdateLabel">{translate key=BulkResourceUpdate}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<form id="bulkUpdateForm" method="post" ajaxAction="{ManageResourcesActions::ActionBulkUpdate}"
						class="form-vertical" role="form">
						<div id="bulkUpdateErrors" class="error d-none mb-2">
							{async_validator id="bulkAttributeValidator" key=""}
						</div>
						<div class="d-flex align-items-center gap-1 mb-1">
							<div>{translate key=Select}</div>
							<a href="#" id="checkAllResources" class="link-primary">{translate key=All}</a>
							<div class="vr"></div>
							<a href="#" id="checkNoResources" class="link-primary">{translate key=None}</a>
						</div>
						<div id="bulkUpdateList" class="bg-light border rounded p-2 mb-2"></div>
						<div class="accordion" id="accordionbulkUpdate">
							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
										data-bs-target="#panelCommon" aria-expanded="false" aria-controls="panelCommon">
										{translate key=Common}
									</button>
								</h2>
								<div id="panelCommon" class="accordion-collapse collapse">
									<div class="accordion-body">
										<div class="form-group mb-2">
											<label for="bulkEditSchedule"
												class="fw-bold">{translate key=MoveToSchedule}:</label>
											<select id="bulkEditSchedule" class="form-select"
												{formname key=SCHEDULE_ID}>
												<option value="-1">{translate key=Unchanged}</option>
												{foreach from=$Schedules item=scheduleName key=scheduleId}
													<option value="{$scheduleId}">{$scheduleName}</option>
												{/foreach}
											</select>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditResourceType"
												class="fw-bold">{translate key=ResourceType}:</label>
											<select id="bulkEditResourceType" class="form-select"
												{formname key=RESOURCE_TYPE_ID}>
												<option value="-1">{translate key=Unchanged}</option>
												<option value="">-- {translate key=None} --</option>
												{foreach from=$ResourceTypes item=resourceType key=id}
													<option value="{$id}">{$resourceType->Name()}</option>
												{/foreach}
											</select>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditLocation"
												class="fw-bold">{translate key=Location}:</label>
											<input id="bulkEditLocation" type="text" class="form-control" maxlength="85"
												{formname key=RESOURCE_LOCATION} />
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditContact"
												class="fw-bold">{translate key=Contact}:</label>
											<input id="bulkEditContact" type="text" class="form-control" maxlength="85"
												{formname key=RESOURCE_CONTACT} />
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditAdminGroupId"
												class="fw-bold">{translate key=ResourceAdministrator}:</label>
											<select id="bulkEditAdminGroupId" {formname key=RESOURCE_ADMIN_GROUP_ID}
												class="form-select">
												<option value="-1">{translate key=Unchanged}</option>
												<option value="">-- {translate key=None} --</option>
												{foreach from=$AdminGroups item=adminGroup}
													<option value="{$adminGroup->Id}">{$adminGroup->Name}</option>
												{/foreach}
											</select>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditStatusId"
												class="fw-bold">{translate key=Status}:</label>
											<select id="bulkEditStatusId" {formname key=RESOURCE_STATUS_ID}
												class="form-select">
												<option value="-1">{translate key=Unchanged}</option>
												<option value="{ResourceStatus::AVAILABLE}">{translate key=Available}
												</option>
												<option value="{ResourceStatus::UNAVAILABLE}">
													{translate key=Unavailable}</option>
												<option value="{ResourceStatus::HIDDEN}">{translate key=Hidden}</option>
											</select>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditStatusReasonId"
												class="fw-bold">{translate key=Reason}:</label>
											<select id="bulkEditStatusReasonId" {formname key=RESOURCE_STATUS_REASON_ID}
												class="form-select">
											</select>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditDescription"
												class="fw-bold">{translate key=Description}:</label>
											<textarea id="bulkEditDescription" class="form-control"
												{formname key=RESOURCE_DESCRIPTION}></textarea>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditNotes" class="fw-bold">{translate key=Notes}:</label>
											<textarea id="bulkEditNotes" class="form-control"
												{formname key=RESOURCE_NOTES}></textarea>
										</div>
									</div>

								</div>
							</div>

							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
										data-bs-target="#panelCapacity" aria-expanded="false"
										aria-controls="panelCapacity">
										{translate key=Capacity}
									</button>
								</h2>
								<div id="panelCapacity" class="accordion-collapse collapse">
									<div class="accordion-body">
										<div class="form-group">
											<div class="form-check">
												<input type="checkbox" id="bulkEditUnlimitedCapacity"
													class="unlimitedCapacity form-check-input"
													data-related-inputs="#bulkEditMaxCapacityInputs"
													{formname key=MAX_PARTICIPANTS_UNLIMITED} />
												<label class="form-check-label"
													for="bulkEditUnlimitedCapacity">{translate key=ResourceCapacityNone}</label>
											</div>
											<div id='bulkEditMaxCapacityInputs'>
												{capture name="txtBulkEditMaxCapacity" assign="txtBulkEditMaxCapacity"}
													<label for='bulkEditMaxCapacity'
														class='visually-hidden'>{translate key=Capacity}</label>
													<input type='number' id='bulkEditMaxCapacity'
														class='form-control form-control-sm w-auto mid-number' min='0'
														max='9999' size='5' {formname key=MAX_PARTICIPANTS} />
												{/capture}
												<div class="d-flex align-items-center gap-1 ms-4">
													{translate key='ResourceCapacity' args=$txtBulkEditMaxCapacity}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
										data-bs-target="#panelDuration" aria-expanded="false"
										aria-controls="panelDuration">
										{translate key=Duration}
									</button>
								</h2>
								<div id="panelDuration" class="accordion-collapse collapse">
									<div class="accordion-body">
										<div class="form-group mb-2">
											<div class="form-check">
												<input type="checkbox" id="bulkEditNoMinimumDuration" value="1"
													{formname key=MIN_DURATION_NONE} class="form-check-input"
													data-related-inputs="#bulkMinDuration" />
												<label class="form-check-label"
													for="bulkEditNoMinimumDuration">{translate key=ResourceMinLengthNone}</label>
											</div>

											{capture name="txtMinDuration" assign="txtMinDuration"}
												<label for='bulkEditMinDurationDays'
													class='visually-hidden'>{translate key=days}</label>
												<label for='bulkEditMinDurationHours'
													class='visually-hidden'>{translate key=hours}</label>
												<label for='bulkEditMinDurationMinutes'
													class='visually-hidden'>{translate key=minutes}</label>
												<input type='number' id='bulkEditMinDurationDays' size='3'
													class='days form-control form-control-sm w-auto' maxlength='3' min='0'
													max='999' placeholder='{translate key=days}' />
												<input type='number' id='bulkEditMinDurationHours' size='2'
													class='hours form-control form-control-sm w-auto' maxlength='2' min='0'
													max='99' placeholder='{translate key=hours}' />
												<input type='number' id='bulkEditMinDurationMinutes' size='2'
													class='minutes form-control form-control-sm w-auto' maxlength='2'
													min='0' max='99' placeholder='{translate key=minutes}' />
												<input type='hidden' id='bulkEditMinDuration' class='interval'
													{formname key=MIN_DURATION} />
											{/capture}
											<div id="bulkMinDuration">
												<div class="d-flex align-items-center gap-1 ms-4">
													{translate key='ResourceMinLength' args=$txtMinDuration}</div>
											</div>
										</div>

										<div class="form-group mb-2">
											<div class="form-check">
												<input type="checkbox" id="bulkEditNoMaximumDuration" value="1"
													{formname key=MAX_DURATION_NONE}
													data-related-inputs="#bulkMaxDuration" class="form-check-input" />
												<label for="bulkEditNoMaximumDuration"
													class="form-check-label">{translate key=ResourceMaxLengthNone}</label>
											</div>

											{capture name="txtMaxDuration" assign="txtMaxDuration"}
												<label for='bulkEditMaxDurationDays'
													class='visually-hidden'>{translate key=days}</label>
												<label for='bulkEditMaxDurationHours'
													class='visually-hidden'>{translate key=hours}</label>
												<label for='bulkEditMaxDurationMinutes'
													class='visually-hidden'>{translate key=minutes}</label>
												<input type='number' id='bulkEditMaxDurationDays' size='3'
													class='days form-control form-control-sm w-auto' maxlength='3' min='0'
													max='999' placeholder='{translate key=days}' />
												<input type='number' id='bulkEditMaxDurationHours' size='2'
													class='hours form-control form-control-sm w-auto' maxlength='2' min='0'
													max='99' placeholder='{translate key=hours}' />
												<input type='number' id='bulkEditMaxDurationMinutes' size='2'
													class='minutes form-control form-control-sm w-auto' maxlength='2'
													min='0' max='99' placeholder='{translate key=minutes}' />
												<input type='hidden' id='bulkEditMaxDuration' class='interval'
													{formname key=MAX_DURATION} />
											{/capture}
											<div id="bulkMaxDuration">
												<div class="d-flex align-items-center gap-1 ms-4">
													{translate key=ResourceMaxLength args=$txtMaxDuration}</div>
											</div>
										</div>

										<div class="form-group mb-2">
											<div class="form-check">
												<input type="checkbox" id="bulkEditNoBufferTime" value="1"
													{formname key=BUFFER_TIME_NONE} class="form-check-input"
													data-related-inputs="#bulkBufferTime" />
												<label for="bulkEditNoBufferTime"
													class="form-check-label">{translate key=ResourceBufferTimeNone}</label>
											</div>

											{capture name="txtBufferTime" assign="txtBufferTime"}
												<label for='bulkEditBufferTimeDays'
													class='visually-hidden'>{translate key=days}</label>
												<label for='bulkEditBufferTimeHours'
													class='visually-hidden'>{translate key=hours}</label>
												<label for='bulkEditBufferTimeMinutes'
													class='visually-hidden'>{translate key=minutes}</label>
												<input type='number' id='bulkEditBufferTimeDays' size='3'
													class='days form-control form-control-sm w-auto' maxlength='3' min='0'
													max='999' placeholder='{translate key=days}' />
												<input type='number' id='bulkEditBufferTimeHours' size='2'
													class='hours form-control form-control-sm w-auto' maxlength='2' min='0'
													max='99' placeholder='{translate key=hours}' />
												<input type='number' id='bulkEditBufferTimeMinutes' size='2'
													class='minutes form-control form-control-sm w-auto' maxlength='2'
													min='0' max='99' placeholder='{translate key=minutes}' />
												<input type='hidden' id='bulkEditBufferTime' class='interval'
													{formname key=BUFFER_TIME} />
											{/capture}
											<div id="bulkBufferTime">
												<div class="d-flex align-items-center gap-1 ms-4">
													{translate key=ResourceBufferTime args=$txtBufferTime}</div>
											</div>

										</div>
									</div>
								</div>
							</div>

							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
										data-bs-target="#panelAccess" aria-expanded="false" aria-controls="panelAccess">
										{translate key=Access}
									</button>
								</h2>
								<div id="panelAccess" class="accordion-collapse collapse">
									<div class="accordion-body">
										<div class="form-group mb-2">
											<div class="form-check">
												<input type="checkbox" id="bulkEditNoStartNoticeAdd" value="1"
													{formname key=MIN_NOTICE_NONE_ADD} class="form-check-input"
													data-related-inputs="#bulkStartNoticeInputsAdd" />
												<label class="form-check-label"
													for="bulkEditNoStartNoticeAdd">{translate key=ResourceMinNoticeNone}</label>
											</div>
											{capture name="txtStartNoticeAdd" assign="txtStartNoticeAdd"}
												<label for='bulkEditStartNoticeAddDays'
													class='visually-hidden'>{translate key=days}</label>
												<label for='bulkEditStartNoticeAddHours'
													class='visually-hidden'>{translate key=hours}</label>
												<label for='bulkEditStartNoticeAddMinutes'
													class='visually-hidden'>{translate key=minutes}</label>
												<input type='number' id='bulkEditStartNoticeAddDays' size='3'
													class='days form-control form-control-sm w-auto' maxlength='3' min='0'
													max='999' placeholder='{translate key=days}' />
												<input type='number' id='bulkEditStartNoticeAddHours' size='2'
													class='hours form-control form-control-sm w-auto' maxlength='2' min='0'
													max='99' placeholder='{translate key=hours}' />
												<input type='number' id='bulkEditStartNoticeAddMinutes' size='2'
													class='minutes form-control form-control-sm w-auto' maxlength='2'
													min='0' max='99' placeholder='{translate key=minutes}' />
												<input type='hidden' id='bulkEditStartNoticeAdd' class='interval'
													{formname key=MIN_NOTICE_ADD} />
											{/capture}
											<div id='bulkStartNoticeInputsAdd'>
												<div class="d-flex align-items-center gap-1 ms-4">
													{translate key='ResourceMinNotice' args=$txtStartNoticeAdd}</div>
											</div>
										</div>

										<div class="form-group mb-2">
											<div class="form-check">
												<input type="checkbox" id="bulkEditNoStartNoticeUpdate" value="1"
													class="form-check-input" {formname key=MIN_NOTICE_NONE_UPDATE}
													data-related-inputs="#bulkStartNoticeInputsUpdate" />
												<label for="bulkEditNoStartNoticeUpdate"
													class="form-check-label">{translate key=ResourceMinNoticeNoneUpdate}</label>
											</div>
											{capture name="txtStartNoticeUpdate" assign="txtStartNoticeUpdate"}
												<label for='bulkEditStartNoticeUpdateDays'
													class='visually-hidden'>{translate key=days}</label>
												<label for='bulkEditStartNoticeUpdateHours'
													class='visually-hidden'>{translate key=hours}</label>
												<label for='bulkEditStartNoticeUpdateMinutes'
													class='visually-hidden'>{translate key=minutes}</label>
												<input type='number' id='bulkEditStartNoticeUpdateDays' size='3'
													class='days form-control form-control-sm w-auto' maxlength='3' min='0'
													max='999' placeholder='{translate key=days}' />
												<input type='number' id='bulkEditStartNoticeUpdateHours' size='2'
													class='hours form-control form-control-sm w-auto' maxlength='2' min='0'
													max='99' placeholder='{translate key=hours}' />
												<input type='number' id='bulkEditStartNoticeUpdateMinutes' size='2'
													class='minutes form-control form-control-sm w-auto' maxlength='2'
													min='0' max='99' placeholder='{translate key=minutes}' />
												<input type='hidden' id='bulkEditStartNoticeUpdate' class='interval'
													{formname key=MIN_NOTICE_UPDATE} />
											{/capture}
											<div id='bulkStartNoticeInputsUpdate'>
												<div class="d-flex align-items-center gap-1 ms-4">
													{translate key='ResourceMinNoticeUpdate' args=$txtStartNoticeUpdate}
												</div>
											</div>
										</div>

										<div class="form-group mb-2">
											<div class="form-check">
												<input type="checkbox" id="bulkEditNoStartNoticeDelete" value="1"
													{formname key=MIN_NOTICE_NONE_DELETE} class="form-check-input"
													data-related-inputs="#bulkStartNoticeInputsDelete" />
												<label class="form-check-label"
													for="bulkEditNoStartNoticeDelete">{translate key=ResourceMinNoticeNoneDelete}</label>
											</div>
											{capture name="txtStartNoticeDelete" assign="txtStartNoticeDelete"}
												<label for='bulkEditStartNoticeDeleteDays'
													class='visually-hidden'>{translate key=days}</label>
												<label for='bulkEditStartNoticeDeleteHours'
													class='visually-hidden'>{translate key=hours}</label>
												<label for='bulkEditStartNoticeDeleteMinutes'
													class='visually-hidden'>{translate key=minutes}</label>
												<input type='number' id='bulkEditStartNoticeDeleteDays' size='3'
													class='days form-control form-control-sm w-auto' maxlength='3' min='0'
													max='999' placeholder='{translate key=days}' />
												<input type='number' id='bulkEditStartNoticeDeleteHours' size='2'
													class='hours form-control form-control-sm w-auto' maxlength='2' min='0'
													max='99' placeholder='{translate key=hours}' />
												<input type='number' id='bulkEditStartNoticeDeleteMinutes' size='2'
													class='minutes form-control form-control-sm w-auto' maxlength='2'
													min='0' max='99' placeholder='{translate key=minutes}' />
												<input type='hidden' id='bulkEditStartNoticeDelete' class='interval'
													{formname key=MIN_NOTICE_DELETE} />
											{/capture}
											<div id='bulkStartNoticeInputsDelete'>
												<div class="d-flex align-items-center gap-1 ms-4">
													{translate key='ResourceMinNoticeDelete' args=$txtStartNoticeDelete}
												</div>
											</div>
										</div>

										<div class="form-group mb-2">
											<div class="form-check">
												<input type="checkbox" id="bulkEditNoEndNotice" value="1"
													{formname key=MAX_NOTICE_NONE} class="form-check-input"
													data-related-inputs="#bulkEndNotice" />
												<label class="form-check-label"
													for="bulkEditNoEndNotice">{translate key=ResourceMaxNoticeNone}</label>
											</div>

											{capture name="txtEndNotice" assign="txtEndNotice"}
												<label for='bulkEditEndNoticeDays'
													class='visually-hidden'>{translate key=days}</label>
												<label for='bulkEditEndNoticeHours'
													class='visually-hidden'>{translate key=hours}</label>
												<label for='bulkEditEndNoticeMinutes'
													class='visually-hidden'>{translate key=minutes}</label>
												<input type='text' id='bulkEditEndNoticeDays' size='3'
													class='days form-control form-control-sm w-auto' maxlength='3'
													placeholder='{translate key=days}' />
												<input type='text' id='bulkEditEndNoticeHours' size='2'
													class='hours form-control form-control-sm w-auto' maxlength='2'
													placeholder='{translate key=hours}' />
												<input type='text' id='bulkEditEndNoticeMinutes' size='2'
													class='minutes form-control form-control-sm w-auto' maxlength='2'
													placeholder='{translate key=minutes}' />
												<input type='hidden' id='bulkEditEndNotice' class='interval'
													{formname key=MAX_NOTICE} />
											{/capture}
											<div id="bulkEndNotice">
												<div class="d-flex align-items-center gap-1 ms-4">
													{translate key='ResourceMaxNotice' args=$txtEndNotice}</div>
											</div>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditAllowMultiday"
												class="fw-bold">{translate key=ResourceAllowMultiDay}</label>
											<select id="bulkEditAllowMultiday" class="form-select form-select-sm"
												{formname key=ALLOW_MULTIDAY}>
												{html_options options=$YesNoUnchangedOptions}
											</select>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditRequiresApproval"
												class="fw-bold">{translate key='ResourceRequiresApproval'}</label>
											<select id="bulkEditRequiresApproval" class="form-select form-select-sm"
												{formname key=REQUIRES_APPROVAL}>
												{html_options options=$YesNoUnchangedOptions}
											</select>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditAutoAssign"
												class="fw-bold">{translate key='ResourcePermissionAutoGranted'}</label>
											<select id="bulkEditAutoAssign" class="form-select form-select-sm"
												{formname key=AUTO_ASSIGN}>
												{html_options options=$YesNoUnchangedOptions}
											</select>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditEnableCheckIn"
												class="fw-bold">{translate key=RequiresCheckInNotification}</label>
											<select id="bulkEditEnableCheckIn" class="form-select form-select-sm"
												{formname key=ENABLE_CHECK_IN}>
												{html_options options=$YesNoUnchangedOptions}
											</select>
											<div class="d-none" id="bulkUpdateAutoReleaseMinutesDiv">
												{capture name="bulkEditTxtAutoRelease" assign="bulkEditTxtAutoRelease"}
													<label for='bulkEditAutoReleaseMinutes' class='visually-hidden'>Auto
														Release
														minutes</label>
													<input type='number' max='99' min='0' id='bulkEditAutoReleaseMinutes'
														class='minutes form-control form-control-sm w-auto'
														{formname key=AUTO_RELEASE_MINUTES} />
												{/capture}
												<div class="d-flex align-items-center gap-1 ms-4 mt-2">
													{translate key='AutoReleaseNotification' args=$bulkEditTxtAutoRelease}
												</div>
											</div>
										</div>

										<div class="form-group mb-2">
											<label for="bulkEditConcurrent"
												class="fw-bold">{translate key=AllowConcurrentReservations}</label>
											<select id="bulkEditConcurrent" class="form-select form-select-sm"
												{formname key=ALLOW_CONCURRENT_RESERVATIONS}>
												{html_options options=$YesNoUnchangedOptions}
											</select>
											<div class="d-none" id="bulkEditAllowConcurrentDiv">
												{capture name="txtConcurrentReservations" assign="txtConcurrentReservations"}
													<label for='bulkEditMaxConcurrentReservations'
														class='visually-hidden'>Maximum
														Concurrent Reservations</label>
													<input type='number' max='99' min='2'
														id='bulkEditMaxConcurrentReservations'
														class='form-control form-control-sm w-auto minutes'
														{formname key=MAX_CONCURRENT_RESERVATIONS} value='2' />
												{/capture}
												<div class="d-flex align-items-center gap-1 ms-4 mt-2">
													{translate key='ResourceConcurrentReservations' args=$txtConcurrentReservations}
												</div>
											</div>
										</div>

										<div class="form-group">
											<label for="bulkEditAllowSubscriptions"
												class="fw-bold">{translate key='TurnOnSubscription'}</label>
											<select id="bulkEditAllowSubscriptions" class="form-select form-select-sm"
												{formname key=ALLOW_CALENDAR_SUBSCRIPTIONS}>
												{html_options options=$YesNoUnchangedOptions}
											</select>
										</div>
									</div>
								</div>
							</div>
							{if $CreditsEnabled}
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#panelCredits" aria-expanded="false"
											aria-controls="panelCredits">
											{translate key=Credits}
										</button>
									</h2>
									<div id="panelCredits" class="accordion-collapse collapse">
										<div class="accordion-body">
											<div class="form-group mb-2">
												{capture name="bulkEditCreditsPerSLot" assign="bulkEditCreditsPerSLot"}
													<label for='bulkEditCreditsPerSlot' class='visually-hidden'>Credits Per
														Slot</label>
													<input type='number' min='0' step='1' id='bulkEditCreditsPerSlot'
														class='credits form-control form-control-sm w-auto'
														{formname key=CREDITS} />
												{/capture}
												<div class="d-flex align-items-center gap-1">
													{translate key='CreditUsagePerSlot' args=$bulkEditCreditsPerSLot}
												</div>
											</div>

											<div class="form-group">
												{capture name="bulkEditPeakCreditsPerSlot" assign="bulkEditPeakCreditsPerSlot"}
													<label for='bulkEditPeakCreditsPerSlot' class='visually-hidden'>Peak Credits
														Per
														Slot</label>
													<input type='number' min='0' step='1' id='bulkEditPeakCreditsPerSlot'
														class='credits form-control form-control-sm w-auto'
														{formname key=PEAK_CREDITS} />
												{/capture}
												<div class="d-flex align-items-center gap-1">
													{translate key='PeakCreditUsagePerSlot' args=$bulkEditPeakCreditsPerSlot}
												</div>
											</div>
										</div>
									</div>
								</div>
							{/if}

							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
										data-bs-target="#panelAdditionalAttributes" aria-expanded="false"
										aria-controls="panelAdditionalAttributes">
										{translate key=AdditionalAttributes}
									</button>
								</h2>
								<div id="panelAdditionalAttributes" class="accordion-collapse collapse">
									<div class="accordion-body">
										{foreach from=$AttributeFilters item=attribute}
											{if !$attribute->UniquePerEntity()}
												<div class="customAttribute">
													{control type="AttributeControl" attribute=$attribute searchmode=true}
												</div>
											{/if}
										{/foreach}
									</div>
								</div>
							</div>

						</div>
						{csrf_token}
					</form>
				</div>
				<div class="modal-footer">
					{cancel_button}
					{update_button submit=true form='bulkUpdateForm'}
					{indicator}
				</div>
			</div>
		</div>
	</div>

	<div id="bulkDeleteDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bulkDeleteLabel"
		aria-hidden="true">
		<form id="bulkDeleteForm" method="post" ajaxAction="{ManageResourcesActions::ActionBulkDelete}"
			class="form-vertical" role="form">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="bulkDeleteLabel">{translate key=BulkResourceDelete}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-warning">
							<div>{translate key=DeleteWarning}</div>
							{translate key=DeleteResourceWarning}:
							<ul>
								<li>{translate key=DeleteResourceWarningReservations}</li>
								<li>{translate key=DeleteResourceWarningPermissions}</li>
							</ul>

							{translate key=DeleteResourceWarningReassign}
						</div>

						<div class="d-flex align-items-center gap-1">{translate key=Select}
							<a href="#" id="checkAllDeleteResources" class="link-primary">{translate key=All}</a>
							<div class="vr"></div>
							<a href="#" id="checkNoDeleteResources" class="link-primary">{translate key=None}</a>
						</div>
						<div id="bulkDeleteList" class="bg-light border rounded p-2"></div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{delete_button}
						{indicator}
					</div>
				</div>
			</div>
			{csrf_token}
		</form>
	</div>

	<div id="userDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userPermissionDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="userPermissionDialogLabel">{translate key=Users}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="userSearch">{translate key=AddUser}</label>
						<a href="#" id="browseUsers" class="link-primary">{translate key=Browse}</a>
						<input type="text" id="userSearch" class="form-control" size="60" />
					</div>
					<div id="resourceUserList"></div>
				</div>
			</div>
		</div>
	</div>

	<div id="allUsers" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="browseUsersDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="browseUsersDialogLabel">{translate key=AllUsers}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div id="allUsersList"></div>
				</div>
			</div>
		</div>
	</div>

	<form id="changeUserForm" method="post" ajaxAction="{ManageResourcesActions::ActionChangeUserPermission}">
		<input type="hidden" id="changeUserId" {formname key=USER_ID} />
		<input type="hidden" id="changeUserType" {formname key=PERMISSION_TYPE} />
	</form>

	<div id="groupDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="browseGroupsDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="browseGroupsDialogLabel">{translate key=AllGroups}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body scrollable-modal-content">
					<div class="form-group">
						<label for="groupSearch">{translate key=AddGroup}</label>
						<a href="#" id="browseGroups" class="link-primary">{translate key=AllGroups}</a>
						<input type="text" id="groupSearch" class="form-control" size="60" />
					</div>

					<div id="resourceGroupList"></div>
				</div>
			</div>
		</div>
	</div>

	<div id="allGroups" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="groupPermissionDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="groupPermissionDialogLabel">{translate key=Groups}</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div id="allGroupsList"></div>
				</div>
			</div>
		</div>
	</div>

	<form id="changeGroupForm" method="post" ajaxAction="{ManageResourcesActions::ActionChangeGroupPermission}">
		<input type="hidden" id="changeGroupId" {formname key=GROUP_ID} />
		<input type="hidden" id="changeGroupType" {formname key=PERMISSION_TYPE} />
	</form>

	<div class="modal fade" id="resourceGroupDialog" tabindex="-1" role="dialog"
		aria-labelledby="resourceGroupsModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<form id="resourceGroupForm" method="post"
				ajaxAction="{ManageResourcesActions::ActionChangeResourceGroups}">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="resourceGroupsModalLabel">{translate key=ResourceGroups}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div id="resourceGroups">{translate key=None}</div>
			</form>
		</div>
		<div class="modal-footer">
			{cancel_button}
			{update_button form="resourceGroupForm" submit=true}
			{indicator}
		</div>
	</div>
</div>
</div>

<form id="colorForm" method="post" ajaxAction="{ManageResourcesActions::ActionChangeColor}">
	<input type="hidden" id="reservationColor" {formname key=RESERVATION_COLOR} />
</form>

<div id="creditsDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="creditsModalLabel"
	aria-hidden="true">
	<form id="creditsForm" method="post" role="form" ajaxAction="{ManageResourcesActions::ActionChangeCredits}">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="creditsModalLabel">{translate key=Credits}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="mb-2">
						{capture name="creditsPerSLot" assign="creditsPerSLot"}
							<label for='creditsPerSlot' class='visually-hidden'>Credits Per Slot</label>
							<input type='number' min='0' step='1' id='creditsPerSlot'
								class='credits form-control form-control-sm w-auto' {formname key=CREDITS} />
						{/capture}
						<div class="d-flex align-items-center gap-1">
							{translate key='CreditUsagePerSlot' args=$creditsPerSLot}</div>
					</div>

					<div>
						{capture name="peakCreditsPerSlot" assign="peakCreditsPerSlot"}
							<label for='peakCreditsPerSlot' class='visually-hidden'>Peak Credits Per Slot</label>
							<input type='number' min='0' step='1' id='peakCreditsPerSlot'
								class='credits form-control form-control-sm w-auto' {formname key=PEAK_CREDITS} />
						{/capture}
						<div class="d-flex align-items-center gap-1">
							{translate key='PeakCreditUsagePerSlot' args=$peakCreditsPerSlot}</div>
					</div>
				</div>

				<div class="modal-footer">
					{cancel_button}
					{update_button}
					{indicator}
				</div>
			</div>
		</div>
	</form>
</div>

<div id="importDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
	<form id="importForm" class="form" role="form" method="post" enctype="multipart/form-data"
		ajaxAction="{ManageResourcesActions::ImportResources}">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="importModalLabel">{translate key=Import}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div id="importInstructions" class="alert alert-info fst-italic">
						<div class="note">{translate key=ResourceImportInstructions}</div>
						<a href="{$smarty.server.SCRIPT_NAME}?dr=template" class="alert-link"
							download="{$smarty.server.SCRIPT_NAME}?dr=template"
							target="_blank">{translate key=GetTemplate}<i class="bi bi-download ms-1"></i></a>
					</div>
					<div id="importResults" class="validationSummary alert alert-danger d-none">
						<ul>
							{async_validator id="fileExtensionValidator" key=""}
						</ul>
					</div>
					<div id="importErrors" class="alert alert-danger d-none"></div>
					<div id="importResult" class="alert alert-success d-none">
						<span>{translate key=RowsImported}</span>

						<span id="importCount" class="fw-bold">0</span>
						<span>{translate key=RowsSkipped}</span>

						<span id="importSkipped" class="fw-bold">0</span>
						<a class="alert-link" href="{$smarty.server.SCRIPT_NAME}">{translate key=Done}</a>
					</div>
					<div class="">
						<label for="resourceImportFile" class="visually-hidden">Resource Import File</label>
						<input type="file" {formname key=RESOURCE_IMPORT_FILE} id="resourceImportFile"
							class="form-control" accept=".csv" />
						<div class="form-check">
							<input type="checkbox" id="updateOnImport" class="form-check-input"
								{formname key=UPDATE_ON_IMPORT} />
							<label for="updateOnImport"
								class="form-check-label">{translate key=UpdateResourcesOnImport}</label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					{cancel_button}
					{add_button key=Import}
					{indicator}
				</div>
			</div>
		</div>
	</form>
</div>

{csrf_token}

{include file="javascript-includes.tpl" InlineEdit=true Clear=true DataTable=true Trumbowyg=true }
{datatable tableId=$tableId}
{jsfile src="ajax-helpers.js"}
{jsfile src="autocomplete.js"}
{jsfile src="js/tree.jquery.js"}
{jsfile src="admin/resource.js"}
{jsfile src="dropzone.js"}
{jsfile src="search-clear.js"}

<script type="text/javascript">
	function addTrumbowygType() {
		var Trumbowyg = function(options) {
			this.init('trumbowyg', options, Trumbowyg.defaults);
		};
		$.fn.editableutils.inherit(Trumbowyg, $.fn.editabletypes.abstractinput);
		$.extend(Trumbowyg.prototype, {
			render: function() {
				// Set any provided classes or attributes
				this.setClass();
				this.setAttr('placeholder');

				this.$input.trumbowyg({
					tagsToRemove: ['script', 'link'],
					removeformatPasted: true,
					urlProtocol: true,
					btns: [
						['bold', 'italic', 'underline'],
						['link'],
						['unorderedList', 'orderedList']
					]
				});
			},

			value2html: function(value, element) {
				const sanitizedHtml = DOMPurify.sanitize(value || '');
				$(element).html(sanitizedHtml);
			},

			html2value: function(html) {
				return html || '';
			},

			activate: function() {
				if (this.$input.data('trumbowyg')) {
					this.$input.trumbowyg('open');
				}
			},
			value2input: function(value) {
				this.$input.trumbowyg('html', value);
			},
			input2value: function() {
				const sanitizedHtml = DOMPurify.sanitize(this.$input.trumbowyg('html'));
				return sanitizedHtml;
			}
		});

		Trumbowyg.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
			tpl: '<textarea></textarea>',
			inputclass: 'input-large',
			placeholder: null,
			rows: 7
		});
		$.fn.editabletypes.trumbowyg = Trumbowyg;
	}

	function setUpPopovers() {
		$('[rel="popover"]').popover({
			container: 'body',
			html: true,
			placement: 'top',
			trigger: 'manual',
			content: function() {
				var popoverId = $(this).data('popover-content');
				return $(popoverId).html();
			}
		});

		$('[rel="popover"]').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			// Hide other popovers if needed
			$('[rel="popover"]').not(this).popover('hide');

			// Toggle the clicked one
			$(this).popover('toggle');
		});

		$('.descriptionValue').on('shown', function(e, editable) {
			$(document).off('click.editable');
		});

		$('.notesValue').on('shown', function(e, editable) {
			$(document).off('click.editable');
		});
	}

	function setupCustomAttributesIcon() {
		$('[id^="customAttributesIcon"]').each(function() {
			const icon = $(this);
			const target = icon.closest('a').data('bs-target');

			$(target).on('shown.bs.collapse', function() {
				icon.removeClass('bi-chevron-down').addClass('bi-chevron-up');
			});

			$(target).on('hidden.bs.collapse', function() {
				icon.removeClass('bi-chevron-up').addClass('bi-chevron-down');
			});
		});
	}

	function setUpEditables() {
		$.fn.editable.defaults.mode = 'popup';
		$.fn.editable.defaults.toggle = 'manual';
		$.fn.editable.defaults.emptyclass = '';
		$.fn.editable.default
		$.fn.editable.defaults.params = function(params) {
			params.CSRF_TOKEN = $('#csrf_token').val();
			return params;
		};

		var updateUrl = '{$smarty.server.SCRIPT_NAME}?action=';
        var xUserAutocompleteUrl = "../ajax/autocomplete.php?type={AutoCompleteType::XUser}";

		$('.resourceNameField').editable({
				url: updateUrl + '{ManageResourcesActions::ActionRename}', validate: function (value) {
				if ($.trim(value) == '') {
					return '{translate key=RequiredValue}';
				}
			}
		});

	$('.scheduleName').editable({
		url: updateUrl + '{ManageResourcesActions::ActionChangeSchedule}', source: [
		{foreach from=$Schedules item=scheduleName key=scheduleId}
			{
				value:{$scheduleId}, text: "{$scheduleName|escape:'javascript'}"
			},
		{/foreach}
	]
	});

	$('.resourceTypeName').editable({
		url: updateUrl + '{ManageResourcesActions::ActionChangeResourceType}',
		emptytext: "{translate key=NoResourceTypeLabel|escape:'javascript'}",
		source: [{
				value: '0', text: '' //'-- {translate key=None} --'
			},
			{foreach from=$ResourceTypes item=resourceType key=id}
				{
					value:{$id}, text: "{$resourceType->Name()|escape:'javascript'}"
				},
			{/foreach}
		]
	});

	$('.sortOrderValue').editable({
		url: updateUrl + '{ManageResourcesActions::ActionChangeSort}', emptytext: '0', min: 0, max: 999
	});

	$('.locationValue').editable({
		url: updateUrl + '{ManageResourcesActions::ActionChangeLocation}', emptytext: "{translate key='NoLocationLabel'|escape:'javascript'}"
	});

	$('.contactValue').editable({
		url: updateUrl + '{ManageResourcesActions::ActionChangeContact}',
		{if $ResourceContactIsUser}
			emptytext: "{translate key='NoContactLabel'|escape:'javascript'}",
			source: xUserAutocompleteUrl,
		{else}
			emptytext: "{translate key='NoContactLabel'|escape:'javascript'}"
		{/if}
	});

	$('.descriptionValue').editable({
		url: updateUrl + '{ManageResourcesActions::ActionChangeDescription}',
		emptytext: "{translate key='NoDescriptionLabel'|escape:'javascript'}"
	});

	$('.notesValue').editable({
		url: updateUrl + '{ManageResourcesActions::ActionChangeNotes}', emptytext: "{translate key='NoDescriptionLabel'|escape:'javascript'}"
	});

	$('.resourceAdminValue').editable({
		url: updateUrl + '{ManageResourcesActions::ActionChangeAdmin}', emptytext: "{translate key=None|escape:'javascript'}", source: [{
		value: '0',
		text: ''
	},
	{foreach from=$AdminGroups item=group key=scheduleId}
		{
			value:{$group->Id()}, text: "{$group->Name()|escape:'javascript'}"
		},
	{/foreach}
	]
	});

	$('.inlineAttribute').editable({
		url: updateUrl + '{ManageResourcesActions::ActionChangeAttribute}', emptytext: '-'
	});

	}

	$(document).ready(function() {
		addTrumbowygType();
		setUpPopovers();
		setUpEditables();
		setupCustomAttributesIcon();

		dropzone($("#addResourceImage"));
		dropzone($("#changeResourceImage"), {
			autoSubmit: true
		});

		var actions = {
			enableSubscription: '{ManageResourcesActions::ActionEnableSubscription}',
			disableSubscription: '{ManageResourcesActions::ActionDisableSubscription}'
		};

		var opts = {
			submitUrl: '{$smarty.server.SCRIPT_NAME}',
			saveRedirect: '{$smarty.server.SCRIPT_NAME}',
			actions: actions,
			userAutocompleteUrl: "../ajax/autocomplete.php?type={AutoCompleteType::User}",
			groupAutocompleteUrl: "../ajax/autocomplete.php?type={AutoCompleteType::Group}",
			permissionsUrl: '{$smarty.server.SCRIPT_NAME}',
			copyText: "{translate key=Copy|escape:'javascript'}"
		};

		var resourceManagement = new ResourceManagement(opts);

		{foreach from=$Resources item=resource}
			var resource = {
				id: '{$resource->GetResourceId()}',
				name: "{$resource->GetName()|escape:'javascript'}",
				location: "{$resource->GetLocation()|escape:'javascript'}",
				contact: "{$resource->GetContact()|escape:'javascript'}",
				description: "{$resource->GetDescription()|escape:'javascript'}",
				notes: "{$resource->GetNotes()|escape:'javascript'}",
				autoAssign: '{$resource->GetAutoAssign()}',
				requiresApproval: '{$resource->GetRequiresApproval()}',
				allowMultiday: '{$resource->GetAllowMultiday()}',
				maxParticipants: '{$resource->GetMaxParticipants()}',
				scheduleId: '{$resource->GetScheduleId()}',
				minLength: {},
				maxLength: {},
				startNoticeAdd: {},
				startNoticeUpdate: {},
				startNoticeDelete: {},
				endNotice: {},
				bufferTime: {},
				adminGroupId: '{$resource->GetAdminGroupId()}',
				sortOrder: '{$resource->GetSortOrder()}',
				resourceTypeId: '{$resource->GetResourceTypeId()}',
				statusId: '{$resource->GetStatusId()}',
				reasonId: '{$resource->GetStatusReasonId()}',
				allowSubscription: '{$resource->GetIsCalendarSubscriptionAllowed()}',
				enableCheckin: '{$resource->IsCheckInEnabled()}',
				autoReleaseMinutes: '{$resource->GetAutoReleaseMinutes()}',
				credits: '{$resource->GetCreditsPerSlot()}',
				peakCredits: '{$resource->GetPeakCreditsPerSlot()}',
				allowConcurrent: '{$resource->GetAllowConcurrentReservations()}',
				maxConcurrent: '{$resource->GetMaxConcurrentReservations()}'
			};

			{if $resource->HasMinLength()}
				resource.minLength = {
					value: '{$resource->GetMinLength()}',
					days: '{$resource->GetMinLength()->Days()}',
					hours: '{$resource->GetMinLength()->Hours()}',
					minutes: '{$resource->GetMinLength()->Minutes()}'
				};
			{/if}

			{if $resource->HasMaxLength()}
				resource.maxLength = {
					value: '{$resource->GetMaxLength()}',
					days: '{$resource->GetMaxLength()->Days()}',
					hours: '{$resource->GetMaxLength()->Hours()}',
					minutes: '{$resource->GetMaxLength()->Minutes()}'
				};
			{/if}

			{if $resource->HasMinNoticeAdd()}
				resource.startNoticeAdd = {
					value: '{$resource->GetMinNoticeAdd()}',
					days: '{$resource->GetMinNoticeAdd()->Days()}',
					hours: '{$resource->GetMinNoticeAdd()->Hours()}',
					minutes: '{$resource->GetMinNoticeAdd()->Minutes()}'
				};
			{/if}

			{if $resource->HasMinNoticeUpdate()}
				resource.startNoticeUpdate = {
					value: '{$resource->GetMinNoticeUpdate()}',
					days: '{$resource->GetMinNoticeUpdate()->Days()}',
					hours: '{$resource->GetMinNoticeUpdate()->Hours()}',
					minutes: '{$resource->GetMinNoticeUpdate()->Minutes()}'
				};
			{/if}

			{if $resource->HasMinNoticeDelete()}
				resource.startNoticeDelete = {
					value: '{$resource->GetMinNoticeDelete()}',
					days: '{$resource->GetMinNoticeDelete()->Days()}',
					hours: '{$resource->GetMinNoticeDelete()->Hours()}',
					minutes: '{$resource->GetMinNoticeDelete()->Minutes()}'
				};
			{/if}

			{if $resource->HasMaxNotice()}
				resource.endNotice = {
					value: '{$resource->GetMaxNotice()}',
					days: '{$resource->GetMaxNotice()->Days()}',
					hours: '{$resource->GetMaxNotice()->Hours()}',
					minutes: '{$resource->GetMaxNotice()->Minutes()}'
				};
			{/if}

			{if $resource->HasBufferTime()}
				resource.bufferTime = {
					value: '{$resource->GetBufferTime()}',
					days: '{$resource->GetBufferTime()->Days()}',
					hours: '{$resource->GetBufferTime()->Hours()}',
					minutes: '{$resource->GetBufferTime()->Minutes()}'
				};
			{/if}

			resource.image = null;
			{if ($resource->HasImage())}
				resource.image = '{resource_image image=$resource->GetImage()}';
			{/if}

			resource.images = [];
			{foreach from=$resource->GetImages() item=image}
				resource.images.push('{resource_image image=$image}');
			{/foreach}

			resource.resourceGroupIds = [{$resource->GetResourceGroupIds()|join:','}];

			resourceManagement.add(resource);
		{/foreach}

		{foreach from=$StatusReasons item=reason}
			resourceManagement.addStatusReason('{$reason->Id()}', '{$reason->StatusId()}', '{$reason->Description()|escape:javascript}');
		{/foreach}

		resourceManagement.init();
		resourceManagement.initializeStatusFilter('{$ResourceStatusFilterId}', '{$ResourceStatusReasonFilterId}');
		resourceManagement.addResourceGroups({$ResourceGroups});

	});
</script>
</div>

{include file='globalfooter.tpl'}
