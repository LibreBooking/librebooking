<div id="calendarSubscription" class="calendar-subscription text-end">
    {if $IsSubscriptionAllowed && $IsSubscriptionEnabled}
        <a href="#" id="turnOffSubscription" class="link-primary d-none"><i class="bi bi-toggle-off"></i>
            {translate key=TurnOffSubscription}
        </a>
        {if $IsSubscriptionEnabled}
            <a id="subscribeToCalendar" href="{$SubscriptionUrl|escape:'html'}" class="link-primary"
                title="{translate key=UrlCopiedToClipboard}"
                onclick="copyUrlToClipboard('{$SubscriptionUrl|escape:'javascript'|escape:'html'}'); return false;"><i class="bi bi-calendar-heart"></i>
                {translate key=SubscribeToCalendar}
            </a>
        {/if}
    {elseif $IsSubscriptionEnabled}
        <a href="#" id="turnOnSubscription" class="link-primary"><i class="bi bi-toggle-on"></i>
            {translate key=TurnOnSubscription}
        </a>
    {/if}
</div>
