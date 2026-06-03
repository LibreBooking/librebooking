{if $Layout->HasPeakTimesDefined()}
	{assign var=p value=$Layout->GetPeakTimes()}

	<div class="peakTimes" data-all-day="{$p->IsAllDay()}" data-start-time="{$p->GetBeginTime()}"
		data-end-time="{$p->GetEndTime()}">
		{if $p->IsAllDay()}
			{translate key=AllDay}
		{else}
			{translate key=Between}
			{formatdate date=$p->GetBeginTime() key='period_time'} - {formatdate date=$p->GetEndTime() key='period_time'}
		{/if}
	</div>

	<div class="peakDays" data-everyday="{$p->IsEveryDay()}" data-weekdays="{$p->GetWeekdays()|join:','}">
		{if $p->IsEveryDay()}
			{translate key=Everyday}
		{else}
			{foreach from=$p->GetWeekdays() item=day}{$DayNames[$day]} {/foreach}
		{/if}
	</div>

	<div class="peakMonths" data-all-year="{$p->IsAllYear()}" data-begin-month="{$p->GetBeginMonth()}"
		data-begin-day="{$p->GetBeginDay()}" data-end-month="{$p->GetEndMonth()}" data-end-day="{$p->GetEndDay()}">
		{if $p->IsAllYear()}
			{translate key=AllYear}
		{else}
			{$Months[$p->GetBeginMonth()-1]} {$p->GetBeginDay()} - {$Months[$p->GetEndMonth()-1]} {$p->GetEndDay()}
		{/if}
	</div>

{else}
	<span class="propertyValue">{translate key=None}</span>
{/if}