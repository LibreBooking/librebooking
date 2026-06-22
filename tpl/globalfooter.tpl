	</div><!-- close main-->
	<div id="button-up" class="bg-primary rounded-circle text-white">
		<i class="bi bi-chevron-double-up" aria-hidden="true"></i>
	</div>
	<footer class="bg-light border-top text-center py-1" role="contentinfo">
		{if $CompanyName neq ''}
			<div class="mb-2"><a class="link-primary" href="{$CompanyUrl}">{$CompanyName}</a></div>
		{/if}
		<div><a class="link-primary" href="https://github.com/LibreBooking/librebooking">LibreBooking - GPLv3 -
				{$DisplayVersion}</a></div>
	</footer>

	<div class="toast-container position-fixed bottom-0 end-0 p-3">
		<div id="clipboardCopyToast" class="toast align-items-center bg-primary text-white border-0 d-none" role="alert"
			aria-live="assertive" aria-atomic="true">
			<div class="d-flex">
				<div class="toast-body">
					<i class="bi bi-check-circle-fill me-2"></i>{translate key=UrlCopiedToClipboard}
				</div>
				<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
					aria-label="{translate key=Close}"></button>
			</div>
		</div>
		<div id="defaultSetToast" class="toast align-items-center bg-primary text-white border-0 d-none" role="alert"
			aria-live="assertive" aria-atomic="true">
			<div class="d-flex">
				<div class="toast-body">
					<i class="bi bi-check-circle-fill me-2"></i>{translate key=DefaultScheduleSet}
				</div>
				<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
					aria-label="{translate key=Close}"></button>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		init();

		{if isset($LoggedIn) && $LoggedIn && count($AvailableLanguages) > 1}
			$('#languageDropdownMenu').on('click', 'a[data-lang-code]', function(e) {
				e.preventDefault();
				var langCode = $(this).data('lang-code');
				var csrfToken = '{$CSRFToken|escape:'javascript'}';
				$.post('{$Path|escape:'javascript'}ajax/change_language.php', {
					'{FormKeys::LANGUAGE}': langCode,
					'{FormKeys::CSRF_TOKEN}': csrfToken
				}, 'json').done(function(data) {
					if (data && data.success) {
						window.location.reload();
					} else {
						console.error('Language change failed', data);
					}
				}).fail(function(jqXHR, textStatus, errorThrown) {
					console.error('Language change request failed', textStatus, errorThrown);
				});
			});
		{/if}
	</script>

	{if !empty($GoogleAnalyticsTrackingId)}
		<!-- Google tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id={$GoogleAnalyticsTrackingId}"></script>
		{literal}
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
			{/literal}
			gtag('config', '{$GoogleAnalyticsTrackingId}');
		</script>
	{/if}

	</body>

</html>
