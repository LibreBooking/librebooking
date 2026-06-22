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
            <i class="bi bi-calendar link-primary"></i>
            <button type="button" id="copy-ical-{$resource->GetId()}" class="btn btn-link link-primary p-0"
                title="{$resource->GetSubscriptionUrl()->GetWebcalUrl()|escape:'html'}"
                onclick="copyUrlToClipboard('{$resource->GetSubscriptionUrl()->GetWebcalUrl()|escape:'javascript'}')">{translate key=SubscribeToCalendar}</button>
            <div class="vr mx-1"></div>
            <i class="bi bi-rss-fill link-primary"></i>
            <button type="button" id="copy-atom-{$resource->GetId()}" class="btn btn-link link-primary p-0"
                title="{$resource->GetSubscriptionUrl()->GetAtomUrl()|escape:'html'}"
                onclick="copyUrlToClipboard('{$resource->GetSubscriptionUrl()->GetAtomUrl()|escape:'javascript'}')">Atom</button>
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
