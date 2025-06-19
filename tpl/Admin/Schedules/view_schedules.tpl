{include file='globalheader.tpl' DataTable=true}

<div id="page-manage-schedules" class="admin-page">

	<div class="border-bottom mb-3 clearfix">
		<h1 class="float-start">{translate key=ManageSchedules}</h1>
	</div>

	<div class="card shadow" id="list-schedules-panel">
		<div class="card-body" id="scheduleList">
			<div class="accordion" id="scheduleAccordion">
				{assign var=tableIdFilter value=schedulesViewTable}
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
									{capture name=daysVisible}
										<span class='fw-bold'>{$schedule->GetDaysVisible()}</span>
									{/capture}
									{assign var=dayOfWeek value=$schedule->GetWeekdayStart()}
									{capture name=dayName}
										<span
											class='fw-bold'>{if $dayOfWeek == Schedule::Today}{$Today}{else}{$DayNames[$dayOfWeek]}{/if}</span>
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
												<div class="scheduleDetails row">
													<div class="col-12 col-sm-6">
														<div class="fw-bold">{$schedule->GetName()}</div>

														<div>
															{translate key="LayoutDescription" args="{$smarty.capture.dayName},
														{$smarty.capture.daysVisible}"}</div>

														<div>{translate key='ScheduleAdministrator'}
															<span
																class="fw-bold">{($GroupLookup[$schedule->GetAdminGroupId()]) ? $GroupLookup[$schedule->GetAdminGroupId()]->Name : 'None'}
															</span>
														</div>

														<div class="">
															{include file="Admin/Schedules/manage_availability.tpl" schedule=$schedule timezone=$Timezone}
														</div>

														<div class="maximumConcurrentContainer">
															{if $schedule->EnforceConcurrentReservationMaximum()}
																{translate key=ScheduleConcurrentMaximum args=$schedule->GetTotalConcurrentReservations()}
															{else}
																{translate key=ScheduleConcurrentMaximumNone}
															{/if}
														</div>

														<div class="resourcesPerReservationContainer">
															{if $schedule->EnforceMaxResourcesPerReservation()}
																{translate key=ScheduleResourcesPerReservationMaximum args=$schedule->GetMaxResourcesPerReservation()}
															{else}
																{translate key=ScheduleResourcesPerReservationNone}
															{/if}
														</div>

														<div>
															{translate key=DefaultStyle}
															<span
																class="fw-bold">{$StyleNames[$schedule->GetDefaultStyle()]}</span>
														</div>

														{if $CreditsEnabled}
															<span>{translate key=PeakTimes}</span>
															<div class="fw-bold">
																{include file="Admin/Schedules/manage_peak_times.tpl" Layout=$Layouts[$id] Months=$Months DayNames=$DayNames}
															</div>
														{/if}

														<div>{translate key=Resources}
															<span class="fw-bold">
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
																<span class="fw-bold">{$schedule->GetPublicId()}</span>
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
														</div>

														{if $Layouts[$id]->UsesDailyLayouts()}
															{translate key=LayoutVariesByDay} -
															<div class="allDailyLayouts">
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
																{translate key=ThisScheduleUsesAStandardLayout}
															</div>
														{elseif $Layouts[$id]->UsesCustomLayout()}
															<div class="mt-4 fw-bold">
																{translate key=ThisScheduleUsesACustomLayout}
															</div>
														{else}
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
																{translate key=ThisScheduleUsesAStandardLayout}
															</div>
														{/if}
													</div>
													<div class="actions col-12">
														{if $schedule->GetIsDefault()}
															<span
																class="fst-italic fw-bold">{translate key=ThisIsTheDefaultSchedule}</span>
														{/if}
														{indicator id="action-indicator"}
														<div class="clear"></div>
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


	{include file="javascript-includes.tpl" DataTable=true}
	{datatablefilter tableId=$tableIdFilter}
</div>

{include file='globalfooter.tpl'}