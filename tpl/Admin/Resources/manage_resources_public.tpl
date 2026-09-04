{if $resource->GetIsCalendarSubscriptionAllowed() && $modeEdit}
    <span>Resource Display</span>
    <a href="{$ScriptUrl}/{Pages::DISPLAY_RESOURCE}?{QueryStringKeys::RESOURCE_ID}={$resource->GetPublicId()}"
        class="link-primary">{$ScriptUrl}/{Pages::DISPLAY_RESOURCE}?{QueryStringKeys::RESOURCE_ID}={$resource->GetPublicId()}</a>
{elseif $resource->GetIsCalendarSubscriptionAllowed() && !$modeEdit}
    {if $IcsEnabled}
        <div><a class="update disableSubscription subscriptionButton link-primary"
                href="#">{translate key=TurnOffSubscription}</a>
        </div>
    {/if}
    <div>
        {if $IcsEnabled}
            {assign var="icalUrl" value=$resource->GetSubscriptionUrl()->GetWebcalUrl()}
            {assign var="atomUrl" value=$resource->GetSubscriptionUrl()->GetAtomUrl()}
            <i class="bi bi-calendar link-primary"></i>
            <a target="_blank" href="{$icalUrl|escape:'html'}" class="link-primary"
                title="{translate key=SubscribeToCalendar}"
                onclick="copyUrlToClipboard('{$icalUrl|escape:'javascript'|escape:'html'}'); return false;">{translate key=SubscribeToCalendar}</a>
            <div class="vr mx-1"></div>
            <i class="bi bi-rss-fill link-primary"></i>
            <a target="_blank" href="{$atomUrl|escape:'html'}" class="link-primary"
                title="{translate key=SubscribeToCalendar}"
                onclick="copyUrlToClipboard('{$atomUrl|escape:'javascript'|escape:'html'}'); return false;">Atom</a>
            <div class="vr mx-1"></div>
        {/if}
        <i class="bi bi-display link-primary"></i>
        <a href="{$ScriptUrl}/{Pages::DISPLAY_RESOURCE}?{QueryStringKeys::RESOURCE_ID}={$resource->GetPublicId()}"
            class="link-primary">Display</a>
    </div>
    <div>
        <span>{translate key=PublicId}</span>
        <span class="propertyValue fw-bold">{$resource->GetPublicId()}</span>
    </div>
{elseif !$resource->GetIsCalendarSubscriptionAllowed() && !$modeEdit && $IcsEnabled}
    <div>
        <a class="update enableSubscription subscriptionButton link-primary" href="#">{translate key=TurnOnSubscription}</a>
    </div>
{else}
    {translate key='None'}
{/if}
