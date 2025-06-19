{include file='globalheader.tpl' Timepicker=true DataTable=true}
<div id="page-manage-blackouts" class="admin-page">
	<h1 class="border-bottom mb-3">{translate key=ManageBlackouts}</h1>
	<div class="accordion">
		<form id="addBlackoutForm" role="form" method="post">
			<div class="accordion-item shadow mb-2 panel-default" id="add-blackout-panel">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed link-primary" type="button" data-bs-toggle="collapse"
						data-bs-target="#add-blackout-content" aria-expanded="false"
						aria-controls="add-blackout-content">
						<i class="bi bi-plus-circle-fill me-1"></i>{translate key="AddBlackout"}
					</button>
				</h2>
				<div id="add-blackout-content" class="accordion-collapse collapse">
					<div class="accordion-body add-contents">
						<div class="form-group d-flex align-items-center flex-wrap gap-1 mb-2">
							<div class="d-flex align-items-center flex-wrap gap-1 me-sm-4">
								<label class="fw-bold" for="addStartDate">{translate key=BeginDate}</label>
								<input type="date" id="addStartDate" class="form-control form-control-sm me-1 dateinput"
									value="{formatdate date=$AddStartDate format='Y-m-d'}" />
								<input {formname key=BEGIN_DATE} id="formattedAddStartDate" type="hidden"
									value="{formatdate date=$AddStartDate key=system}" />
								<input {formname key=BEGIN_TIME} type="text" id="addStartTime"
									class="form-select form-select-sm dateinput timepicker"
									value="{format_date format='h:00 A' date=now}" title="{translate key=StartTime}" />
								<label for="addStartTime" class="visually-hidden">{translate key=StartTime}</label>
							</div>
							<div class="d-flex align-items-center flex-wrap gap-1">
								<label class="fw-bold" for="addEndDate">{translate key=EndDate}</label>
								<input type="date" id="addEndDate" class="form-control form-control-sm me-1 dateinput"
									size="10" value="{formatdate date=$AddEndDate format='Y-m-d'}" />
								<input {formname key=END_DATE} type="hidden" id="formattedAddEndDate"
									value="{formatdate date=$AddEndDate key=system}" />
								<input {formname key=END_TIME} type="text" id="addEndTime"
									class="form-select form-select-sm dateinput timepicker"
									value="{format_date format='h:00 A' date=Date::Now()->AddHours(1)}"
									title="{translate key=EndTime}" />
								<label for="addEndTime" class="visually-hidden">{translate key=EndTime}</label>
							</div>
						</div>
						<div class="form-group d-flex align-items-center flex-wrap gap-1 mb-2">
							<label class="fw-bold" for="addResourceId">{translate key=Resource}</label>
							<select {formname key=RESOURCE_ID} class="form-select form-select-sm w-auto"
								id="addResourceId">
								{object_html_options options=$Resources key='GetId' label="GetName" selected=$ResourceId}
							</select>
							{if $Schedules|default:array()|count > 0}
								<div class="vr mx-2"></div>
								<div class="form-check me-2">
									<input class="form-check-input" {formname key=BLACKOUT_APPLY_TO_SCHEDULE}
										type="checkbox" id="allResources" />
									<label class="form-check-label" for="allResources"
										style="">{translate key=AllResourcesOn}
									</label>
								</div>
								<label for="addScheduleId" class="visually-hidden">{translate key=Schedule} </label>
								<select {formname key=SCHEDULE_ID} id="addScheduleId"
									class="form-select form-select-sm w-auto" disabled="disabled"
									title="{translate key=Schedule}">
									{object_html_options options=$Schedules key='GetId' label="GetName" selected=$ScheduleId}
								</select>
							{/if}
						</div>
						<div class="form-group d-flex align-items-center gap-1 mb-2">
							<label class="fw-bold" for="blackoutReason">{translate key=Reason}<i
									class="bi bi-asterisk text-danger align-top" style="font-size: 0.5rem;"></i>
							</label>
							<input {formname key=SUMMARY} type="text" id="blackoutReason" required
								class="form-control form-control-sm w-auto required has-feedback" />
						</div>
						<div class="form-group mb-2">
							{control type="RecurrenceControl" RepeatTerminationDate=$RepeatTerminationDate}
						</div>
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input class="form-check-input" {formname key=CONFLICT_ACTION} type="radio"
									id="bookAround" name="existingReservations" checked="checked"
									value="{ReservationConflictResolution::BookAround}" />
								<label class="form-check-label"
									for="bookAround">{translate key=BlackoutAroundConflicts}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" {formname key=CONFLICT_ACTION} type="radio"
									id="notifyExisting" name="existingReservations"
									value="{ReservationConflictResolution::Notify}" />
								<label class="form-check-label"
									for="notifyExisting">{translate key=BlackoutShowMe}</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" {formname key=CONFLICT_ACTION} type="radio"
									id="deleteExisting" name="existingReservations"
									value="{ReservationConflictResolution::Delete}" />
								<label class="form-check-label"
									for="deleteExisting">{translate key=BlackoutDeleteConflicts}</label>
							</div>
						</div>

						<div class="accordion-footer border-top pt-3">
							{add_button class="btn-sm"}
							{reset_button class="btn-sm"}
						</div>
					</div>
				</div>
			</div>
		</form>

		<form role="form">
			<div class="accordion-item shadow mb-2 panel panel-default" id="filter-panel">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed link-primary" type="button" data-bs-toggle="collapse"
						data-bs-target="#filter-content" aria-expanded="false" aria-controls="filter-content">
						<i class="bi bi-funnel-fill me-1"></i>{translate key="Filter"}
					</button>
				</h2>
				<div id="filter-content" class="accordion-collapse collapse">
					<div class="accordion-body row gy-2">
						<div class="form-group col-12 col-sm-4">
							<div class="d-flex align-items-center justify-content-between flex-wrap">
								<div>
									<label for="startDate" class="fw-bold">{translate key=BeginDate}</label>
									<input id="startDate" type="date" class="form-control form-control-sm dateinput"
										value="{formatdate date=$StartDate format='Y-m-d'}" title="Between start date"
										placeholder="{translate key=BeginDate}" />
									<input id="formattedStartDate" type="hidden"
										value="{formatdate date=$StartDate key=system}" />
								</div>
								<div class="ms-1">
									<label for="endDate" class="fw-bold">{translate key=EndDate}</label>
									<input id="endDate" type="date" class="form-control form-control-sm dateinput"
										value="{formatdate date=$EndDate format='Y-m-d'}"
										placeholder="{translate key=EndDate}" />
									<input id="formattedEndDate" type="hidden"
										value="{formatdate date=$EndDate key=system}" />
								</div>
							</div>
						</div>
						<div class="form-group col-12 col-sm-4">
							<label for="scheduleId" class="fw-bold">{translate key=Schedule} </label>

							<select id="scheduleId" class="form-select form-select-sm col-12 col-sm-4">
								<option value="">{translate key=AllSchedules}</option>
								{object_html_options options=$Schedules key='GetId' label="GetName" selected=$ScheduleId}
							</select>
						</div>
						<div class="form-group col-12 col-sm-4 mb-2">
							<label for="resourceId" class="fw-bold">{translate key=Resource} </label>

							<select id="resourceId" class="form-select form-select-sm col-12 col-sm-4">
								<option value="">{translate key=AllResources}</option>
								{object_html_options options=$Resources key='GetId' label="GetName" selected=$ResourceId}
							</select>
						</div>
						<div class="accordion-footer border-top pt-3">
							{filter_button class="btn-sm" id="filter"}
							<button id="showAll" class="btn btn-outline-secondary btn-sm"><i
									class="bi bi-list-task me-1"></i>{translate key=ViewAll}</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="card shadow">
		<div class="card-body">
			<div class="table-responsive">
				{assign var=tableId value=blackoutTable}
				<table class="table table-striped table-hover border-top w-100" id="{$tableId}">
					<thead>
						<tr>
							<th>{translate key=Resource}</th>
							<th>{translate key=BeginDate}</th>
							<th>{translate key=EndDate}</th>
							<th>{translate key=Reason}</th>
							<th style="max-width:150px;">{translate key=CreatedBy}</th>
							<th>{translate key=Update}</th>
							<th>{translate key=Delete}</th>
							<th class="action-delete">
								<div class="checkbox checkbox-single form-check">
									<input class="form-check-input" type="checkbox" id="delete-all"
										aria-label="{translate key=All}" />
									<label class="visually-hidden">{translate key=Delete}</label>
									<a href="#" id="delete-selected" class="link-danger d-none"
										title="{translate key=Delete}"><i
											class="bi bi-trash3-fill text-danger icon remove"></i>
								</div>
							</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$blackouts item=blackout}
							{assign var=id value=$blackout->InstanceId}
							<tr class="editable" data-blackout-id="{$id}">
								<td>{$blackout->ResourceName}</td>
								<td class="date">{formatdate date=$blackout->StartDate timezone=$Timezone key=res_popup}
								</td>
								<td class="date">{formatdate date=$blackout->EndDate timezone=$Timezone key=res_popup}</td>
								<td>{$blackout->Title}</td>
								<td>{fullname first=$blackout->FirstName last=$blackout->LastName}</td>
								<td class="update edit"><a class="link-primary" href="#"><i
											class="bi bi-pencil-square"></i></a>
								</td>
								{if $blackout->IsRecurring}
									<td class="update">
										<a href="#" class="update delete-recurring"><i
												class="bi bi-trash3-fill text-danger icon remove"></i></a>
									</td>
								{else}
									<td class="update">
										<a href="#" class="update delete"><i
												class="bi bi-trash3-fill text-danger icon remove"></i></a>
									</td>
								{/if}
								<td class="action-delete">
									<div class="checkbox checkbox-single">
										<input {formname key=BLACKOUT_INSTANCE_ID multi=true}
											class="delete-multiple form-check-input" type="checkbox" id="delete{$id}"
											value="{$id}" aria-label="{translate key=Delete}" />
										<label for="delete{$id}" class="visually-hidden">Delete</label>
									</div>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteDialog" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="deleteModalLabel">{translate key=Delete}</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						{translate key=DeleteWarning}
					</div>
				</div>
				<div class="modal-footer">
					<form id="deleteForm" method="post">
						{cancel_button}
						{delete_button class="btnUpdateAllInstances"}
					</form>

				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteRecurringDialog" tabindex="-1" role="dialog"
		aria-labelledby="deleteRecurringModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="deleteRecurringModalLabel">{translate key=Delete}</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						{translate key=DeleteWarning}
					</div>
				</div>
				<div class="modal-footer">
					<form id="deleteRecurringForm" method="post" class="gap-2 d-md-flex justify-content-md-end">
						{cancel_button}

						<button type="button" class="btn btn-danger save btnUpdateThisInstance">
							<i class="bi bi-x-lg"></i> {translate key='ThisInstance'}</button>

						<button type="button" class="btn btn-danger save btnUpdateAllInstances">
							<i class="bi bi-x-circle"></i> {translate key='AllInstances'}</button>

						<input type="hidden" {formname key=SERIES_UPDATE_SCOPE} class="hdnSeriesUpdateScope"
							value="{SeriesUpdateScope::FullSeries}" />
					</form>
				</div>
			</div>
		</div>
	</div>

	<div id="deleteMultipleDialog" class="modal fade" tabindex="-1" role="dialog"
		aria-labelledby="deleteMultipleModalLabel" aria-hidden="true">
		<form id="deleteMultipleForm" method="post"
			action="{$smarty.server.SCRIPT_NAME}?action={ManageBlackoutsActions::DELETE_MULTIPLE}">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="deleteMultipleModalLabel">{translate key=Delete} (<span
								id="deleteMultipleCount"></span>)</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-warning">
							<div>{translate key=DeleteWarning}</div>
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

	{csrf_token}
	{include file="javascript-includes.tpl" Timepicker=true DataTable=true}
	{datatable tableId=$tableId}
	{jsfile src="reservationPopup.js"}
	{jsfile src="ajax-helpers.js"}
	{jsfile src="admin/blackouts.js"}
	{jsfile src="date-helper.js"}
	{jsfile src="recurrence.js"}

	<script type="text/javascript">
		$(document).ready(function() {
			var updateScope = {};
			updateScope.instance = '{SeriesUpdateScope::ThisInstance}';
			updateScope.full = '{SeriesUpdateScope::FullSeries}';
			updateScope.future = '{SeriesUpdateScope::FutureInstances}';

			var actions = {};

			var blackoutOpts = {
				scopeOpts: updateScope,
				actions: actions,
				deleteUrl: '{$smarty.server.SCRIPT_NAME}?action={ManageBlackoutsActions::DELETE}&{QueryStringKeys::BLACKOUT_ID}=',
				addUrl: '{$smarty.server.SCRIPT_NAME}?action={ManageBlackoutsActions::ADD}',
				editUrl: '{$smarty.server.SCRIPT_NAME}?action={ManageBlackoutsActions::LOAD}&{QueryStringKeys::BLACKOUT_ID}=',
				updateUrl: '{$smarty.server.SCRIPT_NAME}?action={ManageBlackoutsActions::UPDATE}',
				reservationUrlTemplate: "{$Path}reservation.php?{QueryStringKeys::REFERENCE_NUMBER}=[refnum]",
				popupUrl: "{$Path}ajax/respopup.php",
				timeFormat: '{$TimeFormat}'
			};

			var recurOpts = {
				repeatType: '{$RepeatType}',
				repeatInterval: '{$RepeatInterval}',
				repeatMonthlyType: '{$RepeatMonthlyType}',
				repeatWeekdays: [{foreach from=$RepeatWeekdays item=day}{$day}, {/foreach}]
			};

			var recurElements = {
				beginDate: $('#formattedAddStartDate'),
				endDate: $('#formattedAddEndDate'),
				beginTime: $('#addStartTime'),
				endTime: $('#addEndTime')
			};

			var recurrence = new Recurrence(recurOpts, recurElements);
			recurrence.init();

			var blackoutManagement = new BlackoutManagement(blackoutOpts);
			blackoutManagement.init();

		});
	</script>

	{control type="DatePickerSetupControl" ControlId="startDate" AltId="formattedStartDate"}
	{control type="DatePickerSetupControl" ControlId="endDate" AltId="formattedEndDate"}
	{control type="DatePickerSetupControl" ControlId="addStartDate" AltId="formattedAddStartDate"}
	{control type="DatePickerSetupControl" ControlId="addEndDate" AltId="formattedAddEndDate"}
	{control type="DatePickerSetupControl" ControlId="EndRepeat" AltId="formattedEndRepeat"}
	{control type="DatePickerSetupControl" ControlId="RepeatDate" AltId="formattedRepeatDate"}


	<div id="wait-box" class="modal fade" aria-labelledby="update-boxLabel" data-bs-backdrop="static"
		aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<div id="creatingNotification">
						{include file='wait-box.tpl' translateKey='Working'}
					</div>
					<div id="result" class="text-center"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="update-box" tabindex="-1" aria-labelledby="update-boxLabel" data-bs-backdrop="static"
		aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					{indicator id="update-spinner"}
					<div id="update-contents"></div>
				</div>
			</div>
		</div>
	</div>

</div>
{include file='globalfooter.tpl'}