<div class="row form-inline">
    <div id="filter" class="text-center">

        {if isset($GroupName) && $GroupName}
            <span class="groupName fw-bold fs-5">{$GroupName}</span>
        {else}
            <div class="d-flex justify-content-center align-items-center">
                <div>{indicator id=loadingIndicator}</div>
                <label class="fw-bold me-2" for="calendarFilter">{translate key="ChangeCalendar"}</label>
                <select id="calendarFilter" class="form-select w-auto">
                    {foreach from=$filters->GetFilters() item=filter}
                        <option value="s{$filter->Id()}" class="schedule" {if $filter->Selected()}selected="selected" {/if}>
                            {$filter->Name()}</option>
                        {foreach from=$filter->GetFilters() item=subfilter}
                            <option value="r{$subfilter->Id()}" class="resource" {if $subfilter->Selected()}selected="selected"
                                {/if}>{$subfilter->Name()}</option>
                        {/foreach}
                    {/foreach}
                {/if}
            </select>
            <a href="#" id="showResourceGroups" class="link-primary ms-1">{translate key=ResourceGroups}</a>
        </div>
    </div>

    <div id="resourceGroupsContainer" class="bg-white border rounded pt-2">
        <div id="resourceGroups"></div>
    </div>


    {if false}
        <div class="d-flex justify-content-center align-items-center my-3">
            <div class="input-group me-3">
                <label class="input-group-text fw-bold" for="ownerFilter">{translate key=Owner}</label>
                <input {formname key=USER_ID} id="ownerId" type="hidden" value="{$OwnerId}" />
                <input type='search' id='ownerFilter' class="form-control input-sm search" {formname key=OWNER_TEXT}
                    value="{$OwnerText|escape:'html':'UTF-8':false}" />
            </div>
            {if $AllowParticipation}
                <div class="input-group me-3">
                    <label class="input-group-text fw-bold" for="participantFilter">{translate key=Participant}</label>
                    <input {formname key=PARTICIPANT_ID} id="participantId" type="hidden" value="{$ParticipantId}" /> <input
                        type='search' id='participantFilter' class="form-control input-sm search"
                        {formname key=PARTICIPANT_TEXT} value="{$ParticipantText|escape:'html':'UTF-8':false}" />
                </div>
            {/if}
            <div class="">
                <button id="clearUserFilter" class="btn btn-outline-secondary">{translate key=Reset}</button>
            </div>
        </div>
    {/if}

</div>

<script type="text/javascript">
    $(function() {
        $('#calendarFilter').select2();
    });
</script>