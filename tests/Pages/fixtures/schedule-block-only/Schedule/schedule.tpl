{* Stand-in for tpl/Schedule/schedule.tpl so a child template's reservations block can be rendered alone *}
{function name=displaySlotStub}<td class="slot" data-min="{$Slot->BeginDate()->Timestamp()}" data-max="{$Slot->EndDate()->Timestamp()}"></td>{/function}
{block name="reservations"}{/block}
