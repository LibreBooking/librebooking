{extends file="Reservation/view.tpl"}

{block name=header}
    {include file='globalheader.tpl' TitleKey='EditReservationHeading'}
{/block}

{block name=reservationHeader}
    {translate key="EditReservationHeading"}
{/block}

{block name=deleteButtons}
    <div class="btn-group">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            {translate key=More}
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>
                {assign var=icsUrl value="{$Path}export/{Pages::CALENDAR_EXPORT}?{QueryStringKeys::REFERENCE_NUMBER}={$ReferenceNumber}"}
                <a href="{$icsUrl}" download="{$icsUrl}" class="dropdown-item">
                    <i class="bi bi-calendar3 me-1"></i>{translate key=AddToOutlook}</a>
            </li>
            <li><a href="#" class="btnPrint dropdown-item">
                    <i class="bi bi-printer me-1"></i>{translate key='Print'}</a>
            </li>
            <li><a href="#" class="btnPDF dropdown-item">
                    <i class="bi bi-filetype-pdf me-1"></i>PDF</a>
            </li>
        </ul>
    </div>

    <div id="deleteButtonPrompt" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateButtonsLabel">{translate key=Reject}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div>{translate key=DeleteReminderWarning}</div>
                    <div>
                        <label for="deleteReason" class="fw-bold">{translate key=Reason} ({translate key=Optional})</label>
                        <textarea id="deleteReason" class="deleteReason form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    {cancel_button class="cancelDelete cancel"}
                    {delete_button class="confirmDelete delete save" key=Reject}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateButtons" tabindex="-1" role="dialog" aria-labelledby="updateButtonsLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateButtonsLabel">{translate key=ApplyUpdatesTo}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div id="deleteRecurringButtons" class="no-show margin-bottom-15">
                        <div>{translate key=DeleteReminderWarning}</div>
                        <div>
                            <label for="deleteReasonRecurring">{translate key=Reason} ({translate key=Optional})</label>
                            <textarea class="deleteReasonRecurring form-control"></textarea>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary save btnUpdateThisInstance">
                        <i class="bi bi-check-lg me-1"></i>{translate key='ThisInstance'}
                    </button>
                    <button type="button" class="btn btn-primary save btnUpdateAllInstances">
                        <i class="bi bi-check-square-fill"></i>{translate key='AllInstances'}
                    </button>
                    <button type="button" class="btn btn-primary save btnUpdateFutureInstances">
                        <i class="bi bi-check2-square me-1"></i>{translate key='FutureInstances'}
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        {translate key='Cancel'}
                    </button>
                </div>
            </div>
        </div>
    </div>

{/block}

{block name=submitButtons}
    <a href="{$SCRIPT_NAME}?{QueryStringKeys::REFERENCE_NUMBER}={$ReferenceNumber}&update=1&{QueryStringKeys::REDIRECT}={$ReturnUrl|escape:url}"
        class="btn btn-outline-secondary" id="btnApprovalUpdate">
        <span class=""></span>
        {translate key='Update'}
    </a>
    <button type="button"
        class="btn btn-danger {if $IsRecurring}delete prompt{else}triggerDeletePrompt delete prompt-single{/if}">
        <span class="bi bi-x-lg"></span>
        {translate key='Reject'}
    </button>
    <button type="button" class="btn btn-primary" id="btnApprove">
        <span class="bi bi-check-circle"></span>
        {translate key='Approve'}
    </button>
{/block}

{block name="ajaxMessage"}
    {translate key=UpdatingReservation}...
    <br />
{/block}