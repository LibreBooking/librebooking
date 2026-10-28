{strip}
{* {strip} joins these lines; {linebreak} emits the row separators. *}
"{translate key='User'}",
"{translate key='Resource'}",
"{translate key='Title'}",
"{translate key='Description'}",
"{translate key='BeginDate'}",
"{translate key='EndDate'}",
"{translate key='Duration'}",
"{translate key='Created'}",
"{translate key='LastModified'}",
"{translate key='ReferenceNumber'}",
"{translate key='CheckInTime'}",
"{translate key='CheckOutTime'}",
"{translate key='OriginalEndDate'}"
{foreach from=$ReservationAttributes item=attr}
    ,"{$attr->Label()|escape_csv}"
{/foreach}
{linebreak}
{foreach from=$reservations item=reservation}
    "{fullname first=$reservation->FirstName last=$reservation->LastName csv=true}",
    "{$reservation->ResourceName|escape_csv}",
    "{$reservation->Title|escape_csv}",
    "{$reservation->Description|escape_csv}",
    "{formatdate date=$reservation->StartDate timezone=$Timezone key=res_popup}",
    "{formatdate date=$reservation->EndDate timezone=$Timezone key=res_popup}",
    "{$reservation->GetDuration()->__toString()}",
    "{formatdate date=$reservation->CreatedDate timezone=$Timezone key=general_datetime}",
    "{formatdate date=$reservation->ModifiedDate timezone=$Timezone key=general_datetime}",
    "{$reservation->ReferenceNumber}",
    {formatdate date=$reservation->CheckinDate timezone=$Timezone key=general_datetime},
    {formatdate date=$reservation->CheckoutDate timezone=$Timezone key=general_datetime},
    {formatdate date=$reservation->OriginalEndDate timezone=$Timezone key=general_datetime}
    {foreach from=$ReservationAttributes item=attribute}
        ,"{$reservation->Attributes->Get($attribute->Id())|escape_csv}"
    {/foreach}
    {linebreak}
{/foreach}
{/strip}
