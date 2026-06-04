{extends file="Reservation/create.tpl"}

{block name=header}
    {include file='globalheader.tpl' TitleKey='EditReservationHeading' TitleArgs='' cssFiles='css/schedule.css' printCssFiles='css/reservation.print.css'}
{/block}

{block name=reservationHeader}
    {translate key="EditReservationHeading" args=''}
    {if $RequiresApproval}<span class="pendingApproval text-warning">({translate key=PendingApproval}){/if}
    {/block}

    {block name=submitButtons}
        <div class="btn-group btn-group-sm btnMore">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                <span class="d-none d-sm-inline"><i class="bi bi-plus-circle-fill me-1"></i>{translate key='More'}</span>
                <span class="d-sm-none"><i class="bi bi-three-dots-vertical"></i></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li>
                    {assign var=icsUrl value="{$Path}export/{Pages::CALENDAR_EXPORT}?{QueryStringKeys::REFERENCE_NUMBER}={$ReferenceNumber}"}
                    <a href="{$icsUrl}" download="{$icsUrl}" class="dropdown-item">
                        <span class="bi bi-calendar3"></span>
                        {translate key='AddToOutlook'}</a>
                </li>
                <li>
                    <a href="http://www.google.com/calendar/event?action=TEMPLATE&text={$ReservationTitle|escape:'url'}&dates={formatdate date=$StartDate->ToUtc() key=google}/{formatdate date=$EndDate->ToUtc() key=google}&ctz={$StartDate->Timezone()}&details={$Description|escape:'url'}&location={$Resource->Name|escape:'url'}&trp=false&sprop=&sprop=name:"
                        target="_blank" rel="nofollow" class="dropdown-item">
                        <span class="bi bi-google"></span>
                        {translate key='AddToGoogleCalendar'}</a>
                </li>
                {if $EmailEnabled}
                    <li>
                        <a href="#" class="btnSendEmail dropdown-item">
                            <span class="bi bi-envelope-fill"></span>
                            {translate key='Email'}</a>
                    </li>
                {/if}
                <li>
                    <a href="#" class="btnPrint dropdown-item">
                        <span class="bi bi-printer"></span>
                        {translate key='Print'}</a>
                </li>
                <li>
                    <a href="#" class="btnPDF dropdown-item">
                        <span class="bi bi-filetype-pdf"></span>
                        PDF</a>
                </li>
                <li>
                    <a class="dropdown-item"
                        href="{$Path}{Pages::RESERVATION}?{QueryStringKeys::SOURCE_REFERENCE_NUMBER}={$ReferenceNumber}&{QueryStringKeys::REDIRECT}={$ReturnUrl|urlencode}">
                        <i class="bi bi-files"></i>
                        {translate key='DuplicateReservation'}</a>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    {if $IsRecurring}
                        <a href="#" class="delete prompt dropdown-item">
                            <span class="bi bi-x-lg text-danger remove icon"></span>
                            {translate key='Delete'}
                        </a>
                    {else}
                        <a href="#" class="triggerDeletePrompt delete prompt-single dropdown-item">
                            <span class="bi bi-x-lg text-danger remove icon"></span>
                            {translate key='Delete'}
                        </a>
                    {/if}
                </li>
            </ul>
        </div>


        {if $CheckInRequired && (!$checkinAdminOnly || $CanViewAdmin)}
            <button type="button" class="btn btn-sm btn-warning btnCheckin"><i class="bi bi-box-arrow-in-right"></i>
                {translate key=CheckIn}
                <span class="autoReleaseButtonMessage" data-autorelease-minutes="{$AutoReleaseMinutes}"> -
                    {translate key=ReleasedIn} <span class="autoReleaseMinutes"></span> {translate key=minutes}</span></button>
        {/if}
        {if $CheckOutRequired && (!$checkoutAdminOnly || $CanViewAdmin)}
            <button type="button" class="btn btn-sm btn-warning btnCheckout"><i class="bi bi-box-arrow-in-right"></i>
                {translate key=CheckOut}</button>
        {/if}
        {if $IsRecurring}
            <button type="button" class="btn btn-sm btn-primary update prompt">
                <span class="bi bi-check-circle"></span>
                {translate key='Update'}
            </button>
            <div class="modal fade" id="updateButtons" tabindex="-1" role="dialog" aria-labelledby="updateButtonsLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateButtonsLabel">{translate key=ApplyUpdatesTo}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body">
                            <div id="deleteRecurringButtons" class="d-none mb-3">
                                <div>{translate key=DeleteReminderWarning}</div>
                                <div>
                                    <label for="deleteReasonRecurring">{translate key=Reason} ({translate key=Optional})</label>
                                    <textarea id="deleteReasonRecurring" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-sm-block">
                                <button type="button" class="btn btn-primary save btnUpdateThisInstance">
                                    <span class="bi bi-check-lg"></span>
                                    {translate key='ThisInstance'}
                                </button>
                                <button type="button" class="btn btn-primary save btnUpdateAllInstances">
                                    <span class="bi bi-check-square-fill"></span>
                                    {translate key='AllInstances'}
                                </button>
                                <button type="button" class="btn btn-primary save btnUpdateFutureInstances">
                                    <span class="bi bi-check2-square"></span>
                                    {translate key='FutureInstances'}
                                </button>
                                <button type="button" class="btn btn-outline-secondary">
                                    {translate key='Cancel'}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        {else}

            <button type="button" class="btn btn-sm btn-primary save update btnEdit">
                <span class="bi bi-check-circle"></span>
                {translate key='Update'}
            </button>
        {/if}

        <div id="deleteButtonPrompt" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateButtonsLabel">{translate key=Delete}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div>{translate key=DeleteReminderWarning}</div>
                        <div>
                            <label class="fw-bold" for="deleteReason">{translate key=Reason}
                                ({translate key=Optional})</label>
                            <textarea class="deleteReason form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button class="cancelDelete cancel"}
                        {delete_button class="confirmDelete delete save"}
                    </div>
                </div>
            </div>
        </div>
    {/block}

    {block name="ajaxMessage"}
        {translate key=UpdatingReservation}...
    {/block}

    {block name='attachments'}
        {if $Attachments|default:array()|count > 0}
            <div id="attachmentDiv" class="res-attachments border-top mt-2 pt-2">
                <span class="heading fw-bold">{translate key=Attachments} ({$Attachments|default:array()|count})</span>
                <a href="#" class="remove text-danger" id="btnRemoveAttachment">({translate key="Remove"})</a>
                <br />
                {foreach from=$Attachments item=attachment}
                    {assign var=attachmentUrl value="attachments/{Pages::RESERVATION_FILE}?{QueryStringKeys::ATTACHMENT_FILE_ID}={$attachment->FileId()}&{QueryStringKeys::REFERENCE_NUMBER}={$ReferenceNumber}"}
                    <a href="{$attachmentUrl|escape:'html'}" download="{$attachmentUrl|escape:'html'}" target="_blank"
                        class="link-primary">{$attachment->FileName()|escape:'html'}</a>
                    <input style='display: none;' type="checkbox" name="{FormKeys::REMOVED_FILE_IDS}[{$attachment->FileId()}]" />
                {/foreach}
            </div>
            <div class="modal-body">
                <div>{translate key=DeleteReminderWarning}</div>
                <div>
                    <label for="deleteReason">{translate key=Reason} ({translate key=Optional})</label>
                    <textarea id="deleteReason" class="deleteReason form-control"></textarea>
                {/if}
            {/block}

            {block name=extras}
                {if $AutoReleaseMinutes != null}
                    <input type="hidden" id="autoReleaseMinutes" value="{$AutoReleaseMinutes}" />
                {/if}

                <div class="modal fade" id="emailReservationPrompt" tabindex="-1" role="dialog"
                    aria-labelledby="emailReservationLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form id="emailReservationForm" method="post" role="form" onkeypress="return event.keyCode != 13;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="emailReservationLabel">{translate key=EmailReservation}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-hidden="true"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="emailUserAutocomplete"
                                            class="visually-hidden">{translate key=User}</label>
                                        <input type="search" id="emailUserAutocomplete" name="email" class="form-control"
                                            placeholder="{translate key=Email}" autofocus="autofocus" />
                                    </div>

                                    <div id="emailReservationList">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="btnSendReservation">
                                        <span class="bi bi-envelope-fill"></span>
                                        {translate key='Email'}
                                    </button>
                                    {cancel_button}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
{/block}
