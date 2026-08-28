{if $PeakTimesArr|count>0}
{foreach from=$PeakTimesArr item=p}
	<div class="peakPlaceHolder" data-peak-times-id="{$p->GetPeakTimesId()}">
		<div class="peakTimes"  data-all-day="{$p->IsAllDay()}"
			data-start-time="{$p->GetBeginTime()}" data-end-time="{$p->GetEndTime()}"
			data-everyday="{$p->IsEveryDay()}" data-weekdays="{$p->GetWeekdays()|join:','}"
			data-all-year="{$p->IsAllYear()}" data-begin-month="{$p->GetBeginMonth()}"
			data-begin-day="{$p->GetBeginDay()}" data-end-month="{$p->GetEndMonth()}" data-end-day="{$p->GetEndDay()}">
			<a href="#" class="editPeakTimes updateActivePeakTimesId update link-primary" data-peak-times-id="{$p->GetPeakTimesId()}" aria-label="Edit peak time"><span class="bi bi-pencil-square" aria-hidden="true"></span><span class="visually-hidden">Edit peak time</span></a>
			{if $p->IsAllDay()}{translate key=AllDay}
			{else}{formatdate date=$p->GetBeginTime() key='period_time'}-{formatdate date=$p->GetEndTime() key='period_time'}
			{/if},
			{if $p->IsEveryDay()}{translate key=Everyday}
			{else}{foreach from=$p->GetWeekdays() item=day}{$DayNames[$day]} {/foreach}
			{/if},
			{if $p->IsAllYear()}{translate key=AllYear}
			{else}{$Months[$p->GetBeginMonth()-1]} {$p->GetBeginDay()}-{$Months[$p->GetEndMonth()-1]} {$p->GetEndDay()}
			{/if}
		</div>
	</div>
{/foreach}
{else}
	<span class="propertyValue">{translate key=None}</span>
{/if}
