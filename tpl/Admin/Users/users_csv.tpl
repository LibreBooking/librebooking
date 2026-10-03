{strip}
{* {strip} joins these lines; {linebreak} emits the row separators. *}
"{translate key='FirstName'}",
"{translate key='LastName'}",
"{translate key='Username'}",
"{translate key='Email'}",
"{translate key='Phone'}",
"{translate key='Organization'}",
"{translate key='Position'}",
"{translate key='Created'}",
"{translate key='LastLogin'}",
"{translate key='Status'}",
"{translate key='Credits'}",
"{translate key='Color'}",
"{translate key='Timezone'}",
"{translate key='Language'}",
"{translate key='Groups'}"
{foreach from=$AttributeList item=attr name=attributeLabels}
    ",{$attr->Label()|escape_csv}"
{/foreach}
{linebreak}
{foreach from=$users item=user}
    "{$user->First|escape_csv}",
    "{$user->Last|escape_csv}",
    "{$user->Username|escape_csv}",
    "{$user->Email|escape_csv}",
    "{$user->Phone|escape_csv}",
    "{$user->Organization|escape_csv}",
    "{$user->Position|escape_csv}",
    "{format_date date=$user->DateCreated key=short_datetime}",
    "{format_date date=$user->LastLogin key=short_datetime}",
    "{$statusDescriptions[$user->StatusId]|escape_csv}",
    "{$user->CurrentCreditCount}",
    "{$user->ReservationColor}",
    "{$user->Timezone}",
    "{$user->Language}",
    "
    {foreach from=$user->GroupIds item=groupId name=groupLoop}
        {$Groups[$groupId]->Name()|escape_csv}
        {if !$smarty.foreach.groupLoop.last},{/if}
    {/foreach}
    "
    {foreach from=$AttributeList item=attribute name=attributeLoop}
        ,"{$user->GetAttributeValue($attribute->Id())|escape_csv}"
    {/foreach}
    {linebreak}
{/foreach}
{/strip}
