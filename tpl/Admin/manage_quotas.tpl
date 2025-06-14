{include file='globalheader.tpl' Timepicker=true DataTable=true}
<div id="page-manage-quotas" class="admin-page">
	<h1 class="border-bottom mb-3">{translate key=ManageQuotas}</h1>

	<div class="accordion">
		<form id="addQuotaForm" method="post" role="form" class="form-inline">

			<div class="accordion-item shadow mb-2 panel-default" id="add-quota-panel">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed link-primary fw-bold" type="button"
						data-bs-toggle="collapse" data-bs-target="#add-quota-content" aria-expanded="false"
						aria-controls="add-quota-content">
						<i class="bi bi-plus-circle-fill me-1"></i>{translate key="AddQuota"}
					</button>
				</h2>
				<div class="accordion-collapse collapse" id="add-quota-content">
					<div class="accordion-body" id="addQuota">
						{capture name="schedules" assign="schedules"}
							<select class='form-select form-select-sm w-auto' {formname key=SCHEDULE_ID}
								title='Select Schedule'>
								<option selected='selected' value=''>{translate key=AllSchedules}</option>
								{foreach from=$Schedules item=schedule}
									<option value='{$schedule->GetId()}'>{$schedule->GetName()|replace:',':' '}</option>
								{/foreach}
							</select>
						{/capture}

						{capture name="resources" assign="resources"}
							<select class='form-select form-select-sm w-auto' {formname key=RESOURCE_ID}
								title='Select Resource'>
								<option selected='selected' value=''>{translate key=AllResources}</option>
								{foreach from=$Resources item=resource}
									<option value='{$resource->GetResourceId()}'>{$resource->GetName()|replace:',':' '}</option>
								{/foreach}
							</select>
						{/capture}

						{capture name="groups" assign="groups"}
							<select class='form-select form-select-sm w-auto' {formname key=GROUP} title='Select Group'>
								<option selected='selected' value=''>{translate key=AllGroups}</option>
								{foreach from=$Groups item=group}
									<option value='{$group->Id}'>{$group->Name|replace:',':' '}</option>
								{/foreach}
							</select>
						{/capture}

						{capture name="amount" assign="amount"}
							<input type='number' step='any' class='form-control form-control-sm mid-number' min='0'
								max='10000' value='0' {formname key=LIMIT} title='Quota number' />
						{/capture}

						{capture name="unit" assign="unit"}
							<select class='form-select form-select-sm w-auto' {formname key=UNIT} title='Quota unit'>
								<option value='{QuotaUnit::Hours}'>{translate key=hours}</option>
								<option value='{QuotaUnit::Reservations}'>{translate key=reservations}</option>
							</select>
						{/capture}

						{capture name="duration" assign="duration"}
							<select class='form-select form-select-sm w-auto' {formname key=DURATION}
								title='Quota frequency'>
								<option value='{QuotaDuration::Day}'>{translate key=day}</option>
								<option value='{QuotaDuration::Week}'>{translate key=week}</option>
								<option value='{QuotaDuration::Month}'>{translate key=month}</option>
								<option value='{QuotaDuration::Year}'>{translate key=year}</option>
							</select>
						{/capture}

						{capture name="enforceHours" assign="enforceHours"}
							<div class='form-check form-check-inline'>
								<input class='form-check-input' type='checkbox' id='enforce-all-day' checked='checked'
									value='1' {formname key=ENFORCE_ALL_DAY} />
								<label class='form-check-label' for='enforce-all-day'>{translate key=AllDay}</label>
							</div>
							<div id='enforce-hours-times' class='d-none'>
								<div class='form-group form-group-sm d-flex align-items-center'>
									<span class='me-1'>{translate key=Between}</span>
									<label for='enforce-time-start'
										class='visually-hidden'>{translate key=StartTime}</label>
									<label for='enforce-time-end' class='visually-hidden'>{translate key=EndTime}</label>
									<input type='text' class='form-control form-control-sm time' id='enforce-time-start'
										size='6' value='12:00am' {formname key=BEGIN_TIME} />
									-
									<input type='text' class='form-control form-control-sm time' id='enforce-time-end'
										size='6' value='12:00am' {formname key=END_TIME} />
								</div>
							</div>
						{/capture}

						{capture name="enforceDays" assign="enforceDays"}
							<div class='form-check form-check-inline'>
								<input class='form-check-input' type='checkbox' id='enforce-every-day' checked='checked'
									value='1' {formname key=ENFORCE_EVERY_DAY} />
								<label class='form-check-label' for='enforce-every-day'>{translate key=Everyday}</label>
							</div>
							<div id='enforce-days' class='inline d-none'>
								<div class='btn-group-sm' data-bs-toggle='buttons'>
									<input type='checkbox' class='btn-check' id='enforce-sun' value='0'
										{formname key=DAY multi=true} />
									<label class='btn btn-outline-primary'
										for='enforce-sun'>{translate key="DaySundayAbbr"}</label>

									<input type='checkbox' class='btn-check' id='enforce-mon' value='1'
										{formname key=DAY multi=true} />
									<label class='btn btn-outline-primary'
										for='enforce-mon'>{translate key="DayMondayAbbr"}</label>

									<input type='checkbox' class='btn-check' id='enforce-tue' value='2'
										{formname key=DAY multi=true} />
									<label class='btn btn-outline-primary'
										for='enforce-tue'>{translate key="DayTuesdayAbbr"}</label>

									<input type='checkbox' class='btn-check' id='enforce-wed' value='3'
										{formname key=DAY multi=true} />
									<label class='btn btn-outline-primary'
										for='enforce-wed'>{translate key="DayWednesdayAbbr"}</label>

									<input type='checkbox' class='btn-check' id='enforce-thu' value='4'
										{formname key=DAY multi=true} />
									<label class='btn btn-outline-primary'
										for='enforce-thu'>{translate key="DayThursdayAbbr"}</label>

									<input type='checkbox' class='btn-check' id='enforce-fri' value='5'
										{formname key=DAY multi=true} />
									<label class='btn btn-outline-primary'
										for='enforce-fri'>{translate key="DayFridayAbbr"}</label>

									<input type='checkbox' class='btn-check' id='enforce-sat' value='6'
										{formname key=DAY multi=true} />
									<label class='btn btn-outline-primary'
										for='enforce-sat'>{translate key="DaySaturdayAbbr"}</label>
								</div>
							</div>
						{/capture}

						{capture name="scope" assign="scope"}
							<div class='form-group'>
								<label for="quoteScope"
									class='visually-hidden'>{translate key=IncludingCompletedReservations}</label>
								<select class='form-select form-select-sm' {formname key=QUOTA_SCOPE} id="quoteScope">
									<option value='{QuotaScope::IncludeCompleted}'>
										{translate key=IncludingCompletedReservations}
									</option>
									<option value='{QuotaScope::ExcludeCompleted}'>
										{translate key=NotCountingCompletedReservations}
									</option>
								</select>
							</div>
						{/capture}

						<div class="add-quota-line d-flex align-items-center flex-wrap gap-1 mb-2">
							{translate key=QuotaConfiguration args="$schedules,$resources,$groups,$amount,$unit,$duration"}
							{$scope}</div>
						<div class="add-quota-line d-flex align-items-center flex-wrap gap-1 mb-2">
							{translate key=QuotaEnforcement args="$enforceHours,$enforceDays"}</div>

						<div class="fw-semibold fst-italic">{translate key=QuotaReminder}</div>


						<div class="accordion-footer border-top pt-3">
							{add_button class="btn-sm"}
							{reset_button class="btn-sm"}
							{indicator}
						</div>
					</div>
				</div>
			</div>
		</form>

		<div class="accordion-item shadow panel-default" id="list-quotas-panel">
			<h2 class="accordion-header">
				<button class="accordion-button collapsed link-primary fw-bold" type="button" data-bs-toggle="collapse"
					data-bs-target="#quotaList-content" aria-expanded="false" aria-controls="quotaList-content">
					{translate key="AllQuotas"}
				</button>
			</h2>
			<div id="quotaList-content" class="accordion-collapse collapse">
				<div class="accordion-body no-padding" id="quotaList">
					{assign var=tableId value=blackoutTable}
					<table class="table table-striped table-hover border-top w-100" id="{$tableId}">
						<thead>
							<tr>
								<th>{translate key=ManageQuotas}</th>
							</tr>
						</thead>
						{foreach from=$Quotas item=quota}
							{capture name="scheduleName" assign="scheduleName"}
								<span class='fw-bold'>{if $quota->ScheduleName ne ""}
										{$quota->ScheduleName|replace:',':' '}
									{else}
										{translate key="AllSchedules"}
									{/if}
								</span>
							{/capture}
							{capture name="resourceName" assign="resourceName"}
								<span class='fw-bold'>{if $quota->ResourceName ne ""}
										{$quota->ResourceName|replace:',':' '}
									{else}
										{translate key="AllResources"}
									{/if}
								</span>
							{/capture}
							{capture name="groupName" assign="groupName"}
								<span class='fw-bold'>
									{if $quota->GroupName ne ""}
										{$quota->GroupName|replace:',':' '}
									{else}
										{translate key="AllGroups"}
									{/if}
								</span>
							{/capture}
							{capture name="amount" assign="amount"}
								<span class='fw-bold'>{$quota->Limit}</span>
							{/capture}
							{capture name="unit" assign="unit"}
								<span class='fw-bold'>{translate key=$quota->Unit}</span>
							{/capture}
							{capture name="duration" assign="duration"}
								<span class='fw-bold'>{translate key=$quota->Duration}</span>
							{/capture}
							{capture name="enforceHours" assign="enforceHours"}
								<span class='fw-bold'>
									{if $quota->AllDay}
										{translate key=AllDay}
									{else}
										{translate key=Between} {formatdate date=$quota->EnforcedStartTime key=period_time} -
										{formatdate date=$quota->EnforcedEndTime key=period_time}
									{/if}
								</span>
							{/capture}
							{capture name="enforceDays" assign="enforceDays"}
								<span class='fw-bold'>
									{if $quota->Everyday}
										{translate key=Everyday}
									{else}
										{foreach from=$quota->EnforcedDays item=day}
											{translate key=$DayNames[$day]}
										{/foreach}
									{/if}
								</span>
							{/capture}
							{capture name="scope" assign="scope"}
								{if $quota->Scope == QuotaScope::IncludeCompleted}
									{translate key=IncludingCompletedReservations}
								{else}
									{translate key=NotCountingCompletedReservations}
								{/if}
							{/capture}
							<tr>
								<td class="quotaItem clearfix">
									<div class="float-start">
										{translate key=QuotaConfiguration args="$scheduleName,$resourceName,$groupName,$amount,$unit,$duration"}
										<span class="fw-bold">{$scope}</span>.
										{translate key=QuotaEnforcement args="$enforceHours,$enforceDays"}
									</div>
									<a href="#" quotaId="{$quota->Id}" class="delete float-end"><span
											class="bi bi-trash3-fill text-danger icon remove"></span></a>
								</td>
							</tr>
						{foreachelse}
							{translate key=None}
						{/foreach}
					</table>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="deleteDialog" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="deleteModalLabel">{translate key=Delete}</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">
						{translate key=DeleteWarning}
					</div>
				</div>
				<div class="modal-footer">
					<form id="deleteQuotaForm" method="post">
						{cancel_button}
						{delete_button}
						{indicator}
					</form>
				</div>
			</div>
		</div>
	</div>

	{csrf_token}
	{include file="javascript-includes.tpl" Timepicker=true DataTable=true}
	{datatable tableId=$tableId}
	{jsfile src="ajax-helpers.js"}
	{jsfile src="admin/quota.js"}
	{jsfile src="js/jquery.form-3.09.min.js"}

	<script type="text/javascript">
		$(document).ready(function() {

			var actions = {
				addQuota: '{ManageQuotasActions::AddQuota}',
				deleteQuota: '{ManageQuotasActions::DeleteQuota}'
			};

			var quotaOptions = {
				submitUrl: '{$smarty.server.SCRIPT_NAME}',
				saveRedirect: '{$smarty.server.SCRIPT_NAME}',
				actions: actions
			};

			$('#enforce-time-start').timepicker({
				timeFormat: '{$TimeFormat}'
			});
			$('#enforce-time-end').timepicker({
				timeFormat: '{$TimeFormat}'
			});

			var quotaManagement = new QuotaManagement(quotaOptions);
			quotaManagement.init();

			//$('#add-quota-panel').showHidePanel();
		});
	</script>
</div>
{include file='globalfooter.tpl'}