{include file='globalheader.tpl'}

<div class="accordion" id="page-dashboard">
	<div id="dashboardList">
		{foreach from=$items item=dashboardItem}
			<div>{$dashboardItem->PageLoad()}</div>
		{/foreach}
	</div>

	{include file="javascript-includes.tpl"}

	{jsfile src="reservationPopup.js"}
	{jsfile src="dashboard.js"}
	{jsfile src="resourcePopup.js"}
	{jsfile src="ajax-helpers.js"}

	<script type="text/javascript">
		$(document).ready(function() {

			var dashboardOpts = {
				reservationUrl: "{Pages::RESERVATION}?{QueryStringKeys::REFERENCE_NUMBER}=",
				summaryPopupUrl: "ajax/respopup.php",
				scriptUrl: '{$ScriptUrl}'
			};

			var dashboard = new Dashboard(dashboardOpts);
			dashboard.init();
		});
	</script>
</div>

<div id="wait-box" class="modal fade" aria-labelledby="update-boxLabel" data-bs-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
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
{include file='globalfooter.tpl'}
