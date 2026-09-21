<div id="calendarSubscription" class="calendar-subscription text-end">
    {if $IsSubscriptionAllowed && $IsSubscriptionEnabled}
        <a href="#" id="turnOffSubscription" class="link-primary d-none"><i class="bi bi-toggle-off"></i>
            {translate key=TurnOffSubscription}
        </a>
        {if $IsSubscriptionEnabled}
            <button id="subscribeToCalendar" type="button" class="btn btn-sm btn-link link-primary"
                title="{translate key=SubscribeToCalendar}"
                onclick="copyUrlToClipboard('{$SubscriptionUrl|escape:'javascript'|escape:'html'}');"><i class="bi bi-calendar-heart"></i>
                {translate key=SubscribeToCalendar}
            </button>
        {/if}
    {elseif $IsSubscriptionEnabled}
        <a href="#" id="turnOnSubscription" class="link-primary"><i class="bi bi-toggle-on"></i>
            {translate key=TurnOnSubscription}
        </a>
    {/if}
</div>
