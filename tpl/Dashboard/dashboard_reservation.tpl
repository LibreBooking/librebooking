{assign var=checkin value=$reservation->IsCheckinEnabled() && $reservation->RequiresCheckin()}
{assign var=checkout value=$reservation->IsCheckinEnabled() && $reservation->RequiresCheckout()}
{assign var=class value=""}
{if $reservation->RequiresApproval}{assign var=class value="pending"}{/if}
<div class="reservation row gx-0 {$class} border-bottom p-2 border-bottom align-items-center {if isset($orangePending)}bg-white{/if}"
    id="{$reservation->ReferenceNumber}" data-bs-custom-class="respopup-tooltip" data-bs-html="true">
    {*doesn't show pending approval reservations as orange in the Pending Approval Reservations displayer in the dashboard*}
    <div class="col-sm-3 col-12">{$reservation->Title|escape:'html'|default:$DefaultTitle}</div>
    <div class="col-sm-3 col-12">
        {fullname first=$reservation->FirstName|unescape:'html' last=$reservation->LastName|unescape:'html' ignorePrivacy=$reservation->IsUserOwner($UserId)}
        {if !$reservation->IsUserOwner($UserId)}<i class="bi bi-people-fill"></i> {/if}</div>
    <div class="col-sm-3 col-6">{formatdate date=$reservation->StartDate->ToTimezone($Timezone) key=dashboard} -
        {formatdate date=$reservation->EndDate->ToTimezone($Timezone) key=dashboard}</div>
    {*<div class="col-sm-2 col-6">{formatdate date=$reservation->EndDate->ToTimezone($Timezone) key=dashboard}</div>*}
    <div class="col-sm-{if $checkin || $checkout}2{else}3{/if} col-12">{$reservation->ResourceNames|join:', '}</div>
    {if $allowCheckin}
        {if $checkin}
            <div class="col-sm-1 col-12">
                <button title="{translate key=CheckIn}" type="button"
                    class="btn btn-sm col-12 btn-warning text-white btnCheckin"
                    data-referencenumber="{$reservation->ReferenceNumber}"
                    data-url="ajax/reservation_checkin.php?action={ReservationAction::Checkin}"><i
                        class="bi bi-box-arrow-in-right"></i>
                    {translate key=CheckIn}
                </button>
            </div>
        {/if}
    {/if}
    {if $allowCheckout}
        {if $checkout}
            <div class="col-sm-1 col-12">
                <button title="{translate key=CheckOut}" type="button"
                    class="btn btn-sm col-12 btn-warning text-white btnCheckin"
                    data-referencenumber="{$reservation->ReferenceNumber}"
                    data-url="ajax/reservation_checkin.php?action={ReservationAction::Checkout}"><i
                        class="bi bi-box-arrow-in-left"></i>
                    {translate key=CheckOut}
                </button>
            </div>
        {/if}
    {/if}
</div>
