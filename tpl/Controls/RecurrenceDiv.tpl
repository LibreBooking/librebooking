<div id="{if isset($prefix)}{$prefix}{/if}repeatDiv" class="repeat-div">
	<div class="form-group">
		<div class="d-flex align-items-center mb-2">
			<label class="fw-bold"
				for="{if isset($prefix)}{$prefix}{/if}repeatOptions">{translate key="RepeatPrompt"}</label>
			<select id="{if isset($prefix)}{$prefix}{/if}repeatOptions" {formname key=repeat_options}
				class="form-select form-select-sm repeat-drop w-auto">
				{foreach from=$RepeatOptions key=k item=v}
					<option value="{$k}">{translate key=$v['key']}</option>
				{/foreach}
			</select>
		</div>

		<div class="d-flex align-items-center flex-wrap mb-2">
			<div id="{if isset($prefix)}{$prefix}{/if}repeatEveryDiv"
				class="recur-toggle d-none days weeks months years d-flex align-items-center flex-wrap">
				<label class="fw-bold"
					for="{if isset($prefix)}{$prefix}{/if}repeatInterval">{translate key="RepeatEveryPrompt"}</label>
				<div class="input-group-sm d-flex align-items-center flex-wrap gap-1">
					<select id="{if isset($prefix)}{$prefix}{/if}repeatInterval" {formname key=repeat_every}
						class="form-select repeat-interval-drop w-auto">
						{html_options values=$RepeatEveryOptions output=$RepeatEveryOptions}
					</select>
					<span class="days">{translate key=$RepeatOptions['daily']['everyKey']}</span>
					<span class="weeks">{translate key=$RepeatOptions['weekly']['everyKey']}</span>
					<span class="months">{translate key=$RepeatOptions['monthly']['everyKey']}</span>
					<span class="years">{translate key=$RepeatOptions['yearly']['everyKey']}</span>
				</div>
			</div>

			<div id="{if isset($prefix)}{$prefix}{/if}repeatOnWeeklyDiv" class="recur-toggle weeks d-none ms-2">
				<div class="btn-group-sm" data-bs-toggle="buttons">
					<input type="checkbox" class="btn-check" id="{if isset($prefix)}{$prefix}{/if}repeatDay0"
						{formname key=repeat_sunday} />
					<label class="btn btn-outline-primary" for="{if isset($prefix)}{$prefix}{/if}repeatDay0">
						{translate key="DaySundayAbbr"}
					</label>

					<input type="checkbox" class="btn-check" id="{if isset($prefix)}{$prefix}{/if}repeatDay1"
						{formname key=repeat_monday} />
					<label class="btn btn-outline-primary" for="{if isset($prefix)}{$prefix}{/if}repeatDay1">
						{translate key="DayMondayAbbr"}
					</label>

					<input type="checkbox" class="btn-check" id="{if isset($prefix)}{$prefix}{/if}repeatDay2"
						{formname key=repeat_tuesday} />
					<label class="btn btn-outline-primary" for="{if isset($prefix)}{$prefix}{/if}repeatDay2">
						{translate key="DayTuesdayAbbr"}
					</label>

					<input type="checkbox" class="btn-check" id="{if isset($prefix)}{$prefix}{/if}repeatDay3"
						{formname key=repeat_wednesday} />
					<label class="btn btn-outline-primary" for="{if isset($prefix)}{$prefix}{/if}repeatDay3">
						{translate key="DayWednesdayAbbr"}
					</label>

					<input type="checkbox" class="btn-check" id="{if isset($prefix)}{$prefix}{/if}repeatDay4"
						{formname key=repeat_thursday} />
					<label class="btn btn-outline-primary" for="{if isset($prefix)}{$prefix}{/if}repeatDay4">
						{translate key="DayThursdayAbbr"}
					</label>

					<input type="checkbox" class="btn-check" id="{if isset($prefix)}{$prefix}{/if}repeatDay5"
						{formname key=repeat_friday} />
					<label class="btn btn-outline-primary" for="{if isset($prefix)}{$prefix}{/if}repeatDay5">
						{translate key="DayFridayAbbr"}
					</label>

					<input type="checkbox" class="btn-check" id="{if isset($prefix)}{$prefix}{/if}repeatDay6"
						{formname key=repeat_saturday} />
					<label class="btn btn-outline-primary" for="{if isset($prefix)}{$prefix}{/if}repeatDay6">
						{translate key="DaySaturdayAbbr"}
					</label>
				</div>
			</div>

			<div id="{if isset($prefix)}{$prefix}{/if}repeatOnMonthlyDiv" class="recur-toggle months d-none ms-2">
				<div class="btn-group-sm" data-bs-toggle="buttons">
					<input type="radio" class="btn-check" {formname key=REPEAT_MONTHLY_TYPE}
						value="{RepeatMonthlyType::DayOfMonth}" id="{if isset($prefix)}{$prefix}{/if}repeatMonthDay"
						checked />
					<label class="btn btn-outline-primary" for="{if isset($prefix)}{$prefix}{/if}repeatMonthDay">
						{translate key="repeatDayOfMonth"}
					</label>

					<input type="radio" class="btn-check" {formname key=REPEAT_MONTHLY_TYPE}
						value="{RepeatMonthlyType::DayOfWeek}" id="{if isset($prefix)}{$prefix}{/if}repeatMonthWeek" />
					<label class="btn btn-outline-primary" for="{if isset($prefix)}{$prefix}{/if}repeatMonthWeek">
						{translate key="repeatDayOfWeek"}
					</label>
				</div>
			</div>
		</div>

		<div id="{if isset($prefix)}{$prefix}{/if}repeatUntilDiv" class="d-none recur-toggle d-flex align-items-center">
			<label class="fw-bold"
				for="{if isset($prefix)}{$prefix}{/if}EndRepeat">{translate key="RepeatUntilPrompt"} <i
					class="bi bi-asterisk text-danger align-top text-small required-asterisk d-none"></i></label>
			<input type="text" id="{if isset($prefix)}{$prefix}{/if}EndRepeat" {formname key=end_repeat_date}
				class="form-control form-control-sm repeat-termination" />
		</div>

		<div id="{if isset($prefix)}{$prefix}{/if}customDatesDiv" class="d-none specific-dates">
			<div class="d-flex align-items-center flex-wrap mb-2">
				<label class="fw-bold"
					for="{if isset($prefix)}{$prefix}{/if}RepeatDate">{translate key=RepeatOn}</label>
				<div class="input-group input-group-sm w-auto">
					<input type="text" id="{if isset($prefix)}{$prefix}{/if}RepeatDate"
						class="form-control form-control-sm" />
					<button class="btn btn-primary" role="button"
						id="{if isset($prefix)}{$prefix}{/if}AddDate">{translate key=AddDate} <i
							class="bi bi-plus-square-fill"></i></button>
				</div>
			</div>
			<div class="repeat-date-list rounded border bg-white my-2 p-2">

			</div>
		</div>
	</div>
</div>
