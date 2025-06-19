{include file='globalheader.tpl' DataTable=true}

<div id="page-manage-payments" class="admin-page">

    <h1 class="border-bottom mb-3">{translate key=ManagePayments}</h1>

    <div id="updatedCreditsMessage" class="alert alert-success" style="display:none;">
        {translate key=CreditsUpdated}
    </div>
    <div id="updatedGatewayMessage" class="alert alert-success" style="display:none;">
        {translate key=GatewaysUpdated}
    </div>
    <div id="refundIssuedMessage" class="alert alert-success" style="display:none;">
        {translate key=RefundIssued}
    </div>

    {if !$PaymentsEnabled}
        <div class="error alert alert-danger">
            {translate key=CreditPurchaseNotEnabled}<br />
            <a href="{$Path}/admin/manage_configuration.php" class="alert-link">{translate key=ManageConfiguration}</a>
        </div>
    {else}
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item active">
                <a class="nav-link active link-primary" data-bs-toggle="tab" href="#transactions"
                    role="tab">{translate key=Transactions}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link link-primary" data-bs-toggle="tab" href="#cost" role="tab">{translate key=Cost}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link link-primary" data-bs-toggle="tab" href="#gateways"
                    role="tab">{translate key=PaymentGateways}</a>
            </li>
        </ul>
        <div class="tab-content mt-3">
            <div class="tab-pane active" id="transactions" role="tabpanel">
                {indicator id=transactionLogIndicator}
                <div id="transaction-log-content">

                </div>
            </div>

            <div class="tab-pane" id="cost" role="tabpanel">
                <div>
                    {* Form to add or update credit payment configurations *}
                    <form role="form" name="updateCreditsForm" id="updateCreditsForm" method="post"
                        ajaxAction="updateCreditCost" action="{$smarty.server.SCRIPT_NAME}" class="mb-4">
                        <div class="form-group d-flex align-items-center flex-wrap gap-1">
                            <label for="creditCount" class="fw-bold">{translate key=CreditsEachCost1}</label>
                            <input type="number" min="0" max="1000000000" id="creditCount" step="1"
                                class="form-control form-control-sm w-auto" {formname key=CREDIT_COUNT} value="1" />
                            <label for="creditCost" class="fw-bold">{translate key=CreditsEachCost2}</label>
                            <input type="number" min="0" max="1000000000" id="creditCost" step="any"
                                class="form-control form-control-sm w-auto" {formname key=CREDIT_COST} value="30.00" />
                            <label for="creditCurrency" class="visually-hidden">{translate key=Currency}</label>
                            <select id="creditCurrency" {formname key=CREDIT_CURRENCY}
                                class="form-select form-select-sm w-auto" style="width:auto;">
                                {foreach from=$Currencies item=c}
                                    <option value="{$c->IsoCode()}">{$c->IsoCode()}</option>
                                {/foreach}
                            </select>
                            {update_button submit=true class='btn-sm'}
                            {indicator id="updateCreditsIndicator"}
                        </div>
                    </form>
                    {* Table to show and delete credit offers *}
                    {assign var=tableId value='creditsTable'}
                    <table class="table table-striped table-hover border-top w-100" id="{$tableId}">
                        <thead>
                            <tr>
                                <th>{translate key=CreditsCount}</th>
                                <th>{translate key=CreditsCost}</th>
                                <th>{translate key=Currency}</th>
                                <th class="action">{translate key=Actions}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$CreditCosts item=credit}
                                <tr data-credit-id="{$credit->Count()}">
                                    <td>{$credit->Count()}</td>
                                    <td>{$credit->Cost()}</td>
                                    <td>{$credit->Currency()}</td>
                                    <td class="action">
                                        {if $credit->Count() != 1}
                                            <form role="form" name="{($credit->Count())}" id="{$credit->Count()}"
                                                class="deleteCreditsForm" method="post" ajaxAction="deleteCreditCost"
                                                action="{$smarty.server.SCRIPT_NAME}">
                                                <input type="hidden" {formname key=CREDIT_COUNT} value="{$credit->Count()}" />
                                                <button type="submit" class="btn btn-link m-0 p-0">
                                                    <span class="visually-hidden">{translate key=Delete}</span>
                                                    <span class="bi bi-trash3-fill text-danger icon remove"></span>
                                                </button>
                                                {indicator id="deleteCreditsIndicator"}
                                            </form>
                                        {/if}
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane" id="gateways" role="tabpanel">
                <form role="form" name="updateGatewayForm" id="updateGatewayForm" method="post"
                    ajaxAction="updatePaymentGateways" action="{$smarty.server.SCRIPT_NAME}" class="form-vertical row gy-2">
                    <div class="col-12 col-sm-6">
                        <div class="payment-gateway-title">PayPal</div>
                        <div class="form-check form-switch form-switch-lg">
                            <input id="paypalEnabled" type="checkbox" value="1" {formname key=PAYPAL_ENABLED}
                                class="toggleDisabled form-check-input" role="switch" data-target="paypal-toggle">
                            <label class="switch visually-hidden">PayPal</label>
                        </div>
                        <div class="form-group">
                            <label for="paypalClientId">{translate key=PayPalClientId} </label>
                            <input type="text" id="paypalClientId" class="form-control paypal-toggle required" required
                                disabled="disabled" {formname key=PAYPAL_CLIENT_ID} value="{$PayPalClientId}" />

                        </div>
                        <div class="form-group">
                            <label for="paypalSecret">{translate key=PayPalSecret}</label>
                            <input type="text" id="paypalSecret" class="form-control paypal-toggle required" required
                                disabled="disabled" {formname key=PAYPAL_SECRET} value="{$PayPalSecret}" />

                        </div>
                        <div class="form-group">
                            <label for="paypalEnvironment">{translate key=PayPalEnvironment} </label>
                            <select id="paypalEnvironment" class="form-select paypal-toggle" disabled="disabled"
                                {formname key=PAYPAL_ENVIRONMENT}>
                                <option value="live" {if $PayPalEnvironment =='live'}selected="selected" {/if}>
                                    {translate key=Live}</option>
                                <option value="sandbox" {if $PayPalEnvironment =='sandbox'}selected="selected" {/if}>
                                    {translate key=Sandbox}</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group col-12 col-sm-6">
                        <div class="payment-gateway-title">Stripe</div>
                        <div class="form-group">
                            <div class="form-check form-switch form-switch-lg">
                                <input id="stripeEnabled" type="checkbox" value="1" {formname key=STRIPE_ENABLED}
                                    class="toggleDisabled form-check-input" role="switch" data-target="stripe-toggle">
                                <label class="switch visually-hidden">Stripe</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="stripePublishKey">{translate key=StripePublishableKey}</label>
                            <input type="text" id="stripePublishKey" class="form-control stripe-toggle required" required
                                disabled="disabled" {formname key=STRIPE_PUBLISHABLE_KEY} value="{$StripePublishableKey}" />

                        </div>
                        <div class="form-group">
                            <label for="stripeSecretKey">{translate key=StripeSecretKey} </label>
                            <input type="text" id="stripeSecretKey" class="form-control stripe-toggle required" required
                                disabled="disabled" {formname key=STRIPE_SECRET_KEY} value="{$StripeSecretKey}" />

                        </div>
                    </div>
                    <div class="d-grid">
                        {update_button submit=true}
                        {indicator}
                    </div>
                </form>
            </div>
        </div>
    {/if}

    <div class="modal fade" id="refundDialog" tabindex="-1" role="dialog" aria-labelledby="refundDialogLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form role="form" name="issueRefundForm" id="issueRefundForm" method="post" ajaxAction="issueRefund"
                action="{$smarty.server.SCRIPT_NAME}" class="form-vertical">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="refundDialogLabel">{translate key=IssueRefund}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="refundAmount">{translate key='RefundAmount'}</label>
                            <input type="number" id="refundAmount" min=".01" step="any" class="form-control"
                                {formname key=REFUND_AMOUNT} />
                            <input type="hidden" id="refundId" {formname key=REFUND_TRANSACTION_ID} />
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {update_button submit=true key=IssueRefund}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>

    {csrf_token}
    {indicator id="indicator"}

    {include file="javascript-includes.tpl" DataTable=true}
    {datatable tableId={$tableId}}
    {jsfile src="ajax-helpers.js"}
    {jsfile src="admin/payments.js"}

    <script type="text/javascript">
        $(function() {
            var opts = {
                transactionLogUrl: '{$smarty.server.SCRIPT_NAME}?dr=transactionLog&page=[page]&pageSize=[pageSize]',
                transactionDetailsUrl: '{$smarty.server.SCRIPT_NAME}?dr=transactionDetails&id=[id]',
            };

            var payments = new Payments(opts);
            payments.init();
            payments.initGateways({$PayPalEnabled}, {$StripeEnabled});

            var url = document.location.toString();
            if (url.match('#')) {
                $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            }

            $('.nav-tabs a').on('shown.bs.tab', function(e) {
                window.location.hash = e.target.hash;
            })
        });
    </script>

</div>

{include file='globalfooter.tpl'}