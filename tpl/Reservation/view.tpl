{include file='globalheader.tpl' TitleKey='ViewReservationHeading' printCssFiles='css/reservation.print.css'}
<div id="page-view-reservation">
    <div id="reservation-box" class="card shadow readonly">
        <div id="reservationFormDiv" class="card-body">
            <div class="row">
                {*CHECK IF USER HAS PERMISSIONS TO THE RESOURCES OF THE RESERVATIONS, HIDE DETAILS IF HE DOESN'T HAVE PERMISSIONS TO ALL OF THEM*}
                {assign var=isResourcePermitted value=in_array($ResourceId,$CanViewResourceReservations)}
                {foreach from=$AdditionalResourceIds item=checkAditionalResourcePermissionsId}
                    {if in_array($checkAditionalResourcePermissionsId, $CanViewResourceReservations)}
                        {assign var=isResourcePermitted value=true}
                        {break};
                    {/if}
                {{/foreach}}
                {*HOWEVER THE USER CAN SEE THE RESERVATION IF HE IS A OWNER, PARTICIPANT OR INVITEE*}
                {if $isResourcePermitted == false}
                    {if $UserId == ServiceLocator::GetServer()->GetUserSession()->UserId || $IAmParticipating || $IAmInvited}
                        {assign var=isResourcePermitted value=true}
                    {/if}
                {/if}

                {assign var="detailsCol" value="col-12"}
                {assign var="participantCol" value="col-12"}

                {if $ShowParticipation && $AllowParticipation && $ShowReservationDetails && $isResourcePermitted}
                    {assign var="detailsCol" value="col-12 col-sm-6"}
                    {assign var="participantCol" value="col-12 col-sm-6"}
                {/if}

                <div id="reservationDetails" class="{$detailsCol}">
                    <div class="col-12">
                        <label class="fw-bold">{translate key='User'}</label>
                        {if $ShowUserDetails && $ShowReservationDetails && $isResourcePermitted}
                            <a href="#" class="bindableUser link-primary" data-userid="{$UserId}">{$ReservationUserName}</a>
                            <input id="userId" type="hidden" value="{$UserId}" />
                        {else}
                            {translate key=Private}
                        {/if}
                    </div>

                    <div class="row">
                        <div class="col-md-6 no-padding-left">
                            <label class="fw-bold">{translate key='BeginDate'}</label> {formatdate date=$StartDate}
                            <input type="hidden" id="formattedBeginDate"
                                value="{formatdate date=$StartDate key=system}" />
                            {foreach from=$StartPeriods item=period}
                                {if $period eq $SelectedStart}
                                    {$period->Label()}
                                    <input type="hidden" id="BeginPeriod" value="{$period->Begin()}" />
                                {/if}
                            {/foreach}
                        </div>
                        <div class="col-md-6 no-padding-left">
                            <label class="fw-bold">{translate key='EndDate'}</label> {formatdate date=$EndDate}
                            <input type="hidden" id="formattedEndDate" value="{formatdate date=$EndDate key=system}" />
                            {foreach from=$EndPeriods item=period}
                                {if $period eq $SelectedEnd}
                                    {$period->LabelEnd()}
                                    <input type="hidden" id="EndPeriod" value="{$period->End()}" />
                                {/if}
                            {/foreach}
                        </div>
                    </div>

                    <div class="col-12">
                        {*<span class="like-label class="fw-bold"">{translate key=ReservationLength}</span>*}
                        <span class="durationText">
                            <span id="durationDays">0</span> {translate key=days}
                            <span id="durationHours">0</span> {translate key=hours}
                            <span id="durationMinutes">0</span> {translate key=minutes}
                        </span>
                    </div>

                    <div class="col-12">
                        <label class="fw-bold">{translate key='RepeatPrompt'}</label>
                        {translate key=$RepeatOptions[$RepeatType]['key']}
                        {if $IsRecurring}
                            <div class="repeat-details">
                                <label class="fw-bold">{translate key='RepeatEveryPrompt'}</label> {$RepeatInterval}
                                {$RepeatOptions[$RepeatType]['everyKey']}
                                {if $RepeatMonthlyType neq ''}
                                    ({$RepeatMonthlyType})
                                {/if}
                                {if (is_array($RepeatWeekdays) && count($RepeatWeekdays) gt 0)}
                                    <br />
                                    <label class="fw-bold">{translate key='RepeatDaysPrompt'}</label>
                                    {foreach from=$RepeatWeekdays item=day}{translate key=$DayNames[$day]} {/foreach}
                                {/if}
                                <br /><label class="fw-bold">{translate key='RepeatUntilPrompt'}</label>
                                {formatdate date=$RepeatTerminationDate}
                            </div>
                        {/if}
                    </div>

                    <div class="col-12 clearfix">
                        <div class="float-start">
                            <label class="fw-bold">{translate key='Resources'}</label> {$ResourceName}
                            <input id="primaryResourceId" type="hidden" value="{$ResourceId}" />
                            <div id="additionalResources" class="inline">

                                {foreach from=$AvailableResources item=resource}
                                    {if is_array($AdditionalResourceIds) && in_array($resource->Id, $AdditionalResourceIds)}
                                        ,{$resource->Name}
                                        <input class="resourceId" type="hidden" value="{$resource->Id}" />
                                    {/if}
                                {/foreach}
                                {if !$isResourcePermitted}
                                    <p class="text-danger">{translate key='NoResourcePermissions'}</p>
                                {/if}
                            </div>
                        </div>
                        <div class="float-end">
                            {if $ShowReservationDetails && $isResourcePermitted}
                                <label class="fw-bold">{translate key='Accessories'}</label>
                                {foreach from=$Accessories item=accessory name=accessoryLoop}
                                    <span class="badge bg-secondary quantity">{$accessory->QuantityReserved}</span>
                                    {if $smarty.foreach.accessoryLoop.last}
                                        {$accessory->Name}
                                    {else}
                                        {$accessory->Name},
                                    {/if}
                                {/foreach}
                            {/if}
                        </div>
                    </div>

                    {if $ShowReservationDetails && $isResourcePermitted}
                        <div class="col-12">
                            <label class="fw-bold">{translate key='ReservationTitle'}</label>
                            {if $ReservationTitle neq ''}
                                {$ReservationTitle}
                            {else}
                                <span class="no-data fst-italic">{translate key='None'}</span>
                            {/if}
                        </div>
                        <div class="col-12">
                            <label class="fw-bold">{translate key='ReservationDescription'}</label>
                            {if $Description neq ''}
                                <br />
                                {$Description|nl2br}
                            {else}
                                <span class="no-data fst-italic">{translate key='None'}</span>
                            {/if}
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="fw-bold">{translate key=ReferenceNumber}</label>
                                {$ReferenceNumber}
                            </div>
                        </div>
                    {/if}
                </div>

                {if $ShowParticipation && $AllowParticipation && $ShowReservationDetails && $isResourcePermitted}
                    <div class="{$participantCol}">
                        <div id="reservationParticipation">
                            <div id="participationAction" class="participationAction">
                                {if $IAmParticipating && $CanAlterParticipation}
                                    <div class="alert alert-info" role="alert">
                                        <span class="fw-bold">{translate key=YouAreAParticipant}</span>
                                        {if $IsRecurring}
                                            <button value="{InvitationAction::CancelAll}"
                                                class="btn btn-sm btn-info participationAction">
                                                <i class="bi bi-dash-square-fill"></i> {translate key=AllInstances}
                                            </button>
                                            <button value="{InvitationAction::CancelInstance}"
                                                class="btn btn-sm btn-info participationAction">
                                                <i class="bi bi-dash-square-fill"></i> {translate key=ThisInstance}
                                            </button>
                                        {else}
                                            <button value="{InvitationAction::CancelInstance}"
                                                class="btn btn-sm btn-info participationAction">
                                                <i class="bi bi-dash-square-fill"></i> {translate key=CancelParticipation}
                                            </button>
                                        {/if}
                                    </div>
                                {/if}
                            </div>

                            <div id="invitationAction" class="participationAction">
                                {if $IAmInvited && $CanAlterParticipation}
                                    <div class="alert alert-info" role="alert">
                                        <span class="fw-bold">{translate key=YouAreInvited}</span>
                                        <button value="{InvitationAction::Accept}"
                                            class="btn btn-sm btn-info participationAction">
                                            <i class="bi bi-person-fill-check"></i> {translate key="Attending"}
                                        </button>
                                        <button value="{InvitationAction::Decline}"
                                            class="btn btn-sm btn-danger  participationAction">
                                            <i class="bi bi-person-fill-x"></i> {translate key="NotAttending"}
                                        </button>
                                    </div>
                                {/if}
                            </div>

                            <div id="joinReservation" class="participationAction">
                                {if $AllowParticipantsToJoin && !$IAmParticipating && !$IAmInvited && $CanAlterParticipation}
                                    <div class="alert alert-info " role="alert">
                                        <span class="fw-bold">{translate key=YouCanJoinThisReservation}</span>
                                        {if $IsRecurring}
                                            <button value="{InvitationAction::JoinAll}" id="btnJoinSeries"
                                                class="btn btn-sm btn-info participationAction">
                                                <i class="bi bi-person-fill-check"></i> {translate key="AllInstances"}
                                            </button>
                                            <button value="{InvitationAction::Join}" id="btnJoinInstance"
                                                class="btn btn-sm btn-info participationAction">
                                                <i class="bi bi-person-fill-check"></i> {translate key="ThisInstance"}
                                            </button>
                                        {else}
                                            <button value="{InvitationAction::Join}" id="btnJoin"
                                                class="btn btn-sm btn-info participationAction">
                                                <i class="bi bi-person-fill-check"></i> {translate key="Join"}
                                            </button>
                                        {/if}
                                    </div>
                                {/if}
                            </div>

                            <span id="participate-indicator" class="spinner-border spinner-border-sm" role="status"
                                style="display:none;"></span>

                            {if $ShowUserDetails && $isResourcePermitted}
                                <div id="ro-participantList">
                                    <label class="fw-bold">{translate key='ParticipantList'}</label>
                                    {foreach from=$Participants item=participant}
                                        <div><a href="#" class="bindableUser link-primary"
                                                data-userid="{$participant->UserId}">{$participant->FullName}</a></div>
                                    {foreachelse}
                                        <div class="no-data fst-italic">{translate key='None'}</div>
                                    {/foreach}
                                </div>
                                <div id="ro-inviteeList">
                                    <label class="fw-bold">{translate key='InvitationList'}</label>
                                    {foreach from=$Invitees item=invitee}
                                        <div><a href="#" class="bindableUser link-primary"
                                                data-userid="{$invitee->UserId}">{$invitee->FullName}</a></div>
                                    {foreachelse}
                                        <div class="no-data fst-italic">{translate key='None'}</div>
                                    {/foreach}
                                </div>
                            {/if}
                        </div>
                    </div>
                {/if}

                <div class="col-12 same-height">
                    <div id="custom-attributes-placeholder" class="">
                    </div>
                </div>

                {if $ShowReservationDetails && $isResourcePermitted}
                    <div class="clearfix buttons">
                        <div class="float-end">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="window.location='{$ReturnUrl}'">
                                {translate key='Close'}
                            </button>

                            {block name="deleteButtons"}
                                {if $CanJoinWaitList}
                                    <button id="btnWaitList" class="btn btn-outline-secondary"><i class="bi bi-bell-fill"></i>
                                        {translate key='NotifyWhenAvailable'}
                                    </button>
                                {/if}

                                {assign var=icsUrl value="{$Path}export/{Pages::CALENDAR_EXPORT}?{QueryStringKeys::REFERENCE_NUMBER}={$ReferenceNumber}"}
                                <a href="{$icsUrl}" download="{$icsUrl}" class="btn btn-outline-secondary">
                                    <i class="bi bi-calendar3"></i>
                                    {translate key=AddToOutlook}</a>
                                <a href="http://www.google.com/calendar/event?action=TEMPLATE&text={$ReservationTitle|escape:'url'}&dates=
                                    {formatdate date=$StartDate->ToUtc() key=google}/
                                    {formatdate date=$EndDate->ToUtc() key=google}&ctz={$StartDate->Timezone()}&details={$Description|escape:'url'}&location={$Resource->Name|escape:'url'}&trp=false&sprop=&sprop=name:"
                                    target="_blank" rel="nofollow" class="btn btn-outline-secondary">
                                    <i class="bi bi-google"></i>
                                    {translate key=AddToGoogleCalendar}</a>
                                <button type="button" class="btnPrint btn btn-outline-secondary">
                                    <i class="bi bi-printer"></i>
                                    {translate key='Print'}</button>
                                <button type="button" class="btnPDF btn btn-outline-secondary">
                                    <i class="bi bi-filetype-pdf"></i>
                                    PDF</button>
                            {/block}

                            {block name="submitButtons"}
                                {if $CheckInRequired && (!$checkinAdminOnly || $CanViewAdmin)}
                                    <button type="button" class="btn btn-warning btnCheckin"><i
                                            class="bi bi-box-arrow-in-right"></i>
                                        {translate key=CheckIn}<span class="autoReleaseButtonMessage"
                                            data-autorelease-minutes="{$AutoReleaseMinutes}"> - {translate key=ReleasedIn}
                                            <span class="autoReleaseMinutes"></span> {translate key=minutes}</span></button>
                                {/if}
                                {if $CheckOutRequired && (!$checkoutAdminOnly || $CanViewAdmin)}
                                    <button type="button" class="btn btn-warning btnCheckout"><i
                                            class="bi bi-box-arrow-in-left"></i>
                                        {translate key=CheckOut}</button>
                                {/if}
                            {/block}
                        </div>
                    </div>
                {/if}
            </div>
            {if $ShowReservationDetails && $isResourcePermitted}
                {if $Attachments|default:array()|count > 0}
                    <div class="col-12">
                        <div class="res-attachments border-top mt-2 pt-2">
                            <span class="heading fw-bold">{translate key=Attachments}
                                ({$Attachments|default:array()|count})</span>
                            <br />
                            {foreach from=$Attachments item=attachment}
                                {assign var=attachmentUrl value="attachments/{Pages::RESERVATION_FILE}?{QueryStringKeys::ATTACHMENT_FILE_ID}={$attachment->FileId()}&{QueryStringKeys::REFERENCE_NUMBER}={$ReferenceNumber}"}
                                <a href="{$attachmentUrl}" download="{$attachmentUrl}" target="_blank"
                                    class="link-primary">{$attachment->FileName()}</a>
                            {/foreach}
                        </div>
                    </div>
                {/if}
            {/if}
            <input type="hidden" id="referenceNumber" {formname key=reference_number} value="{$ReferenceNumber}" />
        </div>
    </div>

    <div id="wait-box" class="modal fade" aria-labelledby="update-boxLabel" data-bs-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="creatingNotification" class="text-center">
                        <h3 id="createUpdateMessage" class="d-none">
                            {block name="ajaxMessage"}
                                {translate key=UpdatingReservation}
                            {/block}
                        </h3>
                        <h3 id="joiningWaitingList" class="d-none">
                            {translate key=AddingToWaitlist}
                        </h3>
                        <h3 id="checkingInMessage" class="d-none">
                            {translate key=CheckingIn}
                        </h3>
                        <h3 id="checkingOutMessage" class="d-none">
                            {translate key=CheckingOut}
                        </h3>
                        <div class="spinner-border text-secondary" style="width: 3rem; height: 3rem;" role="status">
                        </div>
                    </div>
                    <div id="result" class="text-center"></div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: none">
        <form id="form-reservation" method="post" enctype="application/x-www-form-urlencoded">
            <input type="hidden" {formname key=RESERVATION_ID} value="{$ReservationId}" />
            <input type="hidden" {formname key=REFERENCE_NUMBER} value="{$ReferenceNumber}" />
            <input type="hidden" {formname key=RESERVATION_ACTION} value="{$ReservationAction}" />
            <input type="hidden" {formname key=SERIES_UPDATE_SCOPE} id="hdnSeriesUpdateScope"
                value="{SeriesUpdateScope::FullSeries}" />
            <input type="hidden" {formname key=DELETE_REASON} value="" id="hdnDeleteReason" />
            <input type="hidden" {formname key=BEGIN_DATE} value="{formatdate date=$StartDate key=system}" />
            <input type="hidden" {formname key=BEGIN_PERIOD} value="{$SelectedStart->Begin()}" />
            <input type="hidden" {formname key=END_DATE} value="{formatdate date=$EndDate key=system}" />
            <input type="hidden" {formname key=END_PERIOD} value="{$SelectedEnd->End()}" />
            <input type="hidden" {formname key=USER_ID} value="{$UserId}" />
            <input type="hidden" {formname key=RESOURCE_ID} value="{$ResourceId}" />
            {csrf_token}
        </form>
    </div>
</div>

{include file="javascript-includes.tpl"}
{jsfile src="participation.js"}
{jsfile src="approval.js"}
{jsfile src="resourcePopup.js"}
{jsfile src="userPopup.js"}
{jsfile src="date-helper.js"}
{jsfile src="recurrence.js"}
{jsfile src="reservation.js"}
{jsfile src="autocomplete.js"}
{jsfile src="force-numeric.js"}
{jsfile src="reservation-reminder.js"}
{jsfile src="ajax-helpers.js"}
{jsfile src="js/tree.jquery.js"}

{include file="Reservation/pdf_libraries.tpl"}

<script type="text/javascript">
    $(document).ready(function() {

        var participationOptions = {
            responseType: 'json'
        };

        var participation = new Participation(participationOptions);
        participation.initReservation();

        var approvalOptions = {
            responseType: 'json',
            url: "{$Path}ajax/reservation_approve.php",
            returnUrl: '{$ReturnUrl}'
        };

        var approval = new Approval(approvalOptions);
        approval.initReservation();

        var scopeOptions = {
            instance: '{SeriesUpdateScope::ThisInstance}',
            full: '{SeriesUpdateScope::FullSeries}',
            future: '{SeriesUpdateScope::FutureInstances}'
        };

        var reservationOpts = {
            returnUrl: '{$ReturnUrl}',
            scopeOpts: scopeOptions,
            deleteUrl: 'ajax/reservation_delete.php',
            checkinUrl: 'ajax/reservation_checkin.php?action={ReservationAction::Checkin}',
            checkoutUrl: 'ajax/reservation_checkin.php?action={ReservationAction::Checkout}',
            userAutocompleteUrl: "ajax/autocomplete.php?type={AutoCompleteType::User}",
            changeUserAutocompleteUrl: "ajax/autocomplete.php?type={AutoCompleteType::MyUsers}",
            waitlistUrl: 'ajax/reservation_waitlist.php',
        };
        var reservation = new Reservation(reservationOpts);
        reservation.init('{$UserId}');

        var ajaxOptions = {
            target: '#result', // target element(s) to be updated with server response
            beforeSubmit: reservation.preSubmit, // pre-submit callback
            success: reservation.showResponse // post-submit callback
        };

        $('#form-reservation').submit(function() {
            $(this).ajaxSubmit(ajaxOptions);
            return false;
        });

        // jsPDF
        {include file="Reservation/pdf.tpl"}
        //
    });
</script>
{include file='globalfooter.tpl'}
