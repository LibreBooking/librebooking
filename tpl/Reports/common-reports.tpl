{include file='globalheader.tpl' DataTable=true}

<div id="page-common-reports" class="accordion">
    <div>
        <div class="accordion-item shadow mb-2" id="saved-reports-panel">
            <h2 class="accordion-header fw-bold link-primary">
                <button class="accordion-button link-primary fw-bold" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelCommonReports" aria-expanded="false" aria-controls="panelCommonReports">
                    {translate key=CommonReports}</button>
            </h2>
            <div id="panelCommonReports" class="accordion-collapse collapse">
                <div class="accordion-body no-padding">
                    <div id="report-list">
                        <table class="table table-striped table-hover w-100">
                            <tbody>
                                <tr>
                                    <td class="fw-bold">{translate key=ReservedResources}</td>
                                    <td class="text-end">
                                        <a href="#" reportId="{CannedReport::RESERVATIONS_TODAY}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3-event"></i>
                                            {translate key=Today}</a>
                                        <a href="#" reportId="{CannedReport::RESERVATIONS_THISWEEK}"
                                            class="report report-action runNow link-primary mx-2"><i
                                                class="bi bi-calendar3-week"></i>
                                            {translate key=CurrentWeek}</a>
                                        <a href="#" reportId="{CannedReport::RESERVATIONS_THISMONTH}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3 me-1"></i>
                                            {translate key=CurrentMonth}</a>
                                    </td>
                                </tr>
                                <tr class="alt">
                                    <td class="fw-bold">{translate key=ReservedAccessories}</td>
                                    <td class="text-end">
                                        <a href="#" reportId="{CannedReport::ACCESSORIES_TODAY}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3-event"></i>
                                            {translate key=Today}</a>
                                        <a href="#" reportId="{CannedReport::ACCESSORIES_THISWEEK}"
                                            class="report report-action runNow link-primary mx-2"><i
                                                class="bi bi-calendar3-week"></i>
                                            {translate key=CurrentWeek}</a>
                                        <a href="#" reportId="{CannedReport::ACCESSORIES_THISMONTH}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3 me-1"></i>
                                            {translate key=CurrentMonth}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{translate key=ResourceUsageTimeBooked}</td>
                                    <td class="text-end">
                                        <a href="#" reportId="{CannedReport::RESOURCE_TIME_ALLTIME}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3-event"></i>
                                            {translate key=AllTime}</a>
                                        <a href="#" reportId="{CannedReport::RESOURCE_TIME_THISWEEK}"
                                            class="report report-action runNow link-primary mx-2"><i
                                                class="bi bi-calendar3-week"></i>
                                            {translate key=CurrentWeek}</a>
                                        <a href="#" reportId="{CannedReport::RESOURCE_TIME_THISMONTH}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3 me-1"></i>
                                            {translate key=CurrentMonth}</a>
                                    </td>
                                </tr>
                                <tr class="alt">
                                    <td class="fw-bold">{translate key=ResourceUsageReservationCount}</td>
                                    <td class="text-end">
                                        <a href="#" reportId="{CannedReport::RESOURCE_COUNT_ALLTIME}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3-event"></i>
                                            {translate key=AllTime}</a>
                                        <a href="#" reportId="{CannedReport::RESOURCE_COUNT_THISWEEK}"
                                            class="report report-action runNow link-primary mx-2"><i
                                                class="bi bi-calendar3-week"></i>
                                            {translate key=CurrentWeek}</a>
                                        <a href="#" reportId="{CannedReport::RESOURCE_COUNT_THISMONTH}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3 me-1"></i>
                                            {translate key=CurrentMonth}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">{translate key=Top20UsersTimeBooked}</td>
                                    <td class="text-end">
                                        <a href="#" reportId="{CannedReport::USER_TIME_ALLTIME}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3-event"></i>
                                            {translate key=AllTime}</a>
                                        <a href="#" reportId="{CannedReport::USER_TIME_THISWEEK}"
                                            class="report report-action runNow link-primary mx-2"><i
                                                class="bi bi-calendar3-week"></i>
                                            {translate key=CurrentWeek}</a>
                                        <a href="#" reportId="{CannedReport::USER_TIME_THISMONTH}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3 me-1"></i>
                                            {translate key=CurrentMonth}</a>
                                    </td>
                                </tr>
                                <tr class="alt">
                                    <td class="fw-bold">{translate key=Top20UsersReservationCount}</td>
                                    <td class="text-end">
                                        <a href="#" reportId="{CannedReport::USER_COUNT_ALLTIME}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3-event"></i>
                                            {translate key=AllTime}</a>
                                        <a href="#" reportId="{CannedReport::USER_COUNT_THISWEEK}"
                                            class="report report-action runNow link-primary mx-2"><i
                                                class="bi bi-calendar3-week"></i>
                                            {translate key=CurrentWeek}</a>
                                        <a href="#" reportId="{CannedReport::USER_COUNT_THISMONTH}"
                                            class="report report-action runNow link-primary"><i
                                                class="bi bi-calendar3 me-1"></i>
                                            {translate key=CurrentMonth}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="resultsDiv">
</div>

<div id="indicator" class="d-none card shadow p-2">
    {include file="wait-box.tpl" translateKey='Working'}
</div>

{csrf_token}

{include file="Reports/chart.tpl"}

{include file="javascript-includes.tpl" DataTable=true}
{jsfile src="ajax-helpers.js"}
{jsfile src="reports/canned-reports.js"}
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

        var reports = new CannedReports(reportOptions);
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

{include file='globalfooter.tpl'}
