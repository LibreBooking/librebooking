{include file='globalheader.tpl' InlineEdit=true DataTable=true}

<div id="page-manage-reservations" class="admin-page">
	<div class="clearfix border-bottom mb-3">
		<div class="dropdown admin-header-more float-end">
			<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="moreReservationActions"
				data-bs-toggle="dropdown">
				<i class="bi bi-three-dots"></i>
				<span class="visually-hidden">Expand</span>
			</button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="moreReservationActions">
				{if $CanViewAdmin}
					<li role="presentation">
						<a role="menuitem" href="#" id="import-reservations" class="add-link dropdown-item">
							<i class="bi bi-box-arrow-in-down"></i>
							{translate key=Import}
						</a>
					</li>
				{/if}
				<li role="presentation">
					<a role="menuitem" href="{$CsvExportUrl}" download="{$CsvExportUrl}" class="add-link dropdown-item"
						target="_blank">
						<i class="bi bi-box-arrow-in-up"></i>
						{translate key=Export} CVS
					</a>
				</li>
				{if $CanViewAdmin}
					<li role="separator" class="divider"></li>
					<li role="presentation">
						<a role="menuitem" href="#" id="addTermsOfService" class="add-link dropdown-item">
							<i class="bi bi-journals"></i>
							{translate key=TermsOfService}
						</a>
					</li>
					<li role="presentation">
						<a role="menuitem" href="manage_reservation_colors.php" id="addTermsOfService"
							class="add-link dropdown-item">
							<i class="bi bi-brush"></i>
							{translate key=ReservationColors}
						</a>
					</li>
				{/if}
			</ul>
		</div>
		<h1 class="float-start">{translate key=ManageReservations}</h1>
	</div>

	<div class="accordion">
		<div class="filterTable accordion-item shadow mb-2" id="filter-reservations-panel">
			<h2 class="accordion-header">
				<button class="accordion-button link-primary fw-bold" type="button" data-bs-toggle="collapse"
					data-bs-target="#filter-reservations-content" aria-expanded="true"
					aria-controls="filter-reservations-content">
					<i class="bi bi-funnel-fill me-1"></i>{translate key="Filter"}
				</button>
			</h2>
			<div id="filter-reservations-content" class="accordion-collapse collapse show">
				<div class="accordion-body">
					{assign var=groupClass value="col-12 col-sm-4 col-md-3"}
					<form id="filterForm" role="form" class="row gy-2">
						<div
							class="form-group filter-dates {$groupClass} d-flex justify-content-between align-items-center">
							<div>
								<label for="startDate" class="fw-bold">{translate key='BeginDate'}</label>
								<input id="startDate" type="date" class="form-control form-control-sm dateinput inline"
									value="{formatdate date=$StartDate format='Y-m-d'}" />
								<input id="formattedStartDate" type="hidden"
									value="{formatdate date=$StartDate key=system}" />
							</div>
							<div>
								<label for="endDate" class="fw-bold">{translate key='EndDate'}</label>
								<input id="endDate" type="date" class="form-control form-control-sm dateinput inline"
									value="{formatdate date=$EndDate format='Y-m-d'}" />
								<input id="formattedEndDate" type="hidden"
									value="{formatdate date=$EndDate key=system}" />
							</div>
						</div>
						<div class="form-group filter-user {$groupClass}">
							<label for="userFilter" class="fw-bold">{translate key=User}</label>
							<div class="position-relative">
								<input id="userFilter" type="text" class="form-control form-control-sm"
									value="{$UserNameFilter}" placeholder="{translate key=User}" />
								<input id="userId" type="hidden" value="{$UserIdFilter}" />
								<span class="searchclear bi bi-x-circle-fill" ref="userFilter,userId"></span>
							</div>
						</div>
						<div class="form-group filter-schedule {$groupClass}">
							<label for="scheduleId" class="fw-bold">{translate key=Schedule}</label>
							<select id="scheduleId" class="form-select form-select-sm">
								<option value="">{translate key=AllSchedules}</option>
								{object_html_options options=$Schedules key='GetId' label="GetName" selected=$ScheduleId}
							</select>
						</div>
						<div class="form-group filter-resource {$groupClass}">
							<label for="resourceId" class="fw-bold">{translate key=Resource}</label>
							<select id="resourceId" class="form-select form-select-sm">
								<option value="">{translate key=AllResources}</option>
								{object_html_options options=$Resources key='GetId' label="GetName" selected=$ResourceId}
							</select>
						</div>
						<div class="form-group filter-status {$groupClass}">
							<label for="statusId" class="fw-bold">{translate key=Status}</label>
							<select id="statusId" class="form-select form-select-sm">
								<option value="">{translate key=AllReservations}</option>
								<option value="{ReservationStatus::Pending}"
									{if $ReservationStatusId eq ReservationStatus::Pending}selected="selected" {/if}>
									{translate key=PendingReservations}</option>
							</select>
						</div>
						<div class="form-group filter-referenceNumber {$groupClass}">
							<label for="referenceNumber" class="fw-bold">{translate key=ReferenceNumber}</label>
							<div class="position-relative">
								<input id="referenceNumber" type="text" class="form-control form-control-sm"
									value="{$ReferenceNumber}" placeholder="{translate key=ReferenceNumber}" />
								<span class="searchclear bi bi-x-circle-fill" ref="referenceNumber"></span>
							</div>
						</div>
						<div class="form-group filter-title {$groupClass}">
							<label for="reservationTitle" class="fw-bold">{translate key=Title}</label>
							<div class="position-relative">
								<input id="reservationTitle" type="text" class="form-control form-control-sm"
									value="{$ReservationTitle}" placeholder="{translate key=Title}" />
								<span class="searchclear bi bi-x-circle-fill" ref="reservationTitle"></span>
							</div>
						</div>
						<div class="form-group filter-title {$groupClass}">
							<label for="reservationDescription"
								class="fw-bold">{translate key=ReservationDescription}</label>
							<div class="position-relative">
								<input id="reservationDescription" type="text" class="form-control form-control-sm"
									value="{$ReservationDescription}" placeholder="{translate key=Description}" />
								<span class="searchclear bi bi-x-circle-fill" ref="reservationDescription"></span>
							</div>
						</div>
						<div class="form-group filter-resourceStatus {$groupClass}">
							<label for="resourceStatusIdFilter" class="fw-bold">{translate key=ResourceStatus}</label>
							<select id="resourceStatusIdFilter" class="form-select form-select-sm">
								<option value="">{translate key=AllResourceStatuses}</option>
								<option value="{ResourceStatus::AVAILABLE}">{translate key=Available}</option>
								<option value="{ResourceStatus::UNAVAILABLE}">{translate key=Unavailable}</option>
								<option value="{ResourceStatus::HIDDEN}">{translate key=Hidden}</option>
							</select>
						</div>
						<div class="form-group filter-resourceStatusReason {$groupClass}">
							<label for="resourceReasonIdFilter" class="fw-bold">{translate key=Reason}</label>
							<select id="resourceReasonIdFilter" class="form-select form-select-sm"></select>
						</div>
						<div class="form-group filter-checkin {$groupClass} d-flex align-items-center">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="missedCheckin"
									{if $MissedCheckin}checked="checked" {/if} />
								<label class="form-check-label"
									for="missedCheckin">{translate key=MissedCheckin}</label>
							</div>
						</div>
						<div class="form-group filter-checkout {$groupClass} d-flex align-items-center">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="missedCheckout"
									{if $MissedCheckout}checked="checked" {/if} />
								<label class="form-check-label"
									for="missedCheckout">{translate key=MissedCheckout}</label>
							</div>
						</div>
						{foreach from=$AttributeFilters item=attribute}
							{control type="AttributeControl" attribute=$attribute searchmode=true class="customAttribute filter-customAttribute{$attribute->Id()}
						{$groupClass}"}
						{/foreach}
					</form>

					<div class="accordion-footer border-top mt-3 pt-3">
						{filter_button id="filter" class="btn-sm"}
						{reset_button id="clearFilter" class="btn-sm"}
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card shadow">
		<div class="card-body">
			<div class="table-responsive">
				{assign var=tableId value=reservationTable}
				<table class="table table-striped table-hover border-top w-100 admin-panel" id="{$tableId}">
					{assign var=colCount value=11}
					<thead>
						<tr>
							<th class="id d-none">ID</th>
							<th>{translate key='User'}</th>
							<th>{translate key='Resource'}</th>
							<th>{translate key='Title'}</th>
							<th>{translate key='Description'}</th>
							<th>{translate key='BeginDate'}</th>
							<th>{translate key='EndDate'}</th>
							<th>{translate key='Duration'}</th>
							<th>{translate key='ReferenceNumber'}</th>
							{if $CreditsEnabled}
								<th>{translate key='Credits'}</th>
								{assign var=colCount value=$colCount+1}
							{/if}
							{if !$IsDesktop}
								<th class="action">{translate key='Edit'}</th>
								{assign var=colCount value=$colCount+1}
							{/if}
							<th class="action">{translate key='Approve'}</th>
							<th class="action">{translate key='Delete'}</th>
							<th class="action">
								<div class="form-check checkbox-single">
									<input type="checkbox" id="delete-all" aria-label="{translate key=All}"
										class="form-check-input" title="{translate key=All}" />
									<label for="delete-all"></label>
								</div>
								<div class="action-delete">
									<a href="#" id="delete-selected" class="d-none" title="{translate key=Delete}">
										<span class="bi bi-trash3-fill text-danger icon remove"></span>
										<span class="visually-hidden">{translate key=Delete}</span>
									</a>
								</div>
							</th>
							<th>{translate key='More'}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$reservations item=reservation}
							{if $reservation->RequiresApproval}
								{assign var=rowCss value='table-warning pending'}
							{/if}
							{assign var=reservationId value=$reservation->ReservationId}
							<tr class="{$rowCss} {if $IsDesktop}editable{/if}" data-seriesId="{$reservation->SeriesId}"
								data-refnum="{$reservation->ReferenceNumber}" data-bs-custom-class="respopup-tooltip"
								data-bs-html="true">
								<td class="id d-none">{$reservationId}</td>
								<td class="user">
									{fullname first=$reservation->FirstName|unescape:'html' last=$reservation->LastName|unescape:'html' ignorePrivacy=true}
								</td>
								<td class="resource">{$reservation->ResourceName}
									{if $reservation->ResourceStatusId == ResourceStatus::AVAILABLE}
										<i class="bi bi-check-circle-fill text-success"></i>
									{elseif $reservation->ResourceStatusId == ResourceStatus::UNAVAILABLE}
										<i class="bi bi-exclamation-circle-fill text-warning"></i>
									{else}
										<i class="bi bi-x-circle-fill text-danger"></i>
									{/if}
									{*{if array_key_exists($reservation->ResourceStatusReasonId,$StatusReasons)}*}
										{*<span class="reservationResourceStatusReason">{$StatusReasons[$reservation->ResourceStatusReasonId]->Description()}</span>*}
									{*{/if}*}
								</td>
								<td class="reservationTitle">{$reservation->Title|escape:'html'}</td>
								<td class="description">{$reservation->Description|escape:'html'}</td>
								<td class="date">
									{formatdate date=$reservation->StartDate timezone=$Timezone key=short_reservation_date}
								</td>
								<td class="date">
									{formatdate date=$reservation->EndDate timezone=$Timezone key=short_reservation_date}
								</td>
								<td class="duration">{$reservation->GetDuration()->__toString()}</td>
								<td class="referenceNumber">{$reservation->ReferenceNumber}</td>
								{if $CreditsEnabled}
									<td class="credits">{$reservation->CreditsConsumed}</td>
								{/if}
								{if !$IsDesktop}
									<td class="action">
										<a href="#" class="update edit link-primary"><i class="bi bi-pencil-fill fs-6"></i></a>
									</td>
								{/if}
								<td class="action">
									{if $reservation->RequiresApproval}
										<a href="#" class="update approve link-success"><i class="bi bi-check-lg fs-4"></i></a>
									{else}
										-
									{/if}
								</td>
								<td class="action">
									<a href="#" class="update delete">
										<span class="bi bi-trash3-fill text-danger icon remove fs-5"></span>
										<span class="visually-hidden">{translate key=Delete}</span>
									</a>
								</td>
								<td class="action no-edit">
									<div class="form-check checkbox-single">
										<input {formname key=RESERVATION_ID multi=true}
											class="delete-multiple form-check-input" type="checkbox"
											id="delete{$reservationId}" value="{$reservationId}"
											aria-label="{translate key=Delete}" title="{translate key=Delete}" />
										<label class="" for="delete{$reservationId}"></label>
									</div>
								</td>
								<td>
									<div class="reservation-list-dates d-flex">
										<div>
											<label class="fw-bold">{translate key='Created'}</label>
											{formatdate date=$reservation->CreatedDate timezone=$Timezone key=short_datetime}
										</div>
										<div>
											<label class="fw-bold">{translate key='LastModified'}</label>
											{formatdate date=$reservation->ModifiedDate timezone=$Timezone key=short_datetime}
										</div>
										<div>
											<label class="fw-bold">{translate key='CheckInTime'}</label>
											{formatdate date=$reservation->CheckinDate timezone=$Timezone key=short_datetime}
										</div>
										<div>
											<label class="fw-bold">{translate key='CheckOutTime'}</label>
											{formatdate date=$reservation->CheckoutDate timezone=$Timezone key=short_datetime}
										</div>
										<div>
											<label class="fw-bold">{translate key='OriginalEndDate'}</label>
											{formatdate date=$reservation->OriginalEndDate timezone=$Timezone key=short_datetime}
										</div>
									</div>
									{if $ReservationAttributes|default:array()|count > 0}
										<div class="reservation-list-attributes d-flex">
											{foreach from=$ReservationAttributes item=attribute}
												{include file='Admin/InlineAttributeEdit.tpl' id=$reservation->ReferenceNumber attribute=$attribute value=$reservation->Attributes->Get($attribute->Id()) url="{$smarty.server.SCRIPT_NAME}?action={ManageReservationsActions::UpdateAttribute}"}
											{/foreach}
										</div>
									{/if}

								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>

			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteInstanceDialog" tabindex="-1" role="dialog"
		aria-labelledby="deleteInstanceDialogLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form id="deleteInstanceForm" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteInstanceDialogLabel">{translate key=Delete}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="delResResponse"></div>
						<div class="alert alert-warning">
							{translate key=DeleteWarning}
						</div>

						<input type="hidden" {formname key=SERIES_UPDATE_SCOPE}
							value="{SeriesUpdateScope::ThisInstance}" />
						<input type="hidden" {formname key=REFERENCE_NUMBER} value="" class="referenceNumber" />
					</div>
					<div class="modal-footer">
						{cancel_button}
						{delete_button}
						{indicator}
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="deleteSeriesDialog" tabindex="-1" role="dialog"
		aria-labelledby="deleteSeriesDialogLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form id="deleteSeriesForm" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="deleteSeriesDialogLabel">{translate key=Delete}</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-warning">
							{translate key=DeleteWarning}
						</div>
						<input type="hidden" id="hdnSeriesUpdateScope" {formname key=SERIES_UPDATE_SCOPE} />
						<input type="hidden" {formname key=REFERENCE_NUMBER} value="" class="referenceNumber" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary cancel"
							data-bs-dismiss="modal">{translate key='Cancel'}</button>

						<button type="button" class="btn btn-danger saveSeries btnUpdateThisInstance"
							id="btnUpdateThisInstance">
							{translate key='ThisInstance'}
						</button>
						<button type="button" class="btn btn-danger saveSeries btnUpdateAllInstances"
							id="btnUpdateAllInstances">
							{translate key='AllInstances'}
						</button>
						<button type="button" class="btn btn-danger saveSeries btnUpdateFutureInstances"
							id="btnUpdateFutureInstances">
							{translate key='FutureInstances'}
						</button>
						{indicator}
					</div>
				</div>
			</form>
		</div>
	</div>

	<div id="deleteMultipleDialog" class="modal fade" tabindex="-1" role="dialog"
		aria-labelledby="deleteMultipleModalLabel" aria-hidden="true">
		<form id="deleteMultipleForm" method="post" ajaxAction="{ManageReservationsActions::DeleteMultiple}">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="deleteMultipleModalLabel">{translate key=Delete} (<span
								id="deleteMultipleCount"></span>)</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-warning">
							<div>{translate key=DeleteWarning}</div>

							<div>{translate key=DeleteMultipleReservationsWarning}</div>
						</div>

					</div>
					<div class="modal-footer">
						{cancel_button}
						{delete_button}
						{indicator}
					</div>
					<div id="deleteMultiplePlaceHolder" class="d-none"></div>
				</div>
			</div>
		</form>
	</div>

	<div id="inlineUpdateErrorDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="inlineErrorLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="inlineErrorLabel">{translate key=Error}</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div id="inlineUpdateErrors" class="hidden error">&nbsp;</div>
					<div id="reservationAccessError" class="hidden error"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary cancel"
						data-bs-dismiss="modal">{translate key='OK'}</button>
				</div>
			</div>
		</div>
	</div>

	<div id="importReservationsDialog" class="modal" tabindex="-1" role="dialog"
		aria-labelledby="importReservationsModalLabel" aria-hidden="true">
		<form id="importReservationsForm" class="form" role="form" method="post" enctype="multipart/form-data"
			ajaxAction="{ManageReservationsActions::Import}">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="importReservationsModalLabel">{translate key=Import}</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div id="importInstructions" class="alert alert-info">
							<div class="note">{translate key=ReservationImportInstructions}</div>
							<a href="{$smarty.server.SCRIPT_NAME}?dr=template" class="alert-link"
								download="{$smarty.server.SCRIPT_NAME}?dr=template"
								target="_blank">{translate key=GetTemplate}<span class="bi bi-download ms-1"></span></a>
						</div>
						<div id="importUserResults" class="validationSummary alert alert-danger d-none">
							<ul>
								{async_validator id="fileExtensionValidator" key=""}
							</ul>
						</div>
						<div id="importErrors" class="alert alert-danger d-none"></div>
						<div id="importResult" class="alert alert-success d-none">
							<span>{translate key=RowsImported}</span>

							<span id="importCount" class="inline fw-bold">0</span>
							<span>{translate key=RowsSkipped}</span>

							<span id="importSkipped" class="inline fw-bold">0</span>
							<a class="alert-link" href="{$smarty.server.SCRIPT_NAME}">{translate key=Done}<i
									class="bi bi-arrow-repeat ms-1"></i></a>
						</div>
						<div class="">
							<label for="reservationImportFile" class="visually-hidden">{translate key=File}</label>
							<input type="file" {formname key=RESERVATION_IMPORT_FILE} id="reservationImportFile"
								class="form-control" accept=".csv" />
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

	<div class="modal fade" id="termsOfServiceDialog" tabindex="-1" role="dialog"
		aria-labelledby="termsOfServiceDialogLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form id="termsOfServiceForm" method="post" ajaxAction="termsOfService" enctype="multipart/form-data">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="termsOfServiceDialogLabel">{translate key=TermsOfService}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div>
							<div class="form-check form-check-inline">
								<input type="radio" {formname key=TOS_METHOD} value="manual" id="tos_manual_radio"
									checked="checked" data-ref="tos_manual_div" class="toggle form-check-input">
								<label for="tos_manual_radio">{translate key=EnterTermsManually}</label>
							</div>
							<div class="form-check form-check-inline">
								<input type="radio" {formname key=TOS_METHOD} value="url" id="tos_url_radio"
									data-ref="tos_url_div" class="toggle form-check-input">
								<label for="tos_url_radio">{translate key=LinkToTerms}</label>
							</div>
							<div class="form-check form-check-inline">
								<input type="radio" {formname key=TOS_METHOD} value="upload" id="tos_upload_radio"
									data-ref="tos_upload_div" class="toggle form-check-input">
								<label for="tos_upload_radio">{translate key=UploadTerms}</label>
							</div>
						</div>
						<div id="tos_manual_div" class="tos-div">
							<div class="form-group">
								<label for="tos-manual" class="fw-bold">{translate key=TermsOfService}</label>
								<textarea id="tos-manual" class="form-control w-100" rows="10"
									{formname key=TOS_TEXT}></textarea>
							</div>
						</div>
						<div id="tos_url_div" class="tos-div d-none">
							<div class="form-group">
								<label for="tos-url" class="fw-bold">{translate key=LinkToTerms}</label>
								<input type="url" id="tos-url" class="form-control"
									placeholder="http://www.example.com/tos.html" {formname key=TOS_URL}
									maxlength="255" />
							</div>
						</div>
						<div id="tos_upload_div" class="tos-div d-none margin-bottom-15">
							<label for="tos-upload" class="fw-bold">{translate key=TermsOfService} PDF</label>
							<div class="dropzone text-center border border-2 rounded-3 bg-light"
								id="termsOfServiceUpload">
								<div class="">
									<i class="bi bi-filetype-pdf fs-1"></i><br />
									{translate key=ChooseOrDropFile}
								</div>
								<input id="tos-upload" type="file" {formname key=TOS_UPLOAD} accept="application/pdf" />
							</div>
							<div id="tos-upload-link" class="d-none">
								<a href="{$ScriptUrl}/uploads/tos/tos.pdf" target="_blank" class="link-primary"><span
										class="bi bi-filetype-pdf me-1"></span> {translate key=ViewTerms}
								</a>
							</div>
						</div>
						<div class="mt-3">
							<div>{translate key=RequireTermsOfServiceAcknowledgement}</div>
							<div class="form-check form-check-inline">
								<input type="radio" {formname key=TOS_APPLICABILITY} class="form-check-input"
									value="{TermsOfService::RESERVATION}" id="tos_reservation" checked="checked">
								<label for="tos_reservation"
									class="form-check-label">{translate key=UponReservation}</label>
							</div>
							<div class="form-check form-check-inline">
								<input type="radio" {formname key=TOS_APPLICABILITY} class="form-check-input"
									value="{TermsOfService::REGISTRATION}" id="tos_registration">
								<label for="tos_registration"
									class="form-check-label">{translate key=UponRegistration}</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{delete_button id='deleteTerms' class='d-none'}
						{update_button submit=true}
						{indicator}
					</div>
				</div>
			</form>
		</div>
	</div>

	{include file="javascript-includes.tpl" InlineEdit=true DataTable=true}
	{datatable tableId=$tableId}
	{jsfile src="ajax-helpers.js"}
	{jsfile src="admin/reservations.js"}

	{jsfile src="autocomplete.js"}
	{jsfile src="reservationPopup.js"}
	{jsfile src="approval.js"}
	{jsfile src="dropzone.js"}

	<script type="text/javascript">
		/*function hidePopoversWhenClickAway() {
			$('body').on('click', function(e) {
				$('[rel="popover"]').each(function() {
					if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e
							.target).length === 0) {
						$(this).popover('hide');
					}
				});
			});
		}*/

		function setUpPopovers() {
			var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
			var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl);
			});
		}

		function setUpEditables() {
			$.fn.editable.defaults.mode = 'popup';
			$.fn.editable.defaults.toggle = 'manual';
			$.fn.editable.defaults.emptyclass = '';
			$.fn.editable.defaults.params = function(params) {
				params.CSRF_TOKEN = $('#csrf_token').val();
				return params;
			};

			var updateUrl = '{$smarty.server.SCRIPT_NAME}?action=';

			$('.inlineAttribute').editable({
				url: updateUrl + '{ManageReservationsActions::UpdateAttribute}', emptytext: '-'
			});
		}

		$(document).ready(function() {

			setUpPopovers();
			//hidePopoversWhenClickAway();
			setUpEditables();
			dropzone($("#termsOfServiceUpload"));

			var updateScope = {};
			updateScope['btnUpdateThisInstance'] = '{SeriesUpdateScope::ThisInstance}';
			updateScope['btnUpdateAllInstances'] = '{SeriesUpdateScope::FullSeries}';
			updateScope['btnUpdateFutureInstances'] = '{SeriesUpdateScope::FutureInstances}';

			var actions = {};

			var resOpts = {
				autocompleteUrl: "{$Path}ajax/autocomplete.php?type={AutoCompleteType::User}",
				reservationUrlTemplate: "{$Path}reservation.php?{QueryStringKeys::REFERENCE_NUMBER}=[refnum]",
				popupUrl: "{$Path}ajax/respopup.php",
				updateScope: updateScope,
				actions: actions,
				deleteUrl: '{$Path}ajax/reservation_delete.php?{QueryStringKeys::RESPONSE_TYPE}=json',
				resourceStatusUrl: '{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}=changeStatus',
				submitUrl: '{$smarty.server.SCRIPT_NAME}',
				termsOfServiceUrl: '{$smarty.server.SCRIPT_NAME}?dr=tos',
				updateTermsOfServiceAction: 'termsOfService',
				deleteTermsOfServiceAction: 'deleteTerms'
			};

			var approvalOpts = {
				url: '{$Path}ajax/reservation_approve.php'
			};

			var approval = new Approval(approvalOpts);

			var reservationManagement = new ReservationManagement(resOpts, approval);
			reservationManagement.init();

			{foreach from=$reservations item=reservation}

				reservationManagement.addReservation({
					id: '{$reservation->ReservationId}',
					referenceNumber: '{$reservation->ReferenceNumber}',
					isRecurring: '{$reservation->IsRecurring}',
					resourceStatusId: '{$reservation->ResourceStatusId}',
					resourceStatusReasonId: '{$reservation->ResourceStatusReasonId}',
					resourceId: '{$reservation->ResourceId}'
				});
			{/foreach}

			{foreach from=$StatusReasons item=reason}
				reservationManagement.addStatusReason('{$reason->Id()}', '{$reason->StatusId()}', '{$reason->Description()|escape:javascript}');
			{/foreach}

			reservationManagement.initializeStatusFilter('{$ResourceStatusFilterId}', '{$ResourceStatusReasonFilterId}');
		});
	</script>

	{control type="DatePickerSetupControl" ControlId="startDate" AltId="formattedStartDate"}
	{control type="DatePickerSetupControl" ControlId="endDate" AltId="formattedEndDate"}

	{csrf_token}

	<div class="modal fade" id="approveDiv" tabindex="-1" role="dialog" aria-labelledby="approveDivLabel"
		data-bs-backdrop="static" aria-hidden="true">
		{include file="wait-box.tpl" translateKey='Approving'}
	</div>

</div>
{jsfile src="search-clear.js"}
{include file='globalfooter.tpl'}
