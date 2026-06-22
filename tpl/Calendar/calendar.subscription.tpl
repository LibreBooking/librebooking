<div id="calendarSubscription" class="calendar-subscription text-end">
    {if $IsSubscriptionAllowed && $IsSubscriptionEnabled}
        <a id="subscribeToCalendar" class="link-primary" href="{$SubscriptionUrl|escape:'html'}"
            title="{translate key=UrlCopiedToClipboard}"
            onclick="copyUrlToClipboard('{$SubscriptionUrl|escape:'javascript'|escape:'html'}'); return false;">
            <i class="bi bi-calendar-heart me-1"></i>{translate key=SubscribeToCalendar}</a>
    {/if}
</div>
