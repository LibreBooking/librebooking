{include file='globalheader.tpl' }

<div id="page-data-cleanup" class="admin-page">

    <div class="card shadow col-12 col-sm-8 mx-auto">
        <div class="card-body">
            <h1 class="border-bottom mb-3">{translate key=DataCleanup}</h1>

            <!-- Reservations Item -->
            <div class="cleanup-item p-3 border-bottom">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-7">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-calendar-check fs-5 link-primary mt-1 me-2"></i>
                            <div>
                                <h6 class="mb-0 d-inline">{translate key=Reservations}
                                    <span class="badge bg-primary ms-2">{$ReservationCount}</span>
                                </h6>
                                <div class="text-muted small">{translate key=DeleteReservationsBefore}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                            <div class="flex-grow-1">
                                <input type="date" id="reservationDeleteDate" class="form-control form-control-sm"
                                    value="{formatdate date=$DeleteDate format='Y-m-d'}" />
                                <input type="hidden" id="formattedReservationDeleteDate"
                                    value="{formatdate date=$DeleteDate key=system}" />
                            </div>
                            <div style="min-width: 120px;">
                                {delete_button id='deleteReservations' class='btn-sm w-100'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purge Deleted Reservations -->
            <div class="cleanup-item p-3 border-bottom">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-7">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-trash fs-5 text-warning mt-1 me-2"></i>
                            <div>
                                <h6 class="mb-0 d-inline">{translate key=DeletedReservations}
                                    <span class="badge bg-warning text-dark ms-2">{$DeletedReservationCount}</span>
                                </h6>
                                <div class="text-muted small">{translate key=PermanentlyPurgeAllDeletedReservations}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 text-md-end">
                        <button id="purgeReservations" type="button" class="btn btn-sm btn-warning w-100 w-md-auto">
                            <i class="bi bi-eraser me-1"></i>{translate key=Purge}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Blackouts Item -->
            <div class="cleanup-item p-3 border-bottom">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-7">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-clock-history fs-5 link-primary mt-1 me-2"></i>
                            <div>
                                <h6 class="mb-0 d-inline">{translate key=ManageBlackouts}
                                    <span class="badge bg-primary ms-2">{$BlackoutsCount}</span>
                                </h6>
                                <div class="text-muted small">{translate key=DeleteBlackoutsBefore}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                            <div class="flex-grow-1">
                                <input type="date" id="blackoutDeleteDate" class="form-control form-control-sm"
                                    value="{formatdate date=$DeleteDate format='Y-m-d'}" />
                                <input type="hidden" id="formattedBlackoutDeleteDate"
                                    value="{formatdate date=$DeleteDate key=system}" />
                            </div>
                            <div style="min-width: 120px;">
                                {delete_button id='deleteBlackouts' class='btn-sm w-100'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Item -->
            <div class="cleanup-item p-3">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-7">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-person-x fs-5 link-primary mt-1 me-2"></i>
                            <div>
                                <h6 class="mb-0 d-inline">{translate key=Users}
                                    <span class="badge bg-primary ms-2">{$UserCount}</span>
                                </h6>
                                <div class="text-muted small">{translate key=PermanentlyDeleteUsers}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                            <div class="flex-grow-1">
                                <input type="date" id="userDeleteDate" class="form-control form-control-sm"
                                    value="{formatdate date=$DeleteDate format='Y-m-d'}" />
                                <input type="hidden" id="formattedUserDeleteDate"
                                    value="{formatdate date=$DeleteDate key=system}" />
                            </div>
                            <div style="min-width: 120px;">
                                {delete_button id='deleteUsers' class='btn-sm w-100'}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteReservationsDialog" tabindex="-1" role="dialog"
        aria-labelledby="deleteReservationsDialogLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteReservationsForm" method="post" ajaxAction="deleteReservations">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteReservationsDialogLabel">{translate key=Delete}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <div>{translate key=DeleteWarning}</div>
                            <div>
                                <strong><span id="deleteReservationCount"></span></strong>
                                {translate key=ReservationsWillBeDeleted}
                            </div>
                            <input type="hidden" {formname key=BEGIN_DATE} id="formDeleteReservationDate" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {delete_button submit=true}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="purgeReservationsDialog" tabindex="-1" role="dialog"
        aria-labelledby="purgeReservationsDialogLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="purgeReservationsForm" method="post" ajaxAction="purge">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="purgeReservationsDialogLabel">{translate key=Delete}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <div>{translate key=DeleteWarning}</div>
                            <div>
                                <strong>{$DeletedReservationCount}</strong> {translate key=ReservationsWillBePurged}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {delete_button submit=true}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="deleteBlackoutDialog" tabindex="-1" role="dialog"
        aria-labelledby="deleteBlackoutDialogLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteBlackoutsForm" method="post" ajaxAction="deleteBlackouts">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteBlackoutDialogLabel">{translate key=Delete}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <div>{translate key=DeleteWarning}</div>
                            <div>
                                <strong><span id="deleteBlackoutCount"></span></strong>
                                {translate key=BlackoutsWillBeDeleted}
                            </div>
                            <input type="hidden" {formname key=BEGIN_DATE} id="formDeleteBlackoutDate" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {delete_button submit=true}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="deleteUsersDialog" tabindex="-1" role="dialog" aria-labelledby="deleteUsersDialogLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteUsersForm" method="post" ajaxAction="deleteUsers">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUsersDialogLabel">{translate key=Delete}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <div>{translate key=DeleteWarning}</div>
                            <div>
                                <strong><span id="deleteUserCount"></span></strong> {translate key=UsersWillBeDeleted}
                            </div>
                        </div>
                        <input type="hidden" {formname key=BEGIN_DATE} id="formDeleteUserDate" />

                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {delete_button submit=true}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

{include file="javascript-includes.tpl"}
{jsfile src="js/moment.min.js"}
{jsfile src="ajax-helpers.js"}

{control type="DatePickerSetupControl" ControlId="reservationDeleteDate" AltId="formattedReservationDeleteDate"}
{control type="DatePickerSetupControl" ControlId="blackoutDeleteDate" AltId="formattedBlackoutDeleteDate"}
{control type="DatePickerSetupControl" ControlId="userDeleteDate" AltId="formattedUserDeleteDate"}

<script type="text/javascript">
    $(document).ready(function() {
        $('#deleteReservations').click(function(e) {
            $('#formDeleteReservationDate').val($('#formattedReservationDeleteDate').val());
            ajaxGet('{$smarty.server.SCRIPT_NAME}?dr=getReservationCount&date=' + $('#formattedReservationDeleteDate').val(), null, function (data) {
            $('#deleteReservationCount').text(data.count);
            $('#deleteReservationsDialog').modal('show');
        })
    });

    $('#purgeReservations').click(function(e) {
        $('#purgeReservationsDialog').modal('show');
    });

    $('#deleteBlackouts').click(function(e) {
    $('#formDeleteBlackoutDate').val($('#formattedBlackoutDeleteDate').val());
    ajaxGet('{$smarty.server.SCRIPT_NAME}?dr=getBlackoutCount&date=' + $('#formattedBlackoutDeleteDate').val(), null, function (data) {
    $('#deleteBlackoutCount').text(data.count);
    $('#deleteBlackoutDialog').modal('show');
    })
    });

    $('#deleteUsers').click(function(e) {
    $('#formDeleteUserDate').val($('#formattedUserDeleteDate').val());
    ajaxGet('{$smarty.server.SCRIPT_NAME}?dr=getUserCount&date=' + $('#formattedUserDeleteDate').val(), null, function (data) {
    $('#deleteUserCount').text(data.count);
    $('#deleteUsersDialog').modal('show');
    })
    });

    ConfigureAsyncForm($('#deleteReservationsForm'));
    ConfigureAsyncForm($('#purgeReservationsForm'));
    ConfigureAsyncForm($('#deleteBlackoutsForm'));
    ConfigureAsyncForm($('#deleteUsersForm'));
    });
</script>
{include file='globalfooter.tpl'}
