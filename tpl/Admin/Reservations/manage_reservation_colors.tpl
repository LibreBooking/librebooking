{include file='globalheader.tpl' DataTable=true}

<div id="page-manage-reservation-colors" class="admin-page">

    <h1 class="border-bottom mb-3">{translate key=ReservationColors}</h1>
    <div class="card shadow mb-2 default-box">
        <div class="card-body">
            <form class="form-inline" role="form">
                <div class="d-flex align-items-center">
                    <label class="fw-bold" for="attributeOption">{translate key=Attribute}</label>
                    <select class="form-select w-auto mx-2" id="attributeOption">
                        {foreach from=$Attributes item=attribute}
                            <option value="{$attribute->Id()}">{$attribute->Label()}</option>
                        {/foreach}
                    </select>

                    <button type="button" class="btn btn-primary" id="addRuleButton">
                        <i class="bi bi-plus-lg"></i> {translate key='AddRule'}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            {assign var=tableId value=reservationColorTable}
            <table class="table table-striped table-hover border-top" id="{$tableId}">
                <thead>
                    <tr>
                        <th>{translate key=Attribute}</th>
                        <th>{translate key=RequiredValue}</th>
                        <th>{translate key=Color}</th>
                        <th class="action">{translate key='Delete'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$Rules item=rule}
                        <tr>
                            <td>{$rule->AttributeName}</td>
                            <td>{$rule->RequiredValue}</td>
                            <td style="background-color:{$rule->Color}">&nbsp;</td>
                            <td class="action">
                                <a href="#" class="update delete" ruleId="{$rule->Id}"><span
                                        class="bi bi-trash3-fill icon text-danger remove"></span></a>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addDialog" tabindex="-1" role="dialog" aria-labelledby="addDialogLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addForm" method="post" action="{$smarty.server.SCRIPT_NAME}?action=add" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDialogLabel">{translate key=AddReservationColorRule}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    {translate key=ReservationCustomRuleAdd}
                    <div class="d-flex align-items-end">
                        <div id="attributeFillIn"></div>
                        <div id="color">
                            <label for="reservationColor" class="visually-hidden">Reservation Color</label>
                            <input type="color" {formname key="RESERVATION_COLOR"}
                                class="form-control form-control-color required" id="reservationColor" maxlength="6" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {cancel_button}
                    {add_button}
                    {indicator}
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteDialog" tabindex="-1" role="dialog" aria-labelledby="deleteDialogLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteForm" action="{$smarty.server.SCRIPT_NAME}?action=delete" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDialogLabel">{translate key=Delete}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        {translate key=DeleteWarning}
                    </div>
                    <input type="hidden" id="deleteRuleId" {formname key=RESERVATION_COLOR_RULE_ID} />
                </div>
                <div class="modal-footer">
                    {cancel_button}
                    {delete_button}
                    {indicator}
                </div>
            </div>
        </form>
    </div>
</div>

{foreach from=$Attributes item=attribute}
    <div id="attribute{$attribute->Id()}" class="d-none">
        {control type="AttributeControl" attribute=$attribute searchmode=true}</div>
{/foreach}

{csrf_token}

</div>

{include file="javascript-includes.tpl" DataTable=true}
{datatable tableId=$tableId}
{jsfile src="ajax-helpers.js"}
{vendor_js src="jquery-form/3.09/jquery.form-3.09.min.js"}
{jsfile src="ajax-form-submit.js"}
{jsfile src="admin/reservation-colors.js"}

<script type="text/javascript">
    $('document').ready(function() {
        var mgmt = new ReservationColorManagement();
        mgmt.init();
    });
</script>

{include file='globalfooter.tpl'}