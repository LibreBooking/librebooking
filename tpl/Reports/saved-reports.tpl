{include file='globalheader.tpl' cssFiles="scripts/js/jqplot/jquery.jqplot.min.css" DataTable=true}

<div id="page-saved-reports" class="accordion">
	<div>
		<div class="accordion-item shadow mb-2" id="saved-reports-panel">
			<h2 class="accordion-header">
				<button class="accordion-button link-primary fw-bold" type="button" data-bs-toggle="collapse"
					data-bs-target="#panelReportSaved" aria-expanded="false" aria-controls="panelReportSaved">
					{translate key=MySavedReports} <span
						class="badge bg-primary ms-1">{$ReportList|default:array()|count}</span>
				</button>
			</h2>
			<div id="panelReportSaved" class="accordion-collapse collapse">
				<div class="accordion-body no-padding">
					{if $ReportList|default:array()|count == 0}
						<h3 class="no-data text-center">{translate key=NoSavedReports}</h3>
						<div class="text-center"><a href="{$Path}reports/{Pages::REPORTS_GENERATE}"
								class="link-primary">{translate key=GenerateReport}</a></div>
					{else}
						<div id="report-list">
							{assign var=tableId value='saved-reportsListTable'}
							<table class="table table-striped table-hover border-top w-100" id="{$tableId}">
								<thead>
									<tr>
										<th>{translate key='Title'}</th>
										<th>{translate key='Date'}</th>
										<th>{translate key='RunReport'}</th>
										<th>{translate key='EmailReport'}</th>
										<th>{translate key='Delete'}</th>
									</tr>
								</thead>
								<tbody>
									{foreach from=$ReportList item=report}
										<tr reportId="{$report->Id()}">
											<td><span class="fw-bold">{$report->ReportName()|default:$untitled}</span></td>
											<td class="right"><span
													class="report-created-date fst-italic">{format_date date=$report->DateCreated()}</span>
											</td>

											<td class="report-action"><a href="#" class="runNow report link-primary"><i
														class="bi bi-play-circle text-success me-1"></i>{translate key='RunReport'}
												</a></td>
											<td class="report-action"><a href="#" class="emailNow report link-primary"><span
														class="bi bi-envelope me-1 icon"></span>{translate key='EmailReport'}</a>
											</td>
											<td class="report-action"><a href="#"
													class="delete report link-danger text-decoration-none"><i
														class="bi bi-trash3-fill me-1 icon remove"></i>{translate key='Delete'}</a>
											</td>
											{*
								   {if $report->IsScheduled()}
									   Schedule: <a href="#" class="editSchedule report">{translate key=Edit}</a>
									   {else}
									   <a href="#" class="schedule report">Schedule</a>
								   {/if}
								   *}


										</tr>
									{/foreach}
								</tbody>
							</table>
						</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
	<div id="resultsDiv">
	</div>

	<div id="emailSent" class="alert alert-success no-show">
		<strong>{translate key=ReportSent}</strong>N
	</div>

	<div class="modal fade" id="emailDiv" tabindex="-1" role="dialog" aria-labelledby="emailDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="emailForm" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="emailDialogLabel">{translate key=EmailReport}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="fw-bold" for="emailTo">{translate key=Email}</label>
							<input id="emailTo" {formname key=EMAIL} value="{$UserEmail}" class="form-control" />
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary cancel"
								data-bs-dismiss="modal">{translate key='Cancel'}</button>
							<button id="btnSendEmail" type="button" class="btn btn-primary save"><i
									class="bi bi-envelope me-1"></i>{translate key=EmailReport}
							</button>
							{indicator}
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="deleteDiv" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form id="deleteForm" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteLabel">{translate key=Delete}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-danger">
							{translate key=DeleteWarning}
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary cancel"
							data-bs-dismiss="modal">{translate key='Cancel'}</button>
						<button type="button" class="btn btn-danger save">{translate key='Delete'}</button>
						{indicator}
					</div>
				</div>
				{csrf_token}
			</form>
		</div>
	</div>

	<div id="indicator" class="d-none card shadow p-2">
		{include file="wait-box.tpl" translateKey='Working'}
	</div>

	{include file="Reports/chart.tpl"}

	{*
	<div id="scheduleDiv" class="dialog" title="Schedule Report">
		<label>Email Report</label>
		<select id="repeatOptions" {formname key=repeat_options} class="pulldown">
		{foreach from=$RepeatOptions key=k item=v}
			<option value="{$k}">{translate key=$v['key']}</option>
		{/foreach}
		</select>

		<div id="repeatEveryDiv" style="display:none;" class="days weeks months years">
			<label>{translate key="RepeatEveryPrompt"}</label>
			<select id="repeatInterval" {formname key=repeat_every} class="pulldown">
			{html_options values=$RepeatEveryOptions output=$RepeatEveryOptions}
			</select>
			<span class="days">{translate key=$RepeatOptions['daily']['everyKey']}</span>
			<span class="weeks">{translate key=$RepeatOptions['weekly']['everyKey']}</span>
			<span class="months">{translate key=$RepeatOptions['monthly']['everyKey']}</span>
			<span class="years">{translate key=$RepeatOptions['yearly']['everyKey']}</span>
		</div>
		<div id="repeatOnWeeklyDiv" style="display:none;" class="weeks">
			<label>{translate key="RepeatDaysPrompt"}</label>
			<input type="checkbox"
				   id="repeatDay0" {formname key=repeat_sunday} /><label
				for="repeatDay0">{translate key="DaySundaySingle"}</label>
			<input type="checkbox"
				   id="repeatDay1" {formname key=repeat_monday} /><label
				for="repeatDay1">{translate key="DayMondaySingle"}</label>
			<input type="checkbox"
				   id="repeatDay2" {formname key=repeat_tuesday} /><label
				for="repeatDay2">{translate key="DayTuesdaySingle"}</label>
			<input type="checkbox"
				   id="repeatDay3" {formname key=repeat_wednesday} /><label
				for="repeatDay3">{translate key="DayWednesdaySingle"}</label>
			<input type="checkbox"
				   id="repeatDay4" {formname key=repeat_thursday} /><label
				for="repeatDay4">{translate key="DayThursdaySingle"}</label>
			<input type="checkbox"
				   id="repeatDay5" {formname key=repeat_friday} /><label
				for="repeatDay5">{translate key="DayFridaySingle"}</label>
			<input type="checkbox"
				   id="repeatDay6" {formname key=repeat_saturday} /><label
				for="repeatDay6">{translate key="DaySaturdaySingle"}</label>
		</div>
		<div id="repeatOnMonthlyDiv" style="display:none;" class="months">
			<input type="radio" {formname key=REPEAT_MONTHLY_TYPE} value="{RepeatMonthlyType::DayOfMonth}"
				   id="repeatMonthDay" checked="checked"/>
			<label for="repeatMonthDay">{translate key="repeatDayOfMonth"}</label>
			<input type="radio" {formname key=REPEAT_MONTHLY_TYPE} value="{RepeatMonthlyType::DayOfWeek}"
				   id="repeatMonthWeek"/>
			<label for="repeatMonthWeek">{translate key="repeatDayOfWeek"}</label>
		</div>
	</div>
	*}
	{include file="javascript-includes.tpl" DataTable=true}
	{datatable tableId={$tableId}}
	{jsfile src="ajax-helpers.js"}
	{jsfile src="reports/saved-reports.js"}
	{jsfile src="reports/chart.js"}
	{jsfile src="reports/common.js"}

	<script type="text/javascript">
		$(document).ready(function() {
			var reportOptions = {
				generateUrl: "{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}={ReportActions::Generate}&{QueryStringKeys::REPORT_ID}=",
				emailUrl: "{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}={ReportActions::Email}&{QueryStringKeys::REPORT_ID}=",
				deleteUrl: "{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}={ReportActions::Delete}&{QueryStringKeys::REPORT_ID}=",
				printUrl: "{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}={ReportActions::PrintReport}&{QueryStringKeys::REPORT_ID}=",
				csvUrl: "{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}={ReportActions::Csv}&{QueryStringKeys::REPORT_ID}="
			};

			var reports = new SavedReports(reportOptions);
			reports.init();

			var common = new ReportsCommon({
				scriptUrl: '{$ScriptUrl}',
				chartOpts: {
					dateAxisFormat: '{$DateAxisFormat}'
				}
			});
			common.init();
		});
	</script>
</div>
{include file='globalfooter.tpl'}