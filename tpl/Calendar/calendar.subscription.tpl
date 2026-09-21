<div id="calendarSubscription" class="calendar-subscription text-end">
    {if $IsSubscriptionAllowed && $IsSubscriptionEnabled}
        <button id="subscribeToCalendar" type="button" class="btn btn-sm btn-link link-primary"
            title="{translate key=SubscribeToCalendar}"
            onclick="copyUrlToClipboard('{$SubscriptionUrl|escape:'javascript'|escape:'html'}');">
            <i class="bi bi-calendar-heart me-1"></i>{translate key=SubscribeToCalendar}</button>
    {/if}
</div>
