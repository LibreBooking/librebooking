{include file='globalheader.tpl' Select2=true Fullcalendar=true cssFiles='assets/vendor/jqtree/1.8.11/css/jqtree.css,css/schedule.css' printCssFiles='css/calendar.print.css'}

<div id="page-{$pageIdSuffix}">
    {include file='Calendar/calendar.filter.tpl'}

    <div id="subscriptionContainer">
        {include file="Calendar/{$subscriptionTpl}" IsSubscriptionAllowed=$IsSubscriptionAllowed
        IsSubscriptionEnabled=$IsSubscriptionEnabled SubscriptionUrl=$SubscriptionUrl}
    </div>

    <div id="calendar" class="mt-4"></div>

    <div id="dayDialog" class="card shadow bg-secondary-subtle position-absolute d-none p-0" style="z-index: 2000;">
        <div class="card-body">
            {if !isset($HideCreate)  || !$HideCreate}
                <a href=" #" id="dayDialogCreate" class="link-success me-2"><i
                        class="bi bi-check-lg me-1"></i>{translate key=CreateReservation}</a>
            {/if}
            <a href="#" id="dayDialogView" class="link-secondary me-2"><i
                    class="bi bi-search me-1"></i>{translate key=ViewDay}</a>
            <a href="#" id="dayDialogCancel" class="link-danger"><i
                    class="bi bi-dash-circle me-1"></i>{translate key=Cancel}</a>
        </div>
    </div>

    <div class="modal fade" id="moveErrorDialog" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">{translate key=ErrorMovingReservation}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <ul id="moveErrorsList"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    {ok_button id="moveErrorOk"}
                </div>
            </div>
        </div>
    </div>

    <form id="moveReservationForm">
        <input id="moveReferenceNumber" type="hidden" {formname key=REFERENCE_NUMBER} />
        <input id="moveStartDate" type="hidden" {formname key=BEGIN_DATE} />
        <input id="moveResourceId" type="hidden" {formname key=RESOURCE_ID} value="0" />
        <input id="moveSourceResourceId" type="hidden" {formname key=ORIGINAL_RESOURCE_ID} value="0" />
    </form>

    {csrf_token}

    {include file="javascript-includes.tpl" Select2=true Fullcalendar=true}
    {jsfile src="reservationPopup.js"}
    {jsfile src="calendar.js"}
    {jsfile src="ajax-helpers.js"}
    {jsfile src="autocomplete.js"}
    {vendor_js src="jqtree/1.8.11/js/tree.jquery.js"}

    <script type="text/javascript">
        $(document).ready(function() {

            var options = {
                view: '{$CalendarType|escape:javascript}',
                defaultDate: moment('{$DisplayDate->Format('Y-m-d')}', 'YYYY-MM-DD'),
                todayText: '{{translate key=Today}|escape:'javascript'}',
                dayText: '{{translate key=Day}|escape:'javascript'}',
                monthText: '{{translate key=Month}|escape:'javascript'}',
                weekText: '{{translate key=Week}|escape:'javascript'}',
                dayClickUrl: '{$pageUrl}?ct={CalendarTypes::Day}&sid={$ScheduleId|escape:'javascript'}&rid={$ResourceId|escape:'javascript'}&gid={if isset($GroupId)}{$GroupId|escape:'javascript'}{/if}',
                dayClickUrlTemplate: '{$pageUrl}?ct={CalendarTypes::Day}&sid=[sid]&rid=[rid]&gid=[gid]',
                dayNames: {js_array array=$DayNames},
                dayNamesShort: {js_array array=$DayNamesShort},
                monthNames: {js_array array=$MonthNames},
                monthNamesShort: {js_array array=$MonthNamesShort},
                timeFormat: '{$TimeFormat}',
                dayMonth: '{$DateFormat}',
                firstDay: {$FirstDay},
                reservationUrl: '{$CreateReservationPage}?sid={$ScheduleId|escape:'javascript'}&rid={$ResourceId|escape:'javascript'}',
                reservationUrlTemplate: '{$CreateReservationPage}?sid=[sid]&rid=[rid]',
                reservable: true,
                eventsUrl: '{$pageUrl}',
                eventsData: {
                    dr: 'events',
                    sid: '{$ScheduleId|escape:'javascript'}',
                    rid: '{$ResourceId|escape:'javascript'}',
                    gid: '{if isset($GroupId)}{$GroupId|escape:'javascript'}{/if}'
                },
                getSubscriptionUrl: '{$pageUrl}?dr=subscription',
                subscriptionEnableUrl: '{$pageUrl}?{QueryStringKeys::ACTION}={CalendarActions::ActionEnableSubscription}',
                subscriptionDisableUrl: '{$pageUrl}?{QueryStringKeys::ACTION}={CalendarActions::ActionDisableSubscription}',
                moveReservationUrl: "{$Path}ajax/reservation_move.php",
                returnTo: '{$pageUrl}',
                autocompleteUrl: "{$Path}ajax/autocomplete.php?type={AutoCompleteType::User}",
                showWeekNumbers: {if $ShowWeekNumbers}true{else}false{/if}
            };

            var calendar = new Calendar(options);
            calendar.init();

            calendar.bindResourceGroups({$ResourceGroupsAsJson}, {$SelectedGroupNode|default:0});

        });
    </script>
</div>
{include file='globalfooter.tpl'}
