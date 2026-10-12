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

<script>
$(document).ready(function() {
    $('#dashboardList > div').each(function() {
        var headerText = $(this).find('.accordion-button').text().trim();
        var $target = $(this).find('[id="upcomingReservationsDashboard"]');

        var keywords = ['grupy', 'zatwierdzenie', 'wymeldowania'];

        if (keywords.some(keyword => headerText.includes(keyword))) {
            $target.hide();
        }
    });
});


$(document).ready(function() {
    // 1. Poprawa zduplikowanych ID kontenerów głównych
    $('#dashboardList > div').each(function() {
        var headerText = $(this).find('.accordion-button').text().trim();
        var $target = $(this).find('[id="upcomingReservationsDashboard"]');

        if ($target.length) {
            if (headerText.includes('Wcześniejsze rezerwacje')) {
                 $target.attr('id', 'pastReservationsDashboard');
            } else if (headerText.includes('Zbliżające się rezerwacje') && !headerText.includes('grupy')) {
                 $target.attr('id', 'futureReservationsDashboard');
            } else if (headerText.includes('grupy')) {
                 $target.attr('id', 'groupReservationsDashboard');
            } else if (headerText.includes('oczekujące')) {
                 $target.attr('id', 'pendingReservationsDashboard');
            } else if (headerText.includes('wymeldowania')) {
                 $target.attr('id', 'missingCheckoutDashboard');
            }
        }
    });

    // 2. Kompleksowe zamknięcie wszystkich sekcji akordeonu (w tym #dashboardContents)
    $('#dashboardList .accordion-collapse').removeClass('show');
    $('#dashboardList .accordion-button').addClass('collapsed').attr('aria-expanded', 'false');
});
</script>
