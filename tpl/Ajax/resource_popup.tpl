<div id="resourceDetailsPopup">
    <div class="card shadow-sm">
        {assign var=headerStyle value=""}
        {if !empty($color)}
            {assign var=headerStyle value=" style=\"background-color:{$color};color:{$textColor};\""}
        {/if}
        <div class="resourceNameTitle card-header clearfix" {$headerStyle}>
            <h5 class="float-start">{$resourceName}</h5>
            <a href="#" class="btn btn-close d-sm-inline-block d-md-none hideResourceDetailsPopup float-end"
                aria-label="{translate key=Close}"></a>
        </div>
        <div class="card-body row">
            {assign var=class value='col-md-6'}
            {if $imageUrl neq ''}
                {assign var=class value='col-md-5'}

                <div class="resourceImage col-md-2">
                    <div id="resourceImageCarousel" class="carousel slide h-100" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{resource_image image=$imageUrl}" alt="{$resourceName|escape}"
                                    class="rounded d-block w-100" />
                            </div>
                            {foreach from=$images item=image}
                                <div class="carousel-item">
                                    <img src="{resource_image image=$image}" alt="{$resourceName|escape}"
                                        class="rounded d-block w-100" />
                                </div>
                            {/foreach}
                        </div>
                        <div class="carousel-indicators">
                            {if $images|count > 0}
                                {assign var=slide value=1}
                                <button type="button" data-bs-target="#resourceImageCarousel" data-bs-slide-to="0"
                                    class="active"></button>
                                {foreach from=$images item=image}
                                    <button type="button" data-bs-target="#resourceImageCarousel"
                                        data-bs-slide-to="{$slide}"></button>
                                    {assign var=slide value=$slide+1}
                                {/foreach}
                            {/if}
                        </div>
                    </div>
                </div>
            {/if}
            <div class="attributes {$class}">
                <span class="fw-bold">{translate key=Contact}</span>
                {if $contactInformation neq ''}
                    {$contactInformation}
                {else}
                    {translate key=NoContactLabel}
                {/if}
                <br />
                <span class="fw-bold">{translate key=Location}</span>
                {if $locationInformation neq ''}
                    {$locationInformation}
                {else}
                    {translate key=NoLocationLabel}
                {/if}
                <br />
                <span class="fw-bold">{translate key=ResourceType}</span>
                {if $resourceType neq ''}
                    {$resourceType}
                {else}
                    {translate key=NoResourceTypeLabel}
                {/if}
                {if $Attributes|default:array()|count > 0}
                    {foreach from=$Attributes item=attribute}
                        <div>
                            {control type="AttributeControl" attribute=$attribute readonly=true}
                        </div>
                    {/foreach}
                {/if}
                {if $ResourceTypeAttributes && $ResourceTypeAttributes|default:array()|count > 0}
                    {foreach from=$ResourceTypeAttributes item=attribute}
                        <div>
                            {control type="AttributeControl" attribute=$attribute readonly=true}
                        </div>
                    {/foreach}
                {/if}
            </div>
            <div class="settings {$class}">
                <div>
                    {if $minimumDuration neq ''}
                        {translate key='ResourceMinLength' args=$minimumDuration}
                    {else}
                        {translate key='ResourceMinLengthNone'}
                    {/if}
                </div>
                <div>
                    {if $maximumDuration neq ''}
                        {translate key='ResourceMaxLength' args=$maximumDuration}
                    {else}
                        {translate key='ResourceMaxLengthNone'}
                    {/if}
                </div>
                <div>
                    {if $requiresApproval}
                        {translate key='ResourceRequiresApproval'}
                    {else}
                        {translate key='ResourceRequiresApprovalNone'}
                    {/if}
                </div>
                <div>
                    {if $minimumNotice neq ''}
                        {translate key='ResourceMinNotice' args=$minimumNotice}
                    {else}
                        {translate key='ResourceMinNoticeNone'}
                    {/if}
                </div>
                <div>
                    {if $maximumNotice neq ''}
                        {translate key='ResourceMaxNotice' args=$maximumNotice}
                    {else}
                        {translate key='ResourceMaxNoticeNone'}
                    {/if}
                </div>
                <div>
                    {if $allowMultiday}
                        {translate key='ResourceAllowMultiDay'}
                    {else}
                        {translate key='ResourceNotAllowMultiDay'}
                    {/if}
                </div>
                <div>
                    {if $maxParticipants neq ''}
                        {translate key='ResourceCapacity' args=$maxParticipants}
                    {else}
                        {translate key='ResourceCapacityNone'}
                    {/if}
                </div>

                {if $autoReleaseMinutes neq ''}
                    <div>
                        {translate key='AutoReleaseNotification' args=$autoReleaseMinutes}
                    </div>
                {/if}
                {if $isCheckInEnabled neq ''}
                    <div>
                        {translate key='RequiresCheckInNotification'}
                    </div>
                {/if}

                {if $creditsEnabled}
                    <div>
                        {translate key=CreditUsagePerSlot args=$offPeakCredits}
                    </div>
                    <div>
                        {translate key=PeakCreditUsagePerSlot args=$peakCredits}
                    </div>
                {/if}
            </div>
            {assign var=class value='col-md-10'}
            <div class="description {$class}">
                <span class="fw-bold mt-2">{translate key=Description}</span>
                <div class="descriptionContent px-2">
                {if $description neq ''}
                    {$description|html_entity_decode|url2link|nl2br}
                {else}
                    {translate key=NoDescriptionLabel}
                {/if}
                </div>
                <span class="fw-bold mt-2">{translate key=Notes}</span>                
                <div class="noteContent px-2">
                {if $notes neq ''}
                    {$notes|html_entity_decode|url2link|nl2br}
                {else}
                    {translate key=NoNotesLabel}
                {/if}
                </div>
            </div>
        </div>
    </div>
</div>