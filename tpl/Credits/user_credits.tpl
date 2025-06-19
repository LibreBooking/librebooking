{include file='globalheader.tpl' DataTable=true}

<div id="page-user-credits">

    <div class="card shadow default-box">
        <div class="card-body">
            <div class="clearfix border-bottom mb-3">
                <h1 class="float-start">{translate key=YourCredits}</h1>
                <h1 class="float-end">{$CurrentCredits}</h1>
            </div>

            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item active" role="presentation">
                    <a class="nav-link active link-primary" data-bs-toggle="tab" href="#credit-log"
                        role="tab">{translate key=CreditHistory}</a>
                </li>

                {if $AllowPurchasingCredits && $IsCreditCostSet}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link link-primary" data-bs-toggle="tab" href="#purchase"
                            role="tab">{translate key=BuyMoreCredits}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link link-primary" data-bs-toggle="tab" href="#transaction-log"
                            role="tab">{translate key=TransactionHistory}</a>
                    </li>
                {/if}
            </ul>

            <div class="tab-content margin-top-25">
                <div class="tab-pane active" id="credit-log" role="tabpanel">
                    {indicator id=creditLogIndicator}
                    <div id="credit-log-content">

                    </div>
                </div>

                {if $AllowPurchasingCredits && $IsCreditCostSet}
                    <div class="tab-pane" id="purchase" role="tabpanel">

                        <div class="col-12 col-md-4">
                            <form role="form" name="purchaseCreditsForm" id="purchaseCreditsForm" method="post"
                                action="checkout.php">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <label for="count" class="form-label mb-0">{translate key=CreditsEachCost1}</label>
                                    <select id="count" {formname key=CREDIT_COUNT}
                                        class="form-select form-select-sm w-auto">
                                        {foreach from=$CreditCosts item=credit}
                                            <option value="{$credit->Count()}">{$credit->Count()}</option>
                                        {/foreach}
                                    </select>
                                    <span class="ms-1">{translate key=CreditsEachCost2}</span>
                                    <span id="cost" class="cost fw-bold ms-2">{$CreditCost}</span>
                                </div>

                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <label for="quantity" class="form-label mb-0">{translate key=Quantity}</label>
                                    <input id="quantity" {formname key=CREDIT_QUANTITY} type="number"
                                        class="form-control form-control-sm w-auto" min="1" style="width:100px" value="1">
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-label mb-0">{translate key=Total}</label>
                                    <span id="totalCost" class="cost fw-bold">{$CreditCost}</span>
                                </div>
                                <button type="submit"
                                    class="btn btn-outline-secondary col-12">{translate key=Checkout}</button>
                                {csrf_token}
                            </form>
                        </div>

                        <div class="col-8">&nbsp;</div>
                        <div class="clearfix"></div>

                    </div>

                    <div class="tab-pane" id="transaction-log" role="tabpanel">
                        {indicator id=transactionLogIndicator}
                        <div id="transaction-log-content">

                        </div>
                    </div>
                {/if}
            </div>
        </div>
    </div>
</div>

{include file="javascript-includes.tpl" DataTable=true}
{jsfile src="user-credits.js"}
{jsfile src="ajax-helpers.js"}

<script type="text/javascript">
    $(function() {

        var opts = {
            calcQuantityUrl: '{$smarty.server.SCRIPT_NAME}?dr=calcQuantity&quantity=',
            creditLogUrl: '{$smarty.server.SCRIPT_NAME}?dr=creditLog&page=[page]&pageSize=[pageSize]',
            transactionLogUrl: '{$smarty.server.SCRIPT_NAME}?dr=transactionLog&page=[page]&pageSize=[pageSize]'
        };

        var userCredits = new UserCredits(opts);
        userCredits.init();

        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
        }

        $('.nav-pills a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        });
    });
</script>
{include file='globalfooter.tpl'}