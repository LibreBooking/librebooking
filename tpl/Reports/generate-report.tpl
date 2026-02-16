{include file='globalheader.tpl' cssFiles="scripts/js/jqplot/jquery.jqplot.min.css" Select2=true DataTable=true}

<div id="page-generate-report">
	<div id="customReportInput-container" class="accordion">
		<form role="form" id="customReportInput">
			<div class="accordion-item shadow mb-2 panel-default" id="report-filter-panel">
				<h2 class="accordion-header">
					<button class="accordion-button" type="button" data-bs-toggle="collapse"
						data-bs-target="#panelCustomReport" aria-expanded="true"
						aria-controls="panelCustomReport">{translate key=GenerateReport}
					</button>
				</h2>
				<div id="panelCustomReport" class="accordion-collapse collapse">
					<div class="accordion-body no-padding">
						<div id="custom-report-input">
							<div class="row mb-2 input-set" id="selectDiv">
								<div class="col-md-1"><span class="fw-bold">{translate key=Select}</span></div>

								<div class="col-md-11 btn-group-sm radio d-flex flex-wrap gap-1"
									data-bs-toggle="buttons">
									<input type="radio" class="btn-check" {formname key=REPORT_RESULTS}
										value="{Report_ResultSelection::FULL_LIST}" id="results_list"
										checked="checked" />
									<label for="results_list" class="btn btn-outline-primary"><i
											class="bi bi-list-task me-1"></i>{translate key=List}</label>

									<input type="radio" class="btn-check" {formname key=REPORT_RESULTS}
										value="{Report_ResultSelection::TIME}" id="results_time" />
									<label for="results_time" class="btn btn-outline-primary"><i
											class="bi bi-clock-history me-1"></i>{translate key=TotalTime}</label>

									<input type="radio" class="btn-check" {formname key=REPORT_RESULTS}
										value="{Report_ResultSelection::COUNT}" id="results_count" />
									<label for="results_count" class="btn btn-outline-primary"><i
											class="bi bi-list-columns me-1"></i>{translate key=Count}</label>

									<input type="radio" class="btn-check" {formname key=REPORT_RESULTS}
										value="{Report_ResultSelection::UTILIZATION}" id="results_utilization" />
									<label for="results_utilization" class="btn btn-outline-primary"><i
											class="bi bi-graph-up-arrow me-1"></i>{translate key=Utilization}</label>
								</div>
							</div>
						</div>

						<div class="row input-set select-toggle mb-2" id="listOfDiv">
							<div class="col-md-1"><span class="fw-bold">{translate key=Usage}</span></div>
							<div class="col-md-11 btn-group-sm radio d-flex flex-wrap gap-1" data-bs-toggle="buttons">
								<input type="radio" class="btn-check" {formname key=REPORT_USAGE}
									value="{Report_Usage::RESOURCES}" id="usage_resources" checked="checked">
								<label for="usage_resources" class="btn btn-outline-primary"><i
										class="bi bi-shop-window me-1"></i>{translate key=Resources}</label>

								<input type="radio" class="btn-check" {formname key=REPORT_USAGE}
									value="{Report_Usage::ACCESSORIES}" id="usage_accessories">
								<label for="usage_accessories" class="btn btn-outline-primary"><i
										class="bi bi-collection me-1"></i>{translate key=Accessories}</label>
							</div>
						</div>

						<div class="row input-set select-toggle mb-2" id="aggregateDiv" style="display:none;">
							<div class="col-md-1"><span class="fw-bold">{translate key=AggregateBy}</span></div>
							<div class="col-md-11 btn-group-sm radio d-flex flex-wrap align-items-start gap-1">
								<input type="radio" class="btn-check" {formname key=REPORT_GROUPBY}
									value="{Report_GroupBy::NONE}" id="groupby_none" checked="checked" />
								<label for="groupby_none" class="btn btn-outline-primary"><i
										class="bi bi-slash-circle me-1"></i>{translate key=None}</label>

								<input type="radio" class="btn-check" {formname key=REPORT_GROUPBY}
									value="{Report_GroupBy::RESOURCE}" id="groupby_resource"
									class="utilization-eligible" />
								<label for="groupby_resource" class="btn btn-outline-primary"><i
										class="bi bi-shop-window me-1"></i> {translate key=Resource}</label>

								<input type="radio" class="btn-check" {formname key=REPORT_GROUPBY}
									value="{Report_GroupBy::SCHEDULE}" id="groupby_schedule"
									class="utilization-eligible" />
								<label for="groupby_schedule" class="btn btn-outline-primary"><i
										class="bi bi-calendar me-1"></i> {translate key=Schedule}</label>

								<input type="radio" class="btn-check" {formname key=REPORT_GROUPBY}
									value="{Report_GroupBy::USER}" id="groupby_user" />
								<label for="groupby_user" class="btn btn-outline-primary"><i
										class="bi bi-person-fill me-1"></i>{translate key=User}</label>

								<input type="radio" class="btn-check" {formname key=REPORT_GROUPBY}
									value="{Report_GroupBy::GROUP}" id="groupby_group" />
								<label for="groupby_group" class="btn btn-outline-primary"><i
										class="bi bi-people-fill me-1"></i>{translate key=Group}</label>
							</div>
						</div>

						<div class="row mb-2 input-set form-group-sm" id="rangeDiv">
							<div class="col-md-1"><span class="fw-bold">{translate key=Range}</span></div>
							<div class="col-md-11">
								<div class="btn-group-sm radio d-flex flex-wrap gap-1" data-bs-toggle="buttons">
									<input type="radio" class="btn-check" {formname key=REPORT_RANGE}
										value="{Report_Range::CURRENT_MONTH}" id="current_month" checked="checked" />
									<label for="current_month" class="btn btn-outline-primary"><i
											class="bi bi-calendar3 me-1"></i>{translate key=CurrentMonth}</label>

									<input type="radio" class="btn-check" {formname key=REPORT_RANGE}
										value="{Report_Range::CURRENT_WEEK}" id="current_week" />
									<label for="current_week" class="btn btn-outline-primary"><i
											class="bi bi-calendar3-week me-1"></i>{translate key=CurrentWeek}</label>

									<input type="radio" class="btn-check" {formname key=REPORT_RANGE}
										value="{Report_Range::TODAY}" id="today" />
									<label for="today" class="btn btn-outline-primary"><i
											class="bi bi-calendar3-event me-1"></i>{translate key=Today}</label>

									<input type="radio" class="btn-check" {formname key=REPORT_RANGE}
										value="{Report_Range::ALL_TIME}" id="range_all" />
									<label for="range_all" class="btn btn-outline-primary"><i
											class="bi bi-calendar3-fill me-1"></i>{translate key=AllTime}</label>

									<div class="d-inline-flex align-items-center gap-1 btn-group-sm ">
										<input type="radio" class="btn-check" {formname key=REPORT_RANGE}
											value="{Report_Range::DATE_RANGE}" id="range_within" />
										<label for="range_within" class="btn btn-outline-primary"><i
												class="bi bi-calendar3-range me-1"></i>{translate key=Between}</label>

										<label for="startDate" class="visually-hidden">{translate key=StartDate}</label>
										<input type="text" class="form-control form-control-sm dateinput w-auto"
											id="startDate" {formname key=REPORT_START} />
										<div class='mx-1'>-</div>
										<label for="endDate" class="visually-hidden">{translate key=EndDate}</label>
										<input type="text" class="form-control form-control-sm dateinput w-auto"
											id="endDate" {formname key=REPORT_END} />
									</div>
								</div>
							</div>
						</div>

						<div class="row input-set form-group-sm">
							<div class="col-md-1"><span class="fw-bold">{translate key=FilterBy}</span></div>
							<div class="col-md-11 d-flex flex-wrap gap-1">
								<div class="form-group no-margin no-padding col-12 col-md-3">
									<label for="resourceId" class="visually-hidden">{translate key=Resource}</label>
									<select class="form-control" {formname key=RESOURCE_ID multi=true}
										multiple="multiple" id="resourceId">
										{foreach from=$Resources item=resource}
											<option value="{$resource->GetId()}">{$resource->GetName()}</option>
										{/foreach}
									</select>
								</div>
								<div class="form-group no-margin no-padding col-12 col-md-3">
									<label for="resourceTypeId"
										class="visually-hidden">{translate key=ResourceType}</label>
									<select class="form-control" {formname key=RESOURCE_TYPE_ID multi=true}
										multiple="multiple" id="resourceTypeId">
										{foreach from=$ResourceTypes item=resourceType}
											<option value="{$resourceType->Id()}">{$resourceType->Name()}</option>
										{/foreach}
									</select>
								</div>
								<div class="form-group no-margin no-padding col-12 col-md-3">
									<label for="accessoryId" class="visually-hidden">{translate key=Accessory}</label>
									<select class="form-control" {formname key=ACCESSORY_ID multi=true}
										multiple="multiple" id="accessoryId">
										{foreach from=$Accessories item=accessory}
											<option value="{$accessory->Id}">{$accessory->Name}</option>
										{/foreach}
									</select>
								</div>
								<div class="form-group no-margin no-padding col-12 col-md-3">
									<label for="scheduleId" class="visually-hidden">{translate key=Schedule}</label>
									<select class="form-control" {formname key=SCHEDULE_ID multi=true}
										multiple="multiple" id="scheduleId">
										{foreach from=$Schedules item=schedule}
											<option value="{$schedule->GetId()}">{$schedule->GetName()}</option>
										{/foreach}
									</select>
								</div>
								<div class="form-group no-margin no-padding col-12 col-md-3">
									<label for="groupId" class="visually-hidden">{translate key=Group}</label>
									<select class="form-control" {formname key=GROUP_ID multi=true} multiple="multiple"
										id="groupId">
										{foreach from=$Groups item=group}
											<option value="{$group->Id}">{$group->Name}</option>
										{/foreach}
									</select>
								</div>
							</div>
							<div class="col-md-11 gy-1 offset-md-1  d-flex flex-wrap gap-1">
								<div class="form-group no-margin no-padding col-12 col-md-3">
									<div id="user-filter-div">
										<label class="visually-hidden"
											for="user-filter">{translate key=AllUsers}</label>
										<input id="user-filter" type="search" class="form-control form-control-sm"
											placeholder="{translate key=AllUsers}" />
										<input id="user_id" class="filter-id" type="hidden" {formname key=USER_ID} />
									</div>
								</div>
								<div class="form-group no-margin no-padding col-12 col-md-3">
									<div id="participant-filter-div">
										<div class="form-group">
											<label class="visually-hidden"
												for="participant-filter">{translate key=AllParticipants}</label>
											<input id="participant-filter" type="search"
												class="form-control form-control-sm"
												placeholder="{translate key=AllParticipants}" />
											<input id="participant_id" class="filter-id" type="hidden"
												{formname key=PARTICIPANT_ID} />
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordion-footer border-top mt-3 pt-3 d-flex align-items-center">
							<input type="submit" value="{translate key=GetReport}" class="btn btn-primary btn-sm"
								id="btnCustomReport" asyncAction="" />
							<div class="form-check inline-block ms-2">
								<input class="form-check-input" type="checkbox" id="chkIncludeDeleted"
									{formname key=INCLUDE_DELETED} />
								<label class="form-check-label"
									for="chkIncludeDeleted">{translate key=IncludeDeleted}</label>
							</div>
						</div>
					</div>
				</div>
				{csrf_token}
			</div>
		</form>
	</div>
</div>

<div id="saveMessage" class="alert alert-success" style="display:none;">
	<strong>{translate key=ReportSaved}</strong> <a class="alert-link"
		href="{$Path}reports/{Pages::REPORTS_SAVED}">{translate key=MySavedReports}</a>
</div>

<div id="resultsDiv">
</div>

<div id="indicator" class="d-none card shadow p-2">
	{include file="wait-box.tpl" translateKey='Working'}
</div>

{include file="Reports/chart.tpl"}
<div class="modal fade" id="saveDialog" tabindex="-1" role="dialog" aria-labelledby="saveDialogLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<form id="saveReportForm" method="post">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="saveDialogLabel">{translate key=SaveThisReport}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="saveReportName" class="fw-bold">{translate key=Name}</label>
						<input type="text" id="saveReportName" {formname key=REPORT_NAME} class="form-control"
							placeholder="{translate key=NoTitleLabel}" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary cancel"
						data-bs-dismiss="modal">{translate key='Cancel'}</button>
					<button type="button" id="btnSaveReport" class="btn btn-primary"><span
							class="bi bi-check-circle"></span>
						{translate key='SaveThisReport'}
					</button>
					{indicator}
				</div>
			</div>
		</form>
	</div>
</div>

{include file="javascript-includes.tpl" Select2=true DataTable=true}
{datatable tableId={$tableId}}
{jsfile src="autocomplete.js"}
{jsfile src="ajax-helpers.js"}
{jsfile src="reports/generate-reports.js"}
{jsfile src="reports/common.js"}
{jsfile src="reports/chart.js"}

<script type="text/javascript">
	$(document).ready(function() {
		var reportOptions = {
			userAutocompleteUrl: "{$Path}ajax/autocomplete.php?type={AutoCompleteType::User}",
			groupAutocompleteUrl: "{$Path}ajax/autocomplete.php?type={AutoCompleteType::Group}",
			customReportUrl: "{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}={ReportActions::Generate}",
			printUrl: "{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}={ReportActions::PrintReport}&",
			csvUrl: "{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}={ReportActions::Csv}&",
			saveUrl: "{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}={ReportActions::Save}"
		};

		var reports = new GenerateReports(reportOptions);
		reports.init();

		var common = new ReportsCommon({
			scriptUrl: '{$ScriptUrl}',
			chartOpts: {
				dateAxisFormat: '{$DateAxisFormat}'
			}
		});
		common.init();

		$('#resourceId').select2({
			placeholder: '{translate key=AllResources}'
		});
		$('#resourceTypeId').select2({
			placeholder: '{translate key=AllResourceTypes}'
		});
		$('#accessoryId').select2({
			placeholder: '{translate key=AllAccessories}'
		});
		$('#scheduleId').select2({
			placeholder: '{translate key=AllSchedules}'
		});
		$('#groupId').select2({
			placeholder: '{translate key=AllGroups}'
		});
	});

	//$('#user-filter, #participant-filter').clearable();
</script>

{control type="DatePickerSetupControl" ControlId="startDate"}
{control type="DatePickerSetupControl" ControlId="endDate"}

</div>
{include file='globalfooter.tpl'}
