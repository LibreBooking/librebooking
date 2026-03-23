	</div><!-- close main-->
	<div id="button-up" class="bg-primary rounded-circle text-white">
		<i class="bi bi-chevron-double-up" aria-hidden="true"></i>
	</div>
	<footer class="bg-light border-top text-center pt-2">
		<div><a class="link-primary" href="{$CompanyUrl}">{$CompanyName}</a></div>
		<div><a class="link-primary" href="https://github.com/LibreBooking/librebooking">LibreBooking - GPLv3 -
				{$DisplayVersion}</a></div>
	</footer>

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
