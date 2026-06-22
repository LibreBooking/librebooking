<div id="calendarSubscription" class="calendar-subscription text-end">
    {if $IsSubscriptionAllowed && $IsSubscriptionEnabled}
        <button id="subscribeToCalendar" type="button" class="btn btn-link link-primary p-0"
            title="{$SubscriptionUrl|escape:'html'}"
            onclick="copyUrlToClipboard('{$SubscriptionUrl|escape:'javascript'}')">
            <i class="bi bi-calendar-heart me-1"></i>{translate key=SubscribeToCalendar}
        </button>
    {/if}
</div>
