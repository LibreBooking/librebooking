{include file='globalheader.tpl' InlineEdit=true Fullcalendar=true Timepicker=true DataTable=true}

<div id="page-manage-schedules" class="admin-page">

	<div class="border-bottom mb-3 clearfix">
		<h1 class="float-start">{translate key=ManageSchedules}</h1>
		<a href="#" class="add-link btn btn-sm btn-primary float-end" id="add-schedule">
			<span class="bi bi-plus-circle-fill me-1 icon add"></span>{translate key="AddSchedule"}
		</a>
	</div>

	<div class="card shadow" id="list-schedules-panel">
		<div class="card-body no-padding" id="scheduleList">
			<div class="accordion" id="scheduleAccordion">
				{assign var=tableIdFilter value=schedulesTable}
				<table class="table table-borderless w-100" id="{$tableIdFilter}">
					<thead class="d-none">
						<tr>
							<th>{translate key="AllSchedules"}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$Schedules item=schedule}
							<tr>
								<td>
									{assign var=id value=$schedule->GetId()}
									{capture name=daysVisible}<span
											class='propertyValue daysVisible inlineUpdate fw-bold text-decoration-underline pe-auto'
											data-type='number' data-pk='{$id}' data-name='{FormKeys::SCHEDULE_DAYS_VISIBLE}'
										data-min='0'>{$schedule->GetDaysVisible()}</span>{/capture}
									{assign var=dayOfWeek value=$schedule->GetWeekdayStart()}
									{capture name=dayName}<span
											class='propertyValue dayName inlineUpdate fw-bold text-decoration-underline pe-auto'
											data-type='select' data-pk='{$id}' data-name='{FormKeys::SCHEDULE_WEEKDAY_START}'
											data-value='{$dayOfWeek}'>{if $dayOfWeek == Schedule::Today}{$Today}{else}{$DayNames[$dayOfWeek]}{/if}</span>
									{/capture}
									<div class="accordion-item">
										<h2 class="accordion-header">
											<button class="accordion-button" type="button" data-bs-toggle="collapse"
												data-bs-target="#panel{$id}" aria-expanded="true"
												aria-controls="panel{$id}">
												{$schedule->GetName()}
											</button>
										</h2>
										<div id="panel{$id}" class="accordion-collapse collapse show">
											<div class="accordion-body">
												<div class="scheduleDetails row" data-schedule-id="{$id}">
													<div class="col-12 col-sm-6">
														<input type="hidden" class="id" value="{$id}" />
														<input type="hidden" class="daysVisible" value="{$daysVisible}" />
														<input type="hidden" class="dayOfWeek" value="{$dayOfWeek}" />

														<div>
															<span class="title scheduleName fw-bold" data-type="text"
																data-pk="{$id}"
																data-name="{FormKeys::SCHEDULE_NAME}">{$schedule->GetName()}</span>
															<a class="update renameButton link-primary" href="#"><span
																	class="visually-hidden">{translate key=Rename}</span><span
																	class="bi bi-pencil-square"></span></a>
														</div>

														<div>
															{translate key="LayoutDescription" args="{$smarty.capture.dayName},
														{$smarty.capture.daysVisible}" class="fw-bold"}</div>

														<div>
															{translate key='ScheduleAdministrator'}
															<span class="propertyValue scheduleAdmin fw-bold"
																data-type="select" data-pk="{$id}"
																data-value="{$schedule->GetAdminGroupId()}"
																data-name="{FormKeys::SCHEDULE_ADMIN_GROUP_ID}">{($GroupLookup[$schedule->GetAdminGroupId()]) ? $GroupLookup[$schedule->GetAdminGroupId()]->Name : 'None'}</span>
															{if $AdminGroups|default:array()|count > 0}
																<a class="link-primary update changeScheduleAdmin"><span
																		class="visually-hidden">{translate key='ScheduleAdministrator'}</span><span
																		class="bi bi-pencil-square"></span>
																</a>
															{/if}

														</div>

														<div>
															<div class="availabilityPlaceHolder d-inline-block">
																{include file="Admin/Schedules/manage_availability.tpl" schedule=$schedule timezone=$Timezone}
																<a class="update changeAvailability link-primary" href="#">
																	<span
																		class="visually-hidden">{translate key='Availability'}</span>
																	<span class="bi bi-pencil-square"></span>
																</a>
															</div>
														</div>

														<div class="maximumConcurrentContainer"
															data-concurrent="{$schedule->GetTotalConcurrentReservations()}">
															{if $schedule->EnforceConcurrentReservationMaximum()}
																{translate key=ScheduleConcurrentMaximum args=$schedule->GetTotalConcurrentReservations()}
															{else}
																{translate key=ScheduleConcurrentMaximumNone}
															{/if}
															<a href="#"
																class="update changeScheduleConcurrentMaximum link-primary">
																<span
																	class="visually-hidden">{translate key='ScheduleMaximumConcurrent'}</span>
																<span class="bi bi-pencil-square"></span>
															</a>
														</div>

														<div class="resourcesPerReservationContainer"
															data-maximum="{$schedule->GetMaxResourcesPerReservation()}">
															{if $schedule->EnforceMaxResourcesPerReservation()}
																{translate key=ScheduleResourcesPerReservationMaximum args=$schedule->GetMaxResourcesPerReservation()}
															{else}
																{translate key=ScheduleResourcesPerReservationNone}
															{/if}
															<a href="#"
																class="update changeResourcesPerReservation link-primary">
																<span
																	class="visually-hidden">{translate key='ScheduleResourcesPerReservation'}</span>
																<span class="bi bi-pencil-square"></span>
															</a>
														</div>

														<div>
															{translate key=DefaultStyle}
															<span
																class="propertyValue defaultScheduleStyle inlineUpdate fw-bold text-decoration-underline"
																data-type="select" data-pk="{$id}"
																data-name="{FormKeys::SCHEDULE_DEFAULT_STYLE}"
																data-value="{$schedule->GetDefaultStyle()}">{$StyleNames[$schedule->GetDefaultStyle()]}</span>
														</div>

														{if $CreditsEnabled}
															<span>{translate key=PeakTimes}</span>
															<a class="update changePeakTimes link-primary" href="#">
																<span class="visually-hidden">{translate key=PeakTimes}</span>
																<span class="bi bi-pencil-square"></span>
															</a>
															<div class="peakPlaceHolder">
																{include file="Admin/Schedules/manage_peak_times.tpl" Layout=$Layouts[$id] Months=$Months DayNames=$DayNames}
															</div>
														{/if}

														<div>{translate key=Resources}
															<span class="propertyValue fw-bold">
																{if array_key_exists($id, $Resources)}
																	{foreach from=$Resources[$id] item=r name=resources_loop}
																		{$r->GetName()|escape}{if !$smarty.foreach.resources_loop.last},
																		{/if}
																	{/foreach}
																{else}
																	{translate key=None}
																{/if}
															</span>
														</div>

														{if $schedule->GetIsCalendarSubscriptionAllowed()}
															<div>
																<span>{translate key=PublicId}</span>
																<span
																	class="propertyValue fw-bold">{$schedule->GetPublicId()}</span>
															</div>
														{/if}

													</div>

													<div class="layout col-12 col-sm-6">
														{function name="display_periods"}
															{foreach from=$Layouts[$id]->GetSlots($day) item=period name=layouts}
																{if $period->IsReservable() == $showReservable}
																	{$period->Start->Format("H:i")} - {$period->End->Format("H:i")}
																	{if $period->IsLabelled()}
																		{$period->Label}
																	{/if}
																	{if !$smarty.foreach.layouts.last}, {/if}
																{/if}
															{foreachelse}
																{translate key=None}
															{/foreach}
														{/function}

														<div>
															{translate key=ScheduleLayout args=$schedule->GetTimezone()}
															<a class="update changeLayoutButton link-primary" href="#"
																title="{translate key=ChangeLayout}">
																<span class="bi bi-pencil-square"
																	data-layout-type="{$Layouts[$id]->GetType()}"></span>
																<span
																	class="visually-hidden">{translate key=ChangeLayout}</span>
															</a>
														</div>
														<input type="hidden" class="timezone"
															value="{$schedule->GetTimezone()}" />

														{if $Layouts[$id]->UsesDailyLayouts()}
															<input type="hidden" class="usesDailyLayouts" value="true" />
															{translate key=LayoutVariesByDay} - <a href="#layout{$id}"
																class="link-primary" role="button"
																data-bs-toggle="collapse">{translate key=ShowHide}</a>
															<div class="collapse" id="layout{$id}">
																{foreach from=DayOfWeek::Days() item=day}
																	{$DayNames[$day]}
																	<div class="reservableSlots" id="reservableSlots_{$day}"
																		ref="reservableEdit_{$day}">
																		{display_periods showReservable=true day=$day}
																	</div>
																	<div class="blockedSlots" id="blockedSlots_{$day}"
																		ref="blockedEdit_{$day}">
																		{display_periods showReservable=false day=$day}
																	</div>
																{/foreach}
															</div>
															<div class="mt-4 fw-bold">
																{translate key=ThisScheduleUsesAStandardLayout}</div>
															<div><a href="#" class="update switchLayout link-primary"
																	data-switch-to="{ScheduleLayout::Custom}">{translate key=SwitchToACustomLayout}</a>
															</div>
														{elseif $Layouts[$id]->UsesCustomLayout()}
															<div class="mt-4 fw-bold">
																{translate key=ThisScheduleUsesACustomLayout}</div>
															<div><a href="#" class="update switchLayout link-primary"
																	data-switch-to="{ScheduleLayout::Standard}">{translate key=SwitchToAStandardLayout}</a>
															</div>
														{else}
															<input type="hidden" class="usesDailyLayouts" value="false" />
															{translate key=ReservableTimeSlots}
															<div class="reservableSlots" id="reservableSlots"
																ref="reservableEdit">
																{display_periods showReservable=true day=null}
															</div>
															{translate key=BlockedTimeSlots}
															<div class="blockedSlots" id="blockedSlots" ref="blockedEdit">
																{display_periods showReservable=false day=null}
															</div>
															<div class="mt-4 fw-bold">
																{translate key=ThisScheduleUsesAStandardLayout}</div>
															<div><a href="#" class="update switchLayout link-primary"
																	data-switch-to="{ScheduleLayout::Custom}">{translate key=SwitchToACustomLayout}</a>
															</div>
														{/if}
													</div>
													<div class="actions col-12">
														<div
															class="alert alert-light  d-flex align-items-center gap-1 mb-0">
															{if $schedule->GetIsDefault()}
																<span
																	class="note fst-italic">{translate key=ThisIsTheDefaultSchedule}</span>
																<div class="vr"></div>
																<span
																	class="note fst-italic">{translate key=DefaultScheduleCannotBeDeleted}</span>
																<div class="vr"></div>
															{else}
																<a class="update makeDefaultButton link-primary"
																	href="#">{translate key=MakeDefault}</a>
																<div class="vr"></div>
																<a class="update deleteScheduleButton link-primary"
																	href="#">{translate key=Delete}</a>
																<div class="vr"></div>
															{/if}
															{if $schedule->GetIsCalendarSubscriptionAllowed()}
																<a class="update disableSubscription link-primary"
																	href="#">{translate key=TurnOffSubscription}</a>
																<div class="vr"></div>
															{else}
																<a class="update enableSubscription link-primary"
																	href="#">{translate key=TurnOnSubscription}</a>
															{/if}
															{if $schedule->GetIsCalendarSubscriptionAllowed()}
																<a target="_blank" class="link-primary"
																	href="{$schedule->GetSubscriptionUrl()->GetAtomUrl()}"> <i
																		class="bi bi-rss-fill link-primary me-1"></i>Atom</a>
																<div class="vr"></div>
																<a target="_blank" class="link-primary"
																	href="{$schedule->GetSubscriptionUrl()->GetWebcalUrl()}">iCalendar</a>
															{/if}
															{indicator id="action-indicator"}
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div id="addDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addScheduleDialogLabel"
		aria-hidden="true">
		<form id="addScheduleForm" method="post">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addScheduleDialogLabel">{translate key=AddSchedule}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body vstack gap-2">
						<div class="form-group">
							<label class="fw-bold" for="addName">{translate key=Name}<i
									class="bi bi-asterisk text-danger align-top" style="font-size: 0.5rem;"></i></label>
							<input type="text" id="addName" class="form-control has-feedback required"
								{formname key=SCHEDULE_NAME} />
						</div>
						<div class="form-group">
							<label class="fw-bold" for="addStartsOn">{translate key=StartsOn}</label>
							<select {formname key=SCHEDULE_WEEKDAY_START} class="form-select" id="addStartsOn">
								<option value="{Schedule::Today}">{$Today}</option>
								{foreach from=$DayNames item="dayName" key="dayIndex"}
									<option value="{$dayIndex}">{$dayName}</option>
								{/foreach}
							</select>
						</div>
						<div class="form-group">
							<label class="fw-bold" for="addNumDaysVisible">{translate key=NumberOfDaysVisible}</label>
							<input type="number" min="1" max="100" class="form-control required" id="addNumDaysVisible"
								value="7" {formname key=SCHEDULE_DAYS_VISIBLE} />
						</div>
						<div class="form-group">
							<label class="fw-bold" for="addSameLayoutAs">{translate key=UseSameLayoutAs}</label>
							<select class="form-select" {formname key=SCHEDULE_ID} id="addSameLayoutAs">
								{foreach from=$SourceSchedules item=schedule}
									<option value="{$schedule->GetId()}">{$schedule->GetName()}</option>
								{/foreach}
							</select>
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{add_button submit=true}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<input type="hidden" id="activeId" value="" />

	<div id="deleteDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteScheduleDialogLabel"
		aria-hidden="true">
		<form id="deleteForm" method="post">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteScheduleDialogLabel">{translate key=Delete}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="fw-bold"
								for="targetScheduleId">{translate key=MoveResourcesAndReservations}</label>
							<select id="targetScheduleId" {formname key=SCHEDULE_ID} class="form-select required">
								<option value="">-- {translate key=Schedule} --</option>
								{foreach from=$SourceSchedules item=schedule}
									<option value="{$schedule->GetId()}">{$schedule->GetName()}</option>
								{/foreach}
							</select>
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{delete_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="changeLayoutDialog" class="modal fade" tabindex="-1" role="dialog"
		aria-labelledby="changeLayoutDialogLabel" aria-hidden="true">
		<form id="changeLayoutForm" method="post" role="form" class="form-inline">
			<div class="modal-dialog modal-lg modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="changeLayoutDialogLabel">{translate key=ChangeLayout}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body row gy-2">
						<div class="col-12">
							<div class="validationSummary alert alert-danger no-show">
								<ul>{async_validator id="layoutValidator" key="ValidLayoutRequired"}</ul>
							</div>
						</div>
						<div class="col-12">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="usesSingleLayout"
									{formname key=USING_SINGLE_LAYOUT}>
								<label class="form-check-label"
									for="usesSingleLayout">{translate key=UseSameLayoutForAllDays}</label>
							</div>
						</div>

						{function name=display_slot_inputs}
							<div id="{$id}" class="row">
								{assign var=suffix value=""}
								{if $day!=null}
									{assign var=suffix value="_$day"}
								{/if}
								<div class="col-6">
									<label class="fw-bold"
										for="reservableEdit{$suffix}">{translate key=ReservableTimeSlots}</label>
									<textarea class="reservableEdit form-control" id="reservableEdit{$suffix}"
										name="{FormKeys::SLOTS_RESERVABLE}{$suffix}"></textarea>
								</div>
								<div class="col-6">
									<label class="fw-bold"
										for="blockedEdit{$suffix}">{translate key=BlockedTimeSlots}</label> <a href="#"
										class="autofillBlocked link-primary" title="{translate key=Autofill}"><i
											class="bi bi-magic"></i>
										{translate key=Autofill}</a>
									<textarea class="blockedEdit form-control" id="blockedEdit{$suffix}"
										name="{FormKeys::SLOTS_BLOCKED}{$suffix}"></textarea>
								</div>
							</div>
						{/function}

						<div class="col-12" id="dailySlots">
							<div role="tabpanel" id="tabs">
								<ul class="nav nav-tabs mb-2" role="tablist">
									<li role="presentation" class="nav-item"><a href="#tabs-0"
											class="nav-link active link-primary" aria-controls="tabs-0" role="tab"
											data-bs-toggle="tab">{$DayNames[0]}</a>
									</li>
									<li role="presentation" class="nav-item"><a href="#tabs-1"
											class="nav-link link-primary" aria-controls="tabs-1" role="tab"
											data-bs-toggle="tab">{$DayNames[1]}</a>
									</li>
									<li role="presentation" class="nav-item"><a href="#tabs-2"
											class="nav-link link-primary" aria-controls="tabs-2" role="tab"
											data-bs-toggle="tab" class="nav-item">{$DayNames[2]}</a></li>
									<li role="presentation" class="nav-item"><a href="#tabs-3"
											class="nav-link link-primary" aria-controls="tabs-3" role="tab"
											data-bs-toggle="tab">{$DayNames[3]}</a>
									</li>
									<li role="presentation" class="nav-item"><a href="#tabs-4"
											class="nav-link link-primary" aria-controls="tabs-4" role="tab"
											data-bs-toggle="tab">{$DayNames[4]}</a>
									</li>
									<li role="presentation" class="nav-item"><a href="#tabs-5"
											class="nav-link link-primary" aria-controls="tabs-5" role="tab"
											data-bs-toggle="tab">{$DayNames[5]}</a>
									</li>
									<li role="presentation" class="nav-item"><a href="#tabs-6"
											class="nav-link link-primary" aria-controls="tabs-6" role="tab"
											data-bs-toggle="tab">{$DayNames[6]}</a>
									</li>
								</ul>
								<div class="tab-content">
									<div role="tab-pane" class="tab-pane active" id="tabs-0">
										{display_slot_inputs day='0'}
									</div>
									<div role="tabpanel" class="tab-pane" id="tabs-1">
										{display_slot_inputs day='1'}
									</div>
									<div role="tabpanel" class="tab-pane" id="tabs-2">
										{display_slot_inputs day='2'}
									</div>
									<div role="tabpanel" class="tab-pane" id="tabs-3">
										{display_slot_inputs day='3'}
									</div>
									<div role="tabpanel" class="tab-pane" id="tabs-4">
										{display_slot_inputs day='4'}
									</div>
									<div role="tabpanel" class="tab-pane" id="tabs-5">
										{display_slot_inputs day='5'}
									</div>
									<div role="tabpanel" class="tab-pane" id="tabs-6">
										{display_slot_inputs day='6'}
									</div>
								</div>
							</div>
						</div>

						{display_slot_inputs id="staticSlots" day=null}

						<div class="slotTimezone col-12 d-flex align-items-center gap-1">
							<label class="fw-bold" for="layoutTimezone">{translate key=Timezone}</label>
							<select {formname key=TIMEZONE} id="layoutTimezone"
								class="form-select form-select-sm w-auto">
								{html_options values=$TimezoneValues output=$TimezoneOutput}
							</select>
						</div>

						<div class="slotWizard col-12">
							<div class="d-flex align-items-center flex-wrap gap-1">
								{capture name="layoutConfig" assign="layoutConfig"}
									<input type='number' min='0' step='15' value='30' id='quickLayoutConfig' size='5'
										title='Minutes' class='form-control form-control-sm' />
								{/capture}
								{capture name="layoutStart" assign="layoutStart"}
									<input type='text' value='08:00' id='quickLayoutStart' size='10' title='From time'
										class='form-control form-control-sm' maxlength='5' />
								{/capture}
								{capture name="layoutEnd" assign="layoutEnd"}
									<input type='text' value='18:00' id='quickLayoutEnd' size='10' title='End time'
										class='form-control form-control-sm' maxlength='5' />
								{/capture}
								{translate key=QuickSlotCreation args="$layoutConfig,$layoutStart,$layoutEnd"}
								<a href="#" id="createQuickLayout" type="button"
									class="btn btn-primary btn-sm">{translate key=Create}</a>
							</div>
						</div>
						<div class="slotHelpText col-12">
							<p>{translate key=Format}: <span class="font-monospace">HH:MM - HH:MM
								</span> {translate key=OptionalLabel}</p>

							<p>{translate key=LayoutInstructions}</p>
						</div>

					</div>
					<div class="modal-footer">
						{cancel_button}
						{update_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="peakTimesDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="peakTimesDialogLabel"
		aria-hidden="true">
		<form id="peakTimesForm" method="post">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="peakTimesDialogLabel">{translate key=PeakTimes}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group mb-2">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="peakAllDay"
									{formname key=PEAK_ALL_DAY} />
								<label class="form-check-label" for="peakAllDay">{translate key=AllDay}</label>
							</div>
							<div id="peakTimes" class="d-flex align-items-center flex-wrap gap-1">
								<label class="fw-bold" for="peakStartTime"> {translate key=Between}</label>
								<label for="peakStartTime" class="visually-hidden">Peak Begin Time</label>
								<label for="peakEndTime" class="visually-hidden">Peak End Time</label>
								<input type="text" id="peakStartTime"
									class="form-control form-control-sm w-auto timeinput timepicker"
									value="{formatdate date=$DefaultDate format='h:i A'}"
									{formname key=PEAK_BEGIN_TIME} />
								-
								<input type="text" id="peakEndTime"
									class="form-control form-control-sm w-auto timeinput timepicker"
									value="{formatdate date=$DefaultDate->AddHours(9) format='h:i A'}"
									{formname key=PEAK_END_TIME} />
							</div>
						</div>
						<div class="form-group mb-2">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="peakEveryDay" checked="checked"
									{formname key=PEAK_EVERY_DAY} />
								<label class="form-check-label" for="peakEveryDay">{translate key=Everyday}</label>
							</div>
							<div id="peakDayList" class="no-show">
								<div class="btn-group-sm" data-bs-toggle="buttons">
									<input type="checkbox" class="btn-check" id="peakDay0"
										{formname key=repeat_sunday} />
									<label class="btn btn-outline-primary" for="peakDay0">
										{$DayNames[0]}
									</label>

									<input type="checkbox" class="btn-check" id="peakDay1"
										{formname key=repeat_monday} />
									<label class="btn btn-outline-primary" for="peakDay1">
										{$DayNames[1]}
									</label>

									<input type="checkbox" class="btn-check" id="peakDay2"
										{formname key=repeat_tuesday} />
									<label class="btn btn-outline-primary" for="peakDay2">
										{$DayNames[2]}
									</label>

									<input type="checkbox" class="btn-check" id="peakDay3"
										{formname key=repeat_wednesday} />
									<label class="btn btn-outline-primary" for="peakDay3">
										{$DayNames[3]}
									</label>

									<input type="checkbox" class="btn-check" id="peakDay4"
										{formname key=repeat_thursday} />
									<label class="btn btn-outline-primary" for="peakDay4">
										{$DayNames[4]}
									</label>

									<input type="checkbox" class="btn-check" id="peakDay5"
										{formname key=repeat_friday} />
									<label class="btn btn-outline-primary" for="peakDay5">
										{$DayNames[5]}
									</label>

									<input type="checkbox" class="btn-check" id="peakDay6"
										{formname key=repeat_saturday} />
									<label class="btn btn-outline-primary" for="peakDay6">
										{$DayNames[6]}
									</label>
								</div>
							</div>
						</div>
						<div class="form-group mb-2">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="peakAllYear" checked="checked"
									{formname key=PEAK_ALL_YEAR} />
								<label class="form-check-label" for="peakAllYear">{translate key=AllYear}</label>
							</div>
							<div id="peakDateRange" class="no-show d-flex align-items-center gap-1">
								<label for="peakBeginMonth" class="fw-bold">{translate key=BeginDate}</label>

								<select id="peakBeginMonth" class="form-select form-select-sm"
									{formname key=PEAK_BEGIN_MONTH}>
									{foreach from=$Months item=month name=startMonths}
										<option value="{$smarty.foreach.startMonths.iteration}">{$month}</option>
									{/foreach}
								</select>

								<label for="peakBeginDay" class="no-show">Peak Begin Day</label>
								<select id="peakBeginDay" class="form-select form-select-sm"
									{formname key=PEAK_BEGIN_DAY}>
									{foreach from=$DayList item=day}
										<option value="{$day}">{$day}</option>
									{/foreach}
								</select>

								<label for="peakEndMonth" class="fw-bold ms-2">{translate key=EndDate}</label>
								<select id="peakEndMonth" class="form-select form-select-sm"
									{formname key=PEAK_END_MONTH}>
									{foreach from=$Months item=month name=endMonths}
										<option value="{$smarty.foreach.endMonths.iteration}">{$month}</option>
									{/foreach}
								</select>
								<label for="peakEndDay" class="no-show">Peak End Day</label>
								<select id="peakEndDay" class="form-select form-select-sm" {formname key=PEAK_END_DAY}>
									{foreach from=$DayList item=day}
										<option value="{$day}">{$day}</option>
									{/foreach}
								</select>

							</div>
						</div>

						<input type="hidden" {formname key=PEAK_DELETE} id="deletePeakTimes" value="" />
					</div>
					<div class="modal-footer">
						{delete_button class='pull-left' id="deletePeakBtn"}
						{cancel_button}
						{update_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="availabilityDialog" class="modal fade" tabindex="-1" role="dialog"
		aria-labelledby="availabilityDialogLabel" aria-hidden="true">
		<form id="availabilityForm" method="post">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="availabilityDialogLabel">{translate key=Availability}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="availableAllYear"
									{formname key=AVAILABLE_ALL_YEAR} />
								<label class="form-check-label"
									for="availableAllYear">{translate key=AvailableAllYear}</label>
							</div>
							<div id="availableDates" class="d-flex align-items-center gap-1">
								<label for="availabilityStartDate">{translate key=AvailableBetween}</label>
								<label for="availabilityEndDate" class="visually-hidden">Available End Date</label>
								<input type="date" id="availabilityStartDate"
									class="form-control form-control-sm inline-block dateinput" />
								<input type="hidden" id="formattedBeginDate" {formname key=AVAILABLE_BEGIN_DATE} />
								-
								<input type="date" id="availabilityEndDate"
									class="form-control form-control-sm inline-block dateinput" />
								<input type="hidden" id="formattedEndDate" {formname key=AVAILABLE_END_DATE} />
							</div>
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{update_button}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="switchLayoutDialog" class="modal fade" tabindex="-1" role="dialog"
		aria-labelledby="switchLayoutDialogLabel" aria-hidden="true">
		<form id="switchLayoutForm" method="post">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="switchLayoutDialogLabel">{translate key=ChangeLayout}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-warning">
							{translate key=SwitchLayoutWarning}
						</div>
						<input type="hidden" id="switchLayoutTypeId" {formname key=LAYOUT_TYPE} />
						<div class="clearfix"></div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{update_button submit=true}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="customLayoutDialog" class="modal fade" tabindex="-1" role="dialog"
		aria-labelledby="customLayoutDialogLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="customLayoutDialogLabel">{translate key=ChangeLayout}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div id="calendar"></div>
				</div>
			</div>
		</div>
	</div>

	<form id="layoutSlotForm" method="post">
		<input type="hidden" id="slotStartDate" {formname key=BEGIN_DATE} />
		<input type="hidden" id="slotEndDate" {formname key=END_DATE} />
		<input type="hidden" id="slotId" {formname key=LAYOUT_PERIOD_ID} />
	</form>

	<div id="deleteCustomLayoutDialog" style="z-index:10000;" class="default-box-shadow">
		<form id="deleteCustomTimeSlotForm" method="post">
			<input type="hidden" id="deleteSlotStartDate" {formname key=BEGIN_DATE} />
			<input type="hidden" id="deleteSlotEndDate" {formname key=END_DATE} />
			<div>{translate key=DeleteThisTimeSlot}</div>
			<div>
				{cancel_button id=cancelDeleteSlot}
				{delete_button id=deleteSlot}
			</div>
		</form>
	</div>

	<div id="confirmCreateSlotDialog" class="default-box-shadow" style="z-index:10000;">
		{cancel_button id="cancelCreateSlot"}
		{add_button id="confirmCreateOK"}
	</div>

	<div id="concurrentMaximumDialog" class="modal fade" tabindex="-1" role="dialog"
		aria-labelledby="concurrentMaximumDialogLabel" aria-hidden="true">
		<form id="concurrentMaximumForm" method="post">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="concurrentMaximumDialogLabel">
							{translate key=ScheduleMaximumConcurrent}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-info">
							{translate key=ScheduleMaximumConcurrentNote}
						</div>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="maximumConcurrentUnlimited"
								{formname key=MAXIMUM_CONCURRENT_UNLIMITED} />
							<label class="form-check-label"
								for="maximumConcurrentUnlimited">{translate key=Unlimited}</label>
						</div>
						<div class="form-group">
							<label class="fw-bold" for="maximumConcurrent">{translate key=Resources}</label>
							<input type="number" class="form-control required" min="0" id="maximumConcurrent"
								{formname key=MAXIMUM_CONCURRENT_RESERVATIONS} />
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{update_button submit=true}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	<div id="resourcesPerReservationDialog" class="modal fade" tabindex="-1" role="dialog"
		aria-labelledby="resourcesPerReservationDialogLabel" aria-hidden="true">
		<form id="resourcesPerReservationForm" method="post">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="resourcesPerReservationDialogLabel">
							{translate key=ScheduleResourcesPerReservation}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="resourcesPerReservationUnlimited"
								{formname key=MAXIMUM_RESOURCES_PER_RESERVATION_UNLIMITED} />
							<label class="form-check-label"
								for="resourcesPerReservationUnlimited">{translate key=Unlimited}</label>
						</div>
						<div class="form-group">
							<label class="fw-bold"
								for="resourcesPerReservationResources">{translate key=Resources}</label>
							<input type="number" class="form-control required" min="0"
								id="resourcesPerReservationResources"
								{formname key=MAXIMUM_RESOURCES_PER_RESERVATION} />
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{update_button submit=true}
						{indicator}
					</div>
				</div>
			</div>
		</form>
	</div>

	{control type="DatePickerSetupControl" ControlId="availabilityStartDate" AltId="formattedBeginDate" DefaultDate=$StartDate}
	{control type="DatePickerSetupControl" ControlId="availabilityEndDate" AltId="formattedEndDate" DefaultDate=$EndDate}

	{csrf_token}
	{include file="javascript-includes.tpl" InlineEdit=true Fullcalendar=true Timepicker=true DataTable=true}
	{datatablefilter tableId=$tableIdFilter}
	{jsfile src="ajax-helpers.js"}
	{jsfile src="admin/schedule.js"}
	{jsfile src="js/jquery.form-3.09.min.js"}

	<script type="text/javascript">
		function setUpEditables() {
			$.fn.editable.defaults.mode = 'popup';
			$.fn.editable.defaults.toggle = 'manual';
			$.fn.editable.defaults.emptyclass = '';
			$.fn.editable.defaults.params = function(params) {
				params.CSRF_TOKEN = $('#csrf_token').val();
				return params;
			};

			var updateUrl = '{$smarty.server.SCRIPT_NAME}?action=';

			$('.scheduleName').editable({
					url: updateUrl + '{ManageSchedules::ActionRename}', validate: function (value) {
					if ($.trim(value) == '') {
						return '{translate key=RequiredValue|escape:'javascript'}';
					}
				}
			});

		$('.daysVisible').editable({
			url: updateUrl + '{ManageSchedules::ActionChangeDaysVisible}'
		});

		$('.dayName').editable({
			url: updateUrl + '{ManageSchedules::ActionChangeStartDay}', source: [{
			value: '{Schedule::Today}', text: '{$Today|escape:'javascript'}'
		},
		{foreach from=$DayNames item="dayName" key="dayIndex"}
			{
				value:{$dayIndex}, text: '{$dayName|escape:'javascript'}'
			},
		{/foreach}
		]
		});

		$('.defaultScheduleStyle').editable({
			url: updateUrl + '{ManageSchedules::ActionChangeDefaultStyle}', source: [
			{foreach from=$StyleNames item="styleName" key="styleIndex"}
				{
					value: '{$styleIndex}', text: '{$styleName|escape:'javascript'}'
				},
			{/foreach}
		]
		});

		$('.scheduleAdmin').editable({
			url: updateUrl + '{ManageSchedules::ChangeAdminGroup}', emptytext: '{{translate key=None}|escape:'javascript'}', source: [{
			value: '0', text: '{{translate key=None}|escape:'javascript'}'
		},
		{foreach from=$AdminGroups item=group}
			{
				value:{$group->Id()}, text: '{$group->Name()|escape:'javascript'}'
			},
		{/foreach}
		]
		});
		}

		$(document).ready(function() {
					setUpEditables();

					var opts = {
						submitUrl: '{$smarty.server.SCRIPT_NAME}',
						saveRedirect: '{$smarty.server.SCRIPT_NAME}',
						changeLayoutAction: '{ManageSchedules::ActionChangeLayout}',
						addAction: '{ManageSchedules::ActionAdd}',
						peakTimesAction: '{ManageSchedules::ActionChangePeakTimes}',
						makeDefaultAction: '{ManageSchedules::ActionMakeDefault}',
						deleteAction: '{ManageSchedules::ActionDelete}',
						availabilityAction: '{ManageSchedules::ActionChangeAvailability}',
						enableSubscriptionAction: '{ManageSchedules::ActionEnableSubscription}',
						disableSubscriptionAction: '{ManageSchedules::ActionDisableSubscription}',
						switchLayout: '{ManageSchedules::ActionSwitchLayoutType}',
						addLayoutSlot: '{ManageSchedules::ActionAddLayoutSlot}',
						updateLayoutSlot: '{ManageSchedules::ActionUpdateLayoutSlot}',
						deleteLayoutSlot: '{ManageSchedules::ActionDeleteLayoutSlot}',
						maximumConcurrentAction: '{ManageSchedules::ActionChangeMaximumConcurrent}',
						maximumResourcesAction: '{ManageSchedules::ActionChangeResourcesPerReservation}',
						calendarOptions: {
							buttonText: {
								today: '{{translate key=Today}|escape:'javascript'}',
								month: '{{translate key=Month}|escape:'javascript'}',
								week: '{{translate key=Week}|escape:'javascript'}',
								day: '{{translate key=Day}|escape:'javascript'}'
								}, defaultDate: moment('{Date::Now()->ToTimezone({$Timezone})->Format('Y-m-d')}', 'YYYY-MM-DD'), eventsUrl: '{$smarty.server.SCRIPT_NAME}'
							}
						};

						var scheduleManagement = new ScheduleManagement(opts);
						scheduleManagement.init();

						$('.timepicker').timepicker({
							timeFormat: '{$TimeFormat}'
						});


					});
	</script>

</div>
{include file='globalfooter.tpl'}