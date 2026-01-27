<!-- tpl/Schedule/schedule.tpl -->
{* All of the slot display formatting *}

{function name=displayPastTime}
    <td ref="{$SlotRef}" class="pasttime slot" data-href="{$Href}"
        data-start="{$Slot->BeginDate()->Format('Y-m-d H:i:s')|escape:url}"
        data-end="{$Slot->EndDate()->Format('Y-m-d H:i:s')|escape:url}" data-min="{$Slot->BeginDate()->Timestamp()}"
        data-max="{$Slot->EndDate()->Timestamp()}" data-resourceId="{$ResourceId}">&nbsp;
    </td>
{/function}

{function name=displayReservable}
    <td class="reservable clickres slot" ref="{$SlotRef}" data-href="{$Href}"
        data-start="{$Slot->BeginDate()->Format('Y-m-d H:i:s')|escape:url}"
        data-end="{$Slot->EndDate()->Format('Y-m-d H:i:s')|escape:url}" data-min="{$Slot->BeginDate()->Timestamp()}"
        data-max="{$Slot->EndDate()->Timestamp()}" data-resourceId="{$ResourceId}">&nbsp;
    </td>
{/function}

{function name=displayRestricted}
    <td ref="{$SlotRef}" class="restricted slot" data-href="{$Href}"
        data-start="{$Slot->BeginDate()->Format('Y-m-d H:i:s')|escape:url}"
        data-end="{$Slot->EndDate()->Format('Y-m-d H:i:s')|escape:url}" data-min="{$Slot->BeginDate()->Timestamp()}"
        data-max="{$Slot->EndDate()->Timestamp()}" data-resourceId="{$ResourceId}">&nbsp;
    </td>
{/function}

{function name=displayUnreservable}
    <td ref="{$SlotRef}" class="unreservable slot" data-href="{$Href}"
        data-start="{$Slot->BeginDate()->Format('Y-m-d H:i:s')|escape:url}"
        data-end="{$Slot->EndDate()->Format('Y-m-d H:i:s')|escape:url}" data-min="{$Slot->BeginDate()->Timestamp()}"
        data-max="{$Slot->EndDate()->Timestamp()}" data-resourceId="{$ResourceId}">&nbsp;
    </td>
{/function}

{function name=displaySlot}
    {call name=$DisplaySlotFactory->GetFunction($Slot, $AccessAllowed) Slot=$Slot Href=$Href SlotRef=$SlotRef ResourceId=$ResourceId}
{/function}

{* End slot display formatting *}

{block name="header"}
    {include file='globalheader.tpl' Qtip=true Select2=true DatePicker=true cssFiles='scripts/css/jqtree.css,css/schedule.css' printCssFiles='css/schedule.print.css'}
{/block}

<div id="page-schedule">
    {assign var=startTime value=microtime(true)}

    {if isset($ShowResourceWarning) && $ShowResourceWarning}
        <div class="alert alert-warning no-resource-warning"><i class="bi bi-exclamation-triangle-fill"></i>
            {translate key=NoResources}
            <a class="alert-link" href="admin/manage_resources.php">{translate key=AddResource}</a>
    </div> {/if}

    {if $CanViewAdmin}
        <div id="slow-schedule-warning" class="alert alert-warning d-flex align-items-center d-none" role="alert">
            <div>
                <p>We noticed this page is taking a long time to load. To speed ths page up, try
                    reducing the number of <a class="alert-link" href="admin/manage_resources.php">resources</a> on this
                    schedule or
                    reducing the number of <a class="alert-link" href="admin/manage_schedules.php">days</a> being shown.
                </p>
                <p class="mb-0">
                    This page is taking <span id="warning-time"></span> seconds to load
                    <span id="warning-resources"></span> resources for <span id="warning-days"></span> days.
                    <button type="button" class="close close-forever btn btn-link alert-link"
                        aria-label="Do not show again">
                        <span aria-hidden="true">Do not show again</span>
                    </button>
                </p>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    {/if}

    {if $IsAccessible}
        <div id="defaultSetMessage" class="alert alert-success d-none">
            {translate key=DefaultScheduleSet}
        </div>
        {block name="schedule_control"}
            <div class="row">
                {assign var=titleWidth value="col-sm-12 col-12"}
                {if !isset($HideSchedule) || !$HideSchedule}
                    {assign var=titleWidth value="col-sm-6 col-12"}
                    <div id="schedule-actions" class="col-sm-3 col-12">
                        {block name="actions"}
                            <div class="d-flex align-items-center">
                                <a href="#" id="print_schedule" class="link-primary me-1" title="{translate key=Print}"><span
                                        class="bi bi-printer"></span></a>
                                <a href="#" id="make_default" class="link-primary me-2" style="display:none;"><i
                                        class="bi bi-star-fill"></i></a>
                                <a href="#" class="schedule-style me-1" id="schedule_standard"
                                    schedule-display="{ScheduleStyle::Standard}">
                                    <img src="img/table.png" alt="{translate key='StandardScheduleDisplay'}" />
                                </a>
                                <a href="#" class="schedule-style me-1" id="schedule_tall" schedule-display="{ScheduleStyle::Tall}">
                                    <img src="img/table-tall.png" alt="{translate key='TallScheduleDisplay'}" />
                                </a>
                                <a href="#" class="schedule-style d-none d-md-block me-1" id="schedule_wide"
                                    schedule-display="{ScheduleStyle::Wide}">
                                    <img src="img/table-wide.png" alt="{translate key='WideScheduleDisplay'}" />
                                </a>
                                <a href="#" class="schedule-style d-none d-md-block" id="schedule_week"
                                    schedule-display="{ScheduleStyle::CondensedWeek}">
                                    <img src="img/table-week.png" alt="{translate key='CondensedWeekScheduleDisplay'}" />
                                </a>
                            </div>
                            {if isset($SubscriptionUrl) && $SubscriptionUrl != null && $ShowSubscription && $LoggedIn}
                                <div class="d-flex align-items-center"><i class="bi bi-rss-fill link-primary me-1"></i>
                                    <a class="link-primary me-1" target="_blank" href="{$SubscriptionUrl->GetAtomUrl()}">Atom</a>
                                    <div class="vr me-1"></div>
                                    <a class="link-primary" target="_blank" href="{$SubscriptionUrl->GetWebcalUrl()}">iCalendar</a>
                                </div>
                            {/if}
                        {/block}
                    </div>
                {/if}

                <div id="schedule-title" class="schedule_title {$titleWidth}">
                    <div class="d-flex justify-content-center">
                        {if count($Schedules) > 1}
                            <label for="schedules" class="visually-hidden">{translate key='Schedules'}</label>
                            <select id="schedules" class="form-select w-auto">
                                {foreach from=$Schedules item=schedule}
                                    <option value="{$schedule->GetId()}" {if $schedule->GetId() == $ScheduleId}selected="selected"
                                        {/if}>
                                        {$schedule->GetName()}</option>
                                {/foreach}
                            </select>
                        {/if}
                        <a class="link-primary" href="#" id="calendar_toggle" title="{translate key=ShowHideNavigation}"
                            data-bs-toggle="collapse" data-bs-target="#individualDates" aria-expanded="false"
                            aria-controls="individualDates">
                            <span class="bi bi-calendar"></span>
                            <span class="visually-hidden">{translate key=ShowHideNavigation}</span>
                        </a>
                    </div>
                </div>
            </div>

            <div id="individualDates" class="collapse">
                <div class="d-flex justify-content-center align-items-center mt-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type='checkbox' id='multidateselect' />
                        <label class="form-check-label" for='multidateselect'>{translate key=SpecificDates}</label>
                    </div>
                </div>
                <div class="text-center" id="individualDatesList"></div>
                <button class="btn btn-sm btn-primary mx-auto" href="#" id="individualDatesGo">
                    <i class="bi bi-search me-1"></i>{translate key=SpecificDates}
                </button>
                <div class="d-flex justify-content-center align-items-center">
                    <div id="datepicker" class="collapse"></div>
                </div>
            </div>



            {capture name="date_navigation"}
                {if !isset($HideSchedule) || !$HideSchedule}
                    <div class="schedule-dates d-flex justify-content-center mt-2 fs-5 gap-2">
                        {assign var=TodaysDate value=Date::Now()}
                        <a href="#" class="change-date link-primary" data-year="{$TodaysDate->Year()}"
                            data-month="{$TodaysDate->Month()}" data-day="{$TodaysDate->Day()}" alt="{translate key=Today}"><i
                                class="bi bi-house-fill"></i>
                            <span class="visually-hidden">{translate key=Today}</span>
                        </a>
                        {assign var=FirstDate value=$DisplayDates->GetBegin()}
                        {assign var=LastDate value=$DisplayDates->GetEnd()->AddDays(-1)}
                        <a href="#" class="change-date link-primary" data-year="{$PreviousDate->Year()}"
                            data-month="{$PreviousDate->Month()}" data-day="{$PreviousDate->Day()}"><i
                                class="bi bi-arrow-left-circle-fill"></i></a>
                        {formatdate date=$FirstDate}
                        {if $ShowWeekNumbers}({$FirstDate->WeekNumber()}){/if}
                        -
                        {formatdate date=$LastDate}
                        {if $ShowWeekNumbers}({$LastDate->WeekNumber()}){/if}
                        <a href="#" class="change-date link-primary" data-year="{$NextDate->Year()}" data-month="{$NextDate->Month()}"
                            data-day="{$NextDate->Day()}"><i class="bi bi-arrow-right-circle-fill"></i></a>
                    </div>
                    {if $ShowFullWeekLink}
                        <div class="d-flex justify-content-center fs-5">
                            <a class="link-primary" href="{add_querystring key=SHOW_FULL_WEEK value=1}"
                                id="showFullWeek">({translate key=ShowFullWeek})</a>
                        </div>
                    {/if}
                {/if}
            {/capture}

            {$smarty.capture.date_navigation}
        {/block}

        {if isset($ScheduleAvailabilityEarly) && $ScheduleAvailabilityEarly}
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-triangle-fill"></i> {translate key=ScheduleAvailabilityEarly}
                <a href="#" class="change-date alert-link" data-year="{$ScheduleAvailabilityStart->Year()}"
                    data-month="{$ScheduleAvailabilityStart->Month()}" data-day="{$ScheduleAvailabilityStart->Day()}">
                    {format_date date=$ScheduleAvailabilityStart timezone=$timezone}
                </a> -
                <a href="#" class="change-date alert-link" data-year="{$ScheduleAvailabilityEnd->Year()}"
                    data-month="{$ScheduleAvailabilityEnd->Month()}" data-day="{$ScheduleAvailabilityEnd->Day()}">
                    {format_date date=$ScheduleAvailabilityEnd timezone=$timezone}
                </a>
            </div>
        {/if}

        {if isset($ScheduleAvailabilityLate) && $ScheduleAvailabilityLate}
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {translate key=ScheduleAvailabilityLate}
                <a href="#" class="change-date alert-link" data-year="{$ScheduleAvailabilityStart->Year()}"
                    data-month="{$ScheduleAvailabilityStart->Month()}" data-day="{$ScheduleAvailabilityStart->Day()}">
                    {format_date date=$ScheduleAvailabilityStart timezone=$timezone}
                </a> -
                <a href="#" class="change-date alert-link" data-year="{$ScheduleAvailabilityEnd->Year()}"
                    data-month="{$ScheduleAvailabilityEnd->Month()}" data-day="{$ScheduleAvailabilityEnd->Day()}">
                    {format_date date=$ScheduleAvailabilityEnd timezone=$timezone}
                </a>
            </div>
        {/if}

        {if !isset($HideSchedule) || !$HideSchedule}
            {block name="legend"}
                <div class="schedule-legend mt-3">
                    <div class="d-none d-sm-flex justify-content-center flex-wrap gap-1 text-center">
                        <div
                            class="legend reservable border border-dark-subtle rounded-2 d-flex align-items-center justify-content-center lh-sm py-1">
                            {translate key=Reservable}</div>
                        <div
                            class="legend unreservable border border-dark-subtle rounded-2 d-flex align-items-center justify-content-center lh-sm py-1">
                            {translate key=Unreservable}</div>
                        <div
                            class="legend reserved border border-dark-subtle rounded-2 d-flex align-items-center justify-content-center lh-sm py-1">
                            {translate key=Reserved}</div>
                        {if $LoggedIn}
                            <div
                                class="legend reserved mine border border-dark-subtle rounded-2 d-flex align-items-center justify-content-center lh-sm py-1">
                                {translate key=MyReservation}
                            </div>
                            <div
                                class="legend reserved participating border border-dark-subtle rounded-2 d-flex align-items-center justify-content-center lh-sm py-1">
                                {translate key=Participant}</div>
                        {/if}
                        <div
                            class="legend reserved pending border border-dark-subtle rounded-2 d-flex align-items-center justify-content-center lh-sm py-1">
                            {translate key=Pending}</div>
                        <div
                            class="legend pasttime border border-dark-subtle rounded-2 d-flex align-items-center justify-content-center lh-sm py-1">
                            {translate key=Past}</div>
                        <div
                            class="legend restricted border border-dark-subtle rounded-2 d-flex align-items-center justify-content-center lh-sm py-1">
                            {translate key=Restricted}</div>
                    </div>
                </div>
            {/block}

            <div>
                <a href="#" title="Show Reservation Filter" class="toggle-sidebar link-primary"><i
                        class="bi bi-funnel-fill me-1"></i>{translate key=ResourceFilter}
                    <i id="restore-sidebar" class="bi bi-chevron-double-right"></i></a>
            </div>
            <div class="row g-2">
                <div id="reservations-left" class="col-md-2 col-sm-12">
                    <div class="card h-100">
                        <div
                            class="reservations-left-header card-header d-flex justify-content-between align-items-center px-3 py-2">
                            <h5 class="card-title text-center mb-0">{translate key=Filter}</h5>
                            <a href="#" class="toggle-sidebar link-primary" title="Hide Reservation Filter">
                                <i class="bi bi-x-circle-fill"></i>
                                <span class="visually-hidden">Hide Reservation Filter</span>
                            </a>
                        </div>

                        <div class="reservations-left-content card-body">
                            <form method="get" role="form" id="advancedFilter">

                                {if count($ResourceAttributes) + count($ResourceTypeAttributes) > 5}
                                    <div>
                                        <input type="submit" value="{translate key=Filter}" class="btn btn-primary btn-sm"
                                            {formname key=SUBMIT} />
                                    </div>
                                {/if}

                                <div>
                                    {*<label>{translate key=Resource}</label>*}
                                    <div id="resourceGroups"></div>
                                </div>

                                <div id="resettable">
                                    {if $CanViewUsers}
                                        <div class="form-group mb-2">
                                            <label for="ownerFilter" class="fw-bold">{translate key=Owner}</label>
                                            <input type='search' id='ownerFilter' class="form-control form-control-sm search"
                                                {formname key=OWNER_TEXT} value="{if isset($OwnerText)}{$OwnerText}{/if}" />
                                            <input {formname key=USER_ID} id="ownerId" type="hidden" value="{$OwnerId}" />
                                            <span class="searchclear searchclear-label" ref="ownerFilter,ownerId"></span>
                                        </div>
                                        {if $AllowParticipation}
                                            <div class="form-group mb-2">
                                                <label for="participantFilter" class="fw-bold">{translate key=Participant}</label>
                                                <input type='search' id='participantFilter' class="form-control form-control-sm search"
                                                    {formname key=PARTICIPANT_TEXT}
                                                    value="{if isset($ParticipantText)}{$ParticipantText}{/if}" />
                                                <input {formname key=PARTICIPANT_ID} id="participantId" type="hidden"
                                                    value="{$ParticipantId}" />
                                                <span class="searchclear searchclear-label"
                                                    ref="participantFilter,participantId"></span>
                                            </div>
                                        {/if}
                                    {/if}
                                    <div class="form-group mb-2">
                                        <label for="maxCapactiy" class="fw-bold">{translate key=MinimumCapacity}</label>
                                        <input type='number' min='0' id='maxCapactiy' size='5' maxlength='5'
                                            class="form-control form-control-sm" {formname key=MAX_PARTICIPANTS}
                                            value="{$MaxParticipantsFilter}" />
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="resourceType" class="fw-bold">{translate key=ResourceType}</label>
                                        <select id="resourceType" {formname key=RESOURCE_TYPE_ID}
                                            {formname key=RESOURCE_TYPE_ID} class="form-select form-control-sm">
                                            <option value="">- {translate key=All} -</option>
                                            {object_html_options options=$ResourceTypes label='Name' key='Id' selected=$ResourceTypeIdFilter}
                                        </select>
                                    </div>

                                    {foreach from=$ResourceAttributes item=attribute}
                                        {control type="AttributeControl" attribute=$attribute align='vertical' searchmode=true namePrefix='r' inputClass="form-control-sm" class="customAttribute  mb-2"}
                                    {/foreach}

                                    {foreach from=$ResourceTypeAttributes item=attribute}
                                        {control type="AttributeControl" attribute=$attribute align='vertical' searchmode=true namePrefix='rt' inputClass="form-control-sm" class="customAttribute mb-2"}
                                    {/foreach}

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-block btn-sm"
                                            value="submit">{translate key=Filter}</button>
                                        <button id="show_all_resources" type="button"
                                            class="btn btn-outline-secondary btn-sm">{translate key=ClearFilter}</button>
                                    </div>
                                </div>

                                <input type="hidden" name="sid" value="{$ScheduleId}" />
                                <input type="hidden" name="sds"
                                    value="{foreach from=$SpecificDates item=d}{$d->Format('Y-m-d')},{/foreach}" />
                                <input type="hidden" name="sd" value="{$DisplayDates->GetBegin()->Format('Y-m-d')}" />
                                <input type="hidden" {formname key=SUBMIT} value="true" />
                                <input type="hidden" name="clearFilter" id="clearFilter" value="0" />
                            </form>
                        </div>
                    </div>
                </div>

                <div id="reservations" class="col-md-10 col-sm-12">
                    {block name="reservations"}
                        {include file="Schedule/schedule-reservations-grid.tpl" }
                    {/block}
                </div>
            </div>
        {/if}
    {else}
        <div class="error">{translate key=NoResourcePermission}</div>
    {/if}

    <input type="hidden" value="{$ScheduleId}" id="scheduleId" />

    <div class="mt-2">
        {$smarty.capture.date_navigation}
    </div>
    {assign var=endTime value=microtime(true)}

</div>

<form id="moveReservationForm">
    <input id="moveReferenceNumber" type="hidden" {formname key=REFERENCE_NUMBER} />
    <input id="moveStartDate" type="hidden" {formname key=BEGIN_DATE} />
    <input id="moveResourceId" type="hidden" {formname key=RESOURCE_ID} />
    <input id="moveSourceResourceId" type="hidden" {formname key=ORIGINAL_RESOURCE_ID} />
    {csrf_token}
</form>


<form id="fetchReservationsForm">
    <input type="hidden" {formname key=BEGIN_DATE} value="{formatdate date=$FirstDate key=system}" />
    <input type="hidden" {formname key=END_DATE} value="{formatdate date=$LastDate key=system}" />
    <input type="hidden" {formname key=SCHEDULE_ID} value="{$ScheduleId}" />
    {foreach from=$SpecificDates item=d}
        <input type="hidden" {formname key=SPECIFIC_DATES multi=true} value="{formatdate date=$d key=system}" />
    {/foreach}
    <input type="hidden" {formname key=MIN_CAPACITY} value="{if isset($MinCapacityFilter)}{$MinCapacityFilter}{/if}" />
    <input type="hidden" {formname key=RESOURCE_TYPE_ID} value="{$ResourceTypeIdFilter}" />
    {foreach from=$ResourceAttributes item=attribute}
        <input type="hidden" name="RESOURCE_ATTRIBUTE_ID[{$attribute->Id()}]" value="{$attribute->Value()}" />
    {/foreach}
    {foreach from=$ResourceTypeAttributes item=attribute}
        <input type="hidden" name="RESOURCE_TYPE_ATTRIBUTE_ID[{$attribute->Id()}]" value="{$attribute->Value()}" />
    {/foreach}
    {foreach from=$ResourceIds item=id}
        <input type="hidden" {formname key=RESOURCE_ID multi=true} value="{$id}" />
    {/foreach}
    <input type="hidden" {formname key=USER_ID} value="{$UserIdFilter}" />
    <input type="hidden" {formname key=PARTICIPANT_ID} value="{$ParticipantIdFilter}" />
    {csrf_token}
</form>

<div id="loading-schedule" class="d-none">Loading reservations...</div>

{include file="javascript-includes.tpl" Qtip=true Select2=true Clear=true DatePicker=true}

{block name="scripts-before"}

{/block}

{jsfile src="js/html2canvas.min.js"}
{jsfile src="schedule.js"}
{jsfile src="resourcePopup.js"}
{jsfile src="js/tree.jquery.js"}
{jsfile src="js/jquery.cookie.js"}
{jsfile src="autocomplete.js"}
{jsfile src="ajax-helpers.js"}
<script type="text/javascript">
    let resourceMaxConcurrentReservations = {};
    {foreach from=$Resources item=r}
        resourceMaxConcurrentReservations[{$r->GetId()}] = {$r->MaxConcurrentReservations};
    {/foreach}


    const scheduleOpts = {
        reservationUrlTemplate: "{$Path}{Pages::RESERVATION}?{QueryStringKeys::REFERENCE_NUMBER}=[referenceNumber]",
        summaryPopupUrl: "{$Path}ajax/respopup.php",
        setDefaultScheduleUrl: "{$Path}{Pages::PROFILE}?action=changeDefaultSchedule&{QueryStringKeys::SCHEDULE_ID}=[scheduleId]",
        cookieName: "{$CookieName}",
        scheduleId: "{$ScheduleId|escape:'javascript'}",
        scriptUrl: '{$ScriptUrl}',
        selectedResources: [{$ResourceIds|join:','}],
        specificDates: [{foreach from=$SpecificDates item=d}'{$d->Format('Y-m-d')}',{/foreach}],
        updateReservationUrl: "{$Path}ajax/reservation_move.php",
        lockTableHead: "{if isset($LockTableHead)}{$LockTableHead}{/if}",
        disableSelectable: "{$IsMobile}",
        reservationLoadUrl: "{$Path}{Pages::SCHEDULE}?{QueryStringKeys::DATA_REQUEST}=reservations",
        scheduleStyle: "{$ScheduleStyle}",
        midnightLabel: "{formatdate date=Date::Now()->GetDate() key=period_time}",
        isMobileView: "{$IsMobile && !$IsTablet}",
        newLabel: "{translate key=New}",
        updatedLabel: "{translate key=Updated}",
        isReservable: 1,
        autocompleteUrl: "{$Path}ajax/autocomplete.php?type={AutoCompleteType::User}",
        fastReservationLoad: "{$FastReservationLoad}",
        resourceMaxConcurrentReservations,
        autoScrollToday: {$AutoScrollToday|@json_encode},
        altFormatDate: "{Resources::GetInstance()->GetDateFormat('schedule_daily')}",
    };

    const resourceOrder = [];
    let resourceIndex = 0;
    {foreach from=$Resources item=r}
        resourceOrder[{$r->GetId()}] = resourceIndex++;
    {/foreach}
    scheduleOpts.resourceOrder = resourceOrder;

    {if $LoadViewOnly}
        scheduleOpts.reservationUrlTemplate = "view-reservation.php?{QueryStringKeys::REFERENCE_NUMBER}=[referenceNumber]";
        scheduleOpts.reservationLoadUrl = "{$Path}{Pages::VIEW_SCHEDULE}?{QueryStringKeys::DATA_REQUEST}=reservations";
        scheduleOpts.isReservable = {if $AllowGuestBooking}1{else}0{/if};
    {/if}

    $(document).ready(function() {
        const schedule = new Schedule(scheduleOpts, {$ResourceGroupsAsJson});
        schedule.init();
    });

    $('#schedules').select2({
        width: 'resolve'
    });
</script>

{block name="scripts-after"}

{/block}


{control type="DatePickerSetupControl"
ControlId='datepicker'
AltInput=false
Inline=true
DefaultDate=$FirstDate
NumberOfMonths=$PopupMonths
ShowButtonPanel='true'
OnSelect='dpDateChanged'
FirstDay=$FirstWeekday}

{include file='globalfooter.tpl'}