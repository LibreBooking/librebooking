{include file='globalheader.tpl'}
<div id="page-checkout">

	<div class="card shadow">
		<div class="card-body">
			<h1 class="border-bottom mb-3">
				{translate key=Checkout}
			</h1>
			<script
				src="https://www.paypal.com/sdk/js?client-id={$PayPalClientId}&intent=capture&currency={$Currency}&commit=true&disable-funding=credit">
			</script>
			<script src="https://checkout.stripe.com/checkout.js"></script>

			<div id="checkoutPage">

				{if !$IsCartEmpty}
					<div class="cart row gy-2" id="cart">
						<div class="col-12 col-sm-4">
							<h4>{translate key=PurchaseSummary}</h4>
							<div class="d-flex justify-content-between">
								<div class="ms-2">
									{translate key=CreditsEachCost1} {$CreditCount} {translate key=CreditsEachCost2}
								</div>
								<div class="text-end">{$CreditCost}</div>
							</div>

							<div class="d-flex justify-content-between">
								<div class="ms-2">{translate key=Quantity}</div>
								<div class="text-end">{$CreditQuantity}</div>
							</div>

							<div class="d-flex justify-content-between border-top fw-bold pt-2 mt-2">
								<div class="total ms-2">{translate key=Total}</div>
								<div class="text-end">{$Total}</div>
							</div>
						</div>

						<div class="col-12 col-sm-8">
							<div class="checkout-buttons row g-2">
								{if $PayPalEnabled}
									<div class="col-12 col-md-6">
										<div id="paypal-button"></div>
									</div>
								{/if}

								{if $StripeEnabled}
									<div class="col-12 col-md-6">
										<button id="stripe-button" class="btn btn-outline-secondary w-100" type="button">
											<i class="bi bi-credit-card me-1"></i> {translate key=PayWithCard}
										</button>
									</div>
								{/if}
							</div>
						</div>
					</div>

				{else}
					<div class="alert alert-danger">
						{translate key=EmptyCart} <a href="{$ScriptUrl}/{Pages::CREDITS}#purchase"
							class="alert-link">{translate key=BuyCredits}</a>
					</div>
				{/if}

				<div class="d-none mt-2" id="success">
					<div class="alert alert-success">
						<div>{translate key=Success}</div>
						<div><em>{$CreditQuantity * $CreditCount}</em> {translate key=CreditsPurchased}</div>
						<div><a href="{$ScriptUrl}/{Pages::CREDITS}"
								class="alert-link">{translate key=ViewYourCredits}</a>
						</div>
					</div>
				</div>

				<div class="d-none mt-2" id="error">
					<div class="alert alert-danger">
						<div>{translate key=PurchaseFailed}</div>
						<div><a href="{$ScriptUrl}/{Pages::CREDITS}#purchase"
								class="alert-link">{translate key=TryAgain}</a></div>
					</div>
				</div>

				{csrf_token}

				{include file="javascript-includes.tpl"}
				{jsfile src="ajax-helpers.js"}

				<script type="text/javascript">
					$(function() {
						{if $PayPalEnabled}

							var CREATE_PAYMENT_URL = '{$smarty.server.SCRIPT_NAME}?action=createPayPalPayment';
							var EXECUTE_PAYMENT_URL = '{$smarty.server.SCRIPT_NAME}?action=executePayPalPayment';

							paypal.Buttons({
								createOrder: function(data, actions) {

									const fd = new FormData();
									fd.append("CSRF_TOKEN", $('#csrf_token').val());
									// CSRF_TOKEN: $('#csrf_token').val()
									return fetch(CREATE_PAYMENT_URL, {
										method: 'POST',
										body: fd,
										credentials: "include",
									}).then(function(res) {
										return res.json();
									}).then(function(data) {
										return data.id;
									});
									// return paypal.request.post(CREATE_PAYMENT_URL, {
									//     CSRF_TOKEN: $('#csrf_token').val()
									// }).then(function (res) {
									//     return res.id;
									// });
								},

								onApprove: function(data, actions) {
									const fd = new FormData();
									fd.append("CSRF_TOKEN", $('#csrf_token').val());
									fd.append("paymentID", data.orderID);
									return fetch(EXECUTE_PAYMENT_URL, {
										method: 'POST',
										body: fd,
										credentials: "include"
									}).then(function(res) {
										return res.json();
									}).then(function(res) {
										$('#cart').addClass('d-none');
										if (res.status != "COMPLETED") {
											$('#error').removeClass('d-none');
										} else {
											$('#success').removeClass('d-none');
										}
									});
								}
								// return paypal.request.post(EXECUTE_PAYMENT_URL, {
								// 	paymentID: data.paymentID, payerID: data.payerID, CSRF_TOKEN: $('#csrf_token').val()
								// }).then(function (data) {
								// 	$('#cart').addClass('no-show');
								// 	if (data.state != "approved")
								// 	{
								// 		$('#error').removeClass('no-show');
								// 	}
								// 	else
								// 	{
								// 		$('#success').removeClass('no-show');
								// 	}
								// });

								// onError: function (err) {
								// 	$('#error').removeClass('no-show');
								// }

							}).render('#paypal-button');

						{/if}

						{if $StripeEnabled}

							var executeStripePaymentUrl = '{$smarty.server.SCRIPT_NAME}?action=executeStripePayment';
							var handler = StripeCheckout.configure({
								key: '{$StripePublishableKey}',
								image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
								zipCode: true,
								locale: 'auto',
								currency: '{$Currency}',
								email: '{$Email}',
								token: function(token) {
									var data = {
										CSRF_TOKEN: $('#csrf_token').val(),
										STRIPE_TOKEN: token.id
									};

									$.post(executeStripePaymentUrl, data, function(d) {
										$('#cart').addClass('d-none');

										if (d.result != true) {
											$('#error').removeClass('d-none');
										} else {
											$('#success').removeClass('d-none');
										}
									});
								}
							});

							document.getElementById('stripe-button').addEventListener('click', function(e) {
								handler.open({
									name: '{translate key=BuyMoreCredits}', description: '{$Total}', amount: {$TotalUnformatted * 100}
								});
								e.preventDefault();
							});

							window.addEventListener('popstate', function() {
								handler.close();
							});

						{/if}
					});
				</script>

			</div>
		</div>
	</div>
</div>

{include file='globalfooter.tpl'}