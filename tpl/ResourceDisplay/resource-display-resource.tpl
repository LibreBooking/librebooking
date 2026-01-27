{function name=displayReservation}
    <div class="card shadow-sm">
        <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">
                    {format_date date=$reservation->StartDate() key=res_popup_time timezone=$Timezone}
                    – {format_date date=$reservation->EndDate() key=res_popup_time timezone=$Timezone}
                </small>
                <span class="badge bg-primary">
                    {$reservation->GetUserName()}
                </span>
            </div>
            <div class="fw-bold text-truncate" title="{$reservation->GetTitle()|default:$NoTitle}">
                {$reservation->GetTitle()|default:$NoTitle}
            </div>
        </div>
    </div>
{/function}

<div id="resource-display" class="resource-display">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title clearfix pb-5">
                <div class="float-start">
                    {if $AvailableNow}
                        <div class="text-success">{$ResourceName}:<i
                                class="bi bi-check-circle-fill mx-1"></i>{translate key=Available}</div>
                    {else}
                        <div class="text-warning">{$ResourceName}:<i
                                class="bi bi-exclamation-circle-fill mx-1"></i>{translate key=Unavailable}</div>
                    {/if}
                </div>
                <div class="float-end">
                    <div class="date">{formatdate date=$ReservationDate key=schedule_daily timezone=$Timezone}</div>
                </div>
            </h4>
            <div class="row pt-5">
                {if $CurrentReservation != null}
                    {assign var="colClass" value="col-md-4"}
                    <div class="{$colClass}">
                        <div class="fw-bold text-uppercase fs-6 text-secondary mb-2">
                            {translate key=CurrentReservation}
                        </div>
                        {call name=displayReservation reservation=$CurrentReservation}
                    </div>
                {else}
                    {assign var="colClass" value="col-md-6"}
                {/if}

                <div class="{$colClass}">
                    <div class="fw-bold text-uppercase fs-6 text-secondary mb-2">
                        {translate key=NextReservation}
                    </div>

                    {if $NextReservation != null}
                        {call name=displayReservation reservation=$NextReservation}
                    {else}
                        <div class="alert alert-light text-muted p-2 small mb-3">
                            {translate key=None}
                        </div>
                    {/if}

                    {if $RequiresCheckin}
                        <form role="form" method="post" id="formCheckin"
                            action="{$smarty.server.SCRIPT_NAME}?action=checkin" class="d-inline">
                            <input type="hidden" {formname key=REFERENCE_NUMBER} value="{$CheckinReferenceNumber}" />
                            <button type="submit" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-check-lg"></i> {translate key=CheckIn}
                            </button>
                        </form>
                    {/if}
                </div>

                <div class="{$colClass}">
                    <div class="fw-bold text-uppercase fs-6 text-secondary mb-2">
                        {translate key=UpcomingReservations}
                    </div>

                    {if $UpcomingReservations|default:array()|count > 0}
                        {foreach from=$UpcomingReservations item=r name=upcoming}
                            {call name=displayReservation reservation=$r}
                            {if !$smarty.foreach.upcoming.last}
                                <hr class="my-3 text-muted">
                            {/if}
                        {/foreach}
                    {else}
                        <div class="alert alert-light text-muted p-2 small">
                            {translate key=None}
                        </div>
                    {/if}
                </div>
                {if $AllowReservations}
                    <div class="col-12 mt-3">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#reservation-box">
                                <i class="bi bi-plus-lg me-1"></i>{translate key=Reserve}
                            </button>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="reservation-box" tabindex="-1">
                            <div class="modal-dialog modal-fullscreen">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">{translate key=Reserve}</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form role="form" method="post" id="formReserve"
                                            action="{$smarty.server.SCRIPT_NAME}?action=reserve">
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div id="validationErrors"
                                                        class="validationSummary alert alert-danger d-none">
                                                        <ul></ul>
                                                    </div>
                                                    <div>
                                                        <table class="reservations my-5">
                                                            <thead>
                                                                <tr>
                                                                    {foreach from=$DailyLayout->GetPeriods($ReservationDate, true) item=period}
                                                                        <td class="reslabel" colspan="{$period->Span()}">
                                                                            {$period->Label($ReservationDate)}
                                                                        </td>
                                                                    {/foreach}
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    {assign var=slots value=$DailyLayout->GetLayout($ReservationDate, $ResourceId)}
                                                                    {foreach from=$slots item=slot}
                                                                        {assign var="referenceNumber" value=""}
                                                                        {if $slot->IsReserved()}
                                                                            {assign var="class" value="reserved"}
                                                                            {assign var="referenceNumber" value=$slot->Reservation()->ReferenceNumber}
                                                                        {elseif $slot->IsReservable()}
                                                                            {assign var="class" value="reservable"}
                                                                            {if $slot->IsPastDate(Date::Now())}
                                                                                {assign var="class" value="pasttime"}
                                                                            {/if}
                                                                        {else}
                                                                            {assign var="class" value="unreservable"}
                                                                        {/if}
                                                                        {if $slot->HasCustomColor()}
                                                                            {assign var=color value='style="background-color:'|cat:$slot->Color()|cat:';color:'|cat:$slot->TextColor()|cat:';"'}
                                                                        {/if}
                                                                        <td colspan="{$slot->PeriodSpan()}" {$color}
                                                                            data-begin="{$slot->Begin()}"
                                                                            data-end="{$slot->End()}" class="slot {$class}"
                                                                            data-refnum="{$referenceNumber}">
                                                                            {$slot->Label($SlotLabelFactory)|escape|default:'&nbsp;'}
                                                                        </td>
                                                                    {/foreach}
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-text" id="email-addon"><span
                                                                class="bi bi-envelope"></span></span>
                                                        <label for="emailAddress"
                                                            class="visually-hidden">{translate key=Email}</label>
                                                        <input id="emailAddress" type="email" class="form-control"
                                                            placeholder="{translate key=Email}"
                                                            aria-describedby="email-addon" required="required"
                                                            {formname key=EMAIL} />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-6">
                                                    <div class="input-group input-group-lg has-feedback">
                                                        <span class="input-group-text" id="starttime-addon">
                                                            <span class="bi bi-clock"></span>
                                                        </span>
                                                        <select title="Begin" class="form-select"
                                                            aria-describedby="starttime-addon" id="beginPeriod"
                                                            {formname key=BEGIN_PERIOD}>
                                                            {foreach from=$slots item=slot}
                                                                {if $slot->IsReservable() && !$slot->IsPastDate($ReservationDate)}
                                                                    <option value="{$slot->Begin()}">
                                                                        {$slot->Begin()->Format($TimeFormat)}</option>
                                                                {/if}
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-text" id="endtime-addon"><span
                                                                class="bi bi-clock"></span></span>
                                                        <select title="End" class="form-select input-lg"
                                                            aria-describedby="endtime-addon" id="endPeriod"
                                                            {formname key=END_PERIOD}>
                                                            {foreach from=$slots item=slot}
                                                                {if $slot->IsReservable() && !$slot->IsPastDate($ReservationDate)}
                                                                    <option value="{$slot->End()}">
                                                                        {$slot->End()->Format($TimeFormat)}
                                                                    </option>
                                                                {/if}
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {if $Terms != null}
                                                <div class="mt-3" id="termsAndConditions">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="termsAndConditionsAcknowledgement"
                                                            {formname key=TOS_ACKNOWLEDGEMENT}
                                                            {if $TermsAccepted}checked="checked" {/if} />
                                                        <label class="form-check-label"
                                                            for="termsAndConditionsAcknowledgement">{translate key=IAccept}</label>
                                                        <a href="{$Terms->DisplayUrl()}" class="link-primary"
                                                            target="_blank">{translate key=TheTermsOfService}</a>
                                                    </div>
                                                </div>
                                            {/if}

                                            {if $Attributes|default:array()|count > 0}
                                                <div class="mt-3">
                                                    <div class="customAttributes row gy-3">
                                                        {foreach from=$Attributes item=attribute name=attributeEach}
                                                            <div class="customAttribute col-6">
                                                                {control type="AttributeControl" attribute=$attribute}
                                                            </div>
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            {/if}

                                            <div class="d-grid gap-2 mt-3">
                                                <input type="submit" class="action-reserve btn btn-primary"
                                                    value="{translate key=Reserve}" />
                                                <a href="#" class="action-cancel btn btn-outline-secondary"
                                                    data-bs-dismiss="modal" id="reserveCancel">{translate key=Cancel}</a>
                                            </div>

                                            <input type="hidden" {formname key=RESOURCE_ID} value="{$ResourceId}" />
                                            <input type="hidden" {formname key=SCHEDULE_ID} value="{$ScheduleId}" />
                                            <input type="hidden" {formname key=TIMEZONE} value="{$Timezone}" />
                                            <input type="hidden" {formname key=BEGIN_DATE} value="{$ReservationDate}" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {/if}
            </div>
        </div>
    </div>
</div>