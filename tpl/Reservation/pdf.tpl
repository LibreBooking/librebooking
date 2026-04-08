<script type="text/javascript">
	window.ReservationPdfConfig = {
			appTitle: "{$AppTitle|escape:'javascript'}",
			reservationDetailsTitle: "{translate key=ReservationDetails|capitalize|escape:'javascript'}",
			referenceNumberLabel: "{translate key=ReferenceNumber|escape:'javascript'}",
			referenceNumber: "{$ReferenceNumber|escape:'javascript'}",
			userLabel: "{translate key='User'|escape:'javascript'}",
			reservationUserName: "{$ReservationUserName|unescape:'html'|escape:'javascript'}",
			showUserDetailsAndReservationDetails: {if $ShowUserDetails && $ShowReservationDetails}true{else}false{/if},

			beginDateLabel: "{translate key='BeginDate'|escape:'javascript'}",
			beginDateValue: "{formatdate date=$StartDate key=dashboard|escape:'javascript'}",
			endDateLabel: "{translate key='EndDate'|escape:'javascript'}",
			endDateValue: "{formatdate date=$EndDate key=dashboard|escape:'javascript'}",
			reservationLengthLabel: "{translate key=ReservationLength|escape:'javascript'}",
			repeatPromptLabel: "{translate key=RepeatPrompt|escape:'javascript'}",
			isRecurring: {if $IsRecurring}true{else}false{/if},
			isCustomRepeat: {if $RepeatType eq 'custom'}true{else}false{/if},
			repeatTypeLabel: "{translate key=$RepeatOptions[$RepeatType]['key']|escape:'javascript'}",
			repeatInterval: "{$RepeatInterval|escape:'javascript'}",
			repeatEveryLabel: "{if $RepeatType neq 'custom'}{translate key=$RepeatOptions[$RepeatType]['everyKey']|escape:'javascript'}{/if}",
			repeatOnLabel: "{translate key=RepeatOn|escape:'javascript'}",
			typeLabel: "{translate key=Type|escape:'javascript'}",
			repeatMonthlyTypeLabel: "{if $RepeatMonthlyType neq ''}{if $RepeatMonthlyType == 'dayOfMonth'}{translate key=repeatDayOfMonth|escape:'javascript'}{else}{translate key=repeatDayOfWeek|escape:'javascript'}{/if}{/if}",
			daysLabel: "{translate key=RepeatDaysPrompt|capitalize|escape:'javascript'}",
			repeatWeekdays: [{foreach from=$RepeatWeekdays item=day name=repeatDaysLoop}"{$DayNamesFull[$day]|escape:'javascript'}"{if !$smarty.foreach.repeatDaysLoop.last},{/if}{/foreach}],
			repeatCustomDates: [{foreach from=$CustomRepeatDates item=date name=repeatCustomDatesLoop}"{formatdate date=$date key=schedule_daily timezone=$Timezone|escape:'javascript'}"{if !$smarty.foreach.repeatCustomDatesLoop.last},{/if}{/foreach}],
			repeatUntilPromptLabel: "{translate key=RepeatUntilPrompt|escape:'javascript'}",
			repeatUntilDate: "{formatdate date=$RepeatTerminationDate key=dashboard|escape:'javascript'}",

			additionalAttributesLabel: "{translate key='AdditionalAttributes'|escape:'javascript'}",
			customAttributeTypeCheckbox: {$CustomAttributeTypeCheckbox|default:0|escape:'javascript'},

			resourcesHeaderLabel: "{translate key='Resources'|escape:'javascript'}",
			requiresApprovalLabel: "{translate key='RequiresApproval'|escape:'javascript'}",
			requiresCheckInNotificationLabel: "{translate key='RequiresCheckInNotification'|escape:'javascript'}",
			releasedInLabel: "{translate key='ReleasedIn'|escape:'javascript'} ({translate key='minutes'|escape:'javascript'})",
			resources: [{
						name: "{$Resource->Name|escape:'javascript'}",
						requiresApproval: {if $Resource->GetRequiresApproval()}true{else}false{/if},
						requiresCheckIn: {if $Resource->IsCheckInEnabled()}true{else}false{/if},
						releasedIn: "{if $Resource->IsAutoReleased()}{$Resource->GetAutoReleaseMinutes()|escape:'javascript'}{else}{/if}"
						}{foreach from=$AvailableResources item=resource}
							{if is_array($AdditionalResourceIds) && in_array($resource->Id, $AdditionalResourceIds)},
								{
									name: "{$resource->Name|escape:'javascript'}",
									requiresApproval: {if $resource->GetRequiresApproval()}true{else}false{/if},
									requiresCheckIn: {if $resource->IsCheckInEnabled()}true{else}false{/if},
									releasedIn: "{if $resource->IsAutoReleased()}{$resource->GetAutoReleaseMinutes()|escape:'javascript'}{else} - {/if}"
								}{/if}
							{/foreach}
						],

						showAccessories: {if $ShowReservationDetails && is_array($Accessories) && $Accessories|default:array()|count > 0}true{else}false{/if},
						accessoriesHeaderLabel: "{translate key='Accessories'|escape:'javascript'}",
						quantityLabel: "{translate key='Quantity'|escape:'javascript'}",
						accessories: [
								{foreach from=$Accessories item=accessory name=accessoriesLoop}
									{
										name: "{$accessory->Name|escape:'javascript'}",
										quantity: "{$accessory->QuantityReserved|escape:'javascript'}"
										}{if !$smarty.foreach.accessoriesLoop.last},{/if}
									{/foreach}
								],

								showParticipants: {if $ShowReservationDetails && $ShowParticipation && $Participants|default:array()|count > 0}true{else}false{/if},
								participantsHeaderLabel: "{translate key='Participants'|escape:'javascript'}",
								emailLabel: "{translate key='Email'|escape:'javascript'}",
								participants: [
									{foreach from=$Participants item=user name=participantsLoop}
										{
											fullName: "{$user->FullName|unescape:'html'|escape:'javascript'}",
											email: "{$user->Email|escape:'javascript'}"
											}{if !$smarty.foreach.participantsLoop.last},{/if}
										{/foreach}
									],

									showInvitees: {if $ShowReservationDetails && $ShowParticipation && $Invitees|default:array()|count > 0}true{else}false{/if},
									invitationListHeaderLabel: "{translate key='InvitationList'|escape:'javascript'}",
									invitees: [
										{foreach from=$Invitees item=user name=inviteesLoop}
											{
												fullName: "{$user->FullName|unescape:'html'|escape:'javascript'}",
												email: "{$user->Email|escape:'javascript'}"
												}{if !$smarty.foreach.inviteesLoop.last},{/if}
											{/foreach}
										],

										reservationTitleLabel: "{translate key='ReservationTitle'|escape:'javascript'}",
										reservationTitle: "{if isset($ReservationTitle)}{$ReservationTitle|unescape:'html'|escape:'javascript'}{/if}",
										reservationDescriptionLabel: "{translate key='ReservationDescription'|escape:'javascript'}",
										reservationDescription: "{if isset($Description)}{$Description|unescape:'html'|escape:'javascript'}{/if}",

										remindersEnabled: {if $RemindersEnabled}true{else}false{/if},
										sendReminderLabel: "{translate key='SendReminder'|escape:'javascript'}",
										reminders: [
											{if $ReminderTimeStart neq ''}
												{
													time: "{$ReminderTimeStart|escape:'javascript'}",
													interval: "{translate key=$ReminderIntervalStart|escape:'javascript'}",
													text: "{translate key=ReminderBeforeStart|escape:'javascript'}"
													}{if $ReminderTimeEnd neq ''},{/if}
												{/if}
												{if $ReminderTimeEnd neq ''}
													{
														time: "{$ReminderTimeEnd|escape:'javascript'}",
														interval: "{translate key=$ReminderIntervalEnd|escape:'javascript'}",
														text: "{translate key=ReminderBeforeEnd|escape:'javascript'}"
													}
												{/if}
											],

											attachmentsLabel: "{translate key=Attachments|escape:'javascript'}",
											attachments: [
												{foreach from=$Attachments item=attachment name=attachmentsLoop}
													"{$attachment->FileName()|escape:'javascript'}"{if !$smarty.foreach.attachmentsLoop.last},{/if}
												{/foreach}
											],

											showTermsAcceptance: {if $Terms != null && $TermsAccepted}true{else}false{/if},
											acceptTermsLabel: "{translate key=IAccept|escape:'javascript'} {translate key=TheTermsOfService|escape:'javascript'}",

											logoUrl: "{$ScriptUrl|escape:'javascript'}/img/{$LogoUrl|escape:'javascript'}"
										};
</script>
