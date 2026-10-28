{strip}
{* {strip} joins these lines; {linebreak} emits the row separators. *}
"{translate key='Name'}",
"{translate key='Status'}",
"{translate key='Schedule'}",
"{translate key='ResourceType'}",
"{translate key='SortOrder'}",
"{translate key='Location'}",
"{translate key='Contact'}",
"{translate key='Description'}",
"{translate key='Notes'}",
"{translate key='ResourceAdministrator'}",
"{translate key='ResourceColor'}",
"{translate key='ResourceMinLengthCsv'}",
"{translate key='ResourceMaxLengthCsv'}",
"{translate key='ResourceBufferTimeCsv'}",
"{translate key='ResourceAllowMultiDay'}",
"{translate key='Capacity'}",
"{translate key='ResourceGroups'}",
"{translate key='ResourceMinNoticeAddCsv'}",
"{translate key='ResourceMinNoticeUpdateCsv'}",
"{translate key='ResourceMinNoticeDeleteCsv'}",
"{translate key='ResourceMaxNotice'}",
"{translate key='ResourceRequiresApproval'}",
"{translate key='ResourcePermissionAutoGranted'}",
"{translate key='RequiresCheckInNotification'}",
"{translate key='AutoReleaseMinutes'}",
"{translate key='CreditsOffPeak'}",
"{translate key='CreditsPeak'}",
"{translate key='MaximumConcurrentReservations'}"
{foreach from=$AttributeList item=attr name=attributeLabels}
    ,"{$attr->Label()|escape_csv}"
{/foreach}
{linebreak}
{foreach from=$Resources item=resource}
    "{$resource->GetName()|escape_csv}",
    "
    {if $resource->IsAvailable()}
        {translate key='Available'}
    {elseif $resource->IsUnavailable()}
        {translate key='Unavailable'}
    {else}
        {translate key='Hidden'}
    {/if}
    ",
    "{$Schedules[$resource->GetScheduleId()]|escape_csv}",
    "
    {if $resource->HasResourceType()}
        {($ResourceTypes[$resource->GetResourceTypeId()]->Name())|escape_csv}
    {/if}
    ",
    {$resource->GetSortOrder()|default:"0"},
    "{$resource->GetLocation()|escape_csv}",
    "{$resource->GetContact()|escape_csv}",
    "{$resource->GetDescription()|escape_csv}",
    "{$resource->GetNotes()|escape_csv}",
    "
    {if $resource->GetAdminGroupId()}
        {($GroupLookup[$resource->GetAdminGroupId()]->Name)|escape_csv}
    {/if}
    ",
    "{$resource->GetColor()}",
    "{$resource->GetMinLength()}",
    "{$resource->GetMaxLength()}",
    "{$resource->GetBufferTime()}",
    "{$resource->GetAllowMultiday()|default:0}",
    "{$resource->GetMaxParticipants()}",
    "
    {foreach from=$resource->GetResourceGroupIds() item=resourceGroupId name=eachGroup}
        {($ResourceGroupList[$resourceGroupId]->name)|escape_csv}
        {if !$smarty.foreach.eachGroup.last},{/if}
    {/foreach}
    ",
    "{$resource->GetMinNoticeAdd()}",
    "{$resource->GetMinNoticeUpdate()}",
    "{$resource->GetMinNoticeDelete()}",
    "{$resource->GetMaxNotice()}",
    "{$resource->GetRequiresApproval()|default:0}",
    "{$resource->GetAutoAssign()|default:0}",
    "{$resource->IsCheckInEnabled()|default:0}",
    "{$resource->GetAutoReleaseMinutes()}",
    "{$resource->GetCreditsPerSlot()}",
    "{$resource->GetPeakCreditsPerSlot()}",
    "{$resource->GetMaxConcurrentReservations()}"
    {foreach from=$AttributeList item=attribute name=attributeLoop}
        ,"{$resource->GetAttributeValue($attribute->Id())|escape_csv}"
    {/foreach}
    {linebreak}
{/foreach}
{/strip}
