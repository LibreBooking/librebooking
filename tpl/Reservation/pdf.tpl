var allAttributes;

function GetSelectedResourceIds() {
	var resourceIds = [parseInt($('#primaryResourceId').val())];
	$('#additionalResources').find('.resourceId').each(function (i, element) {
		resourceIds.push(parseInt($(element).val()));
	});
	return resourceIds;
}
	
function LoadCustomAttributesData() {
	var url = 'ajax/reservation_attributes_print.php?uid=' + $('#userId').val() + '&rn=' + $('#referenceNumber').val() + '&ro=' + $('#reservation-box').hasClass('readonly');

	var resourceIds = GetSelectedResourceIds();
	_.each(resourceIds, function (n) {
		url += '&rid[]=' + n;
	});
	
	return $.ajax({
        global: false,
        url: url,
        dataType: 'json',
	    success: function (data) {
            allAttributes = data;
        }
    });
}

LoadCustomAttributesData();

$('.btnPDF').click(function (e) {
	e.preventDefault();

	LoadCustomAttributesData().done(function() {
		window.jsPDF = window.jspdf.jsPDF;

		var pdfDocument = new jsPDF();

		var logo = new Image();
		logo.src = '{$ScriptUrl}/img/{$LogoUrl}';

		pdfDocument.autoTable({
		  columnStyles: { title: { halign: 'center'  , valign: 'center', fontSize: 14}},
		  theme: 'plain',
		  body: [
			{ logo: '', title: '- {$AppTitle|escape:'javascript'} - '},
			{ blank: ''},
		  ],
		  didDrawCell: data => {
			if (data.section == 'body' && data.column.index == 0 && data.row.index == 0){
				pdfDocument.addImage(logo, data.cell.x, data.cell.y + 1, 0, 17);
			}
		  }
		});

		pdfDocument.autoTable({
		  styles: { halign: 'center'  , valign: 'center', fontSize: 12},
		  theme: 'plain',
		  body: [
			['{translate key=ReservationDetails|capitalize}'],
		  ],
		});

		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  columnStyles: { reftitle: { fontStyle: 'bold'}},
		  theme: 'plain',
			body: [
			{ reftitle: '{translate key=ReferenceNumber}', refnumb: '{$ReferenceNumber}'},
		  ],
		});

		{if $ShowUserDetails && $ShowReservationDetails}
			pdfDocument.autoTable({
			  styles: { lineWidth: 0.02},
			  theme: 'plain',
			  body: [
				{ user: '{$ReservationUserName|unescape:'html'|escape:'javascript'}'},
			  ],
			  columns: [
				{ header: '{translate key='User'}', dataKey: 'user' },
			  ],
			});
		{/if}

		var durationText = document.getElementsByClassName('durationText').item(0).innerText;
		var daysText = "{translate key=days}";
		daysText = daysText.charAt(0).toUpperCase() + daysText.slice(1);

		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  theme: 'plain',
			body: [
			[{ content: '{translate key='BeginDate'}', styles: { fontStyle: 'bold'}},
			 { content: '{formatdate date=$StartDate key=embedded_datetime}'},
			 { content: '{translate key='EndDate'}', styles: { fontStyle: 'bold'}},
			 { content: '{formatdate date=$EndDate key=embedded_datetime}'},
			],
			[{ content: '{translate key=ReservationLength}', styles: { fontStyle: 'bold'}},
			 { colSpan: 3, content: durationText},	 
			],
			[{ content: '{translate key=RepeatPrompt}', styles: { fontStyle: 'bold'}},
			{if $IsRecurring}
			 { content: '{translate key=$RepeatOptions[$RepeatType]['key']}'},
			 { content: '{$RepeatInterval}'},	
			 { content: '{translate key=$RepeatOptions[$RepeatType]['everyKey']}'},
			{else}
			 { colSpan: 3, content: '{translate key=$RepeatOptions[$RepeatType]['key']}'},
			{/if}
			],
			{if $IsRecurring}
				{if $RepeatMonthlyType neq ''}
					[{ content: '{translate key=Type}', styles: { fontStyle: 'bold'}},
					{if $RepeatMonthlyType == 'dayOfMonth'}
						{ colSpan: 3, content: '{translate key=repeatDayOfMonth}'},
					{else}
						{ colSpan: 3, content: '{translate key=repeatDayOfWeek}'},
					{/if}
					],
				{/if}
				{if (is_array($RepeatWeekdays) && count($RepeatWeekdays) gt 0)}
					[{ content: daysText, styles: { fontStyle: 'bold'}},
					{ colSpan: 3, content: '{foreach from=$RepeatWeekdays item=day name=weekdaysLoop}{if $smarty.foreach.weekdaysLoop.last}{translate key=$DayNames[$day]}{else}{translate key=$DayNames[$day]},{/if} {/foreach}'},
					],
				{/if}	
				[{ content: '{{translate key=RepeatUntilPrompt}|escape:'javascript'}', styles: { fontStyle: 'bold'}},
				 { colSpan: 3, content: '{formatdate date=$RepeatTerminationDate}'},	 
				],
			{/if}
			],
		});

		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  columnStyles: { 1: { cellWidth: 16}, 2: { cellWidth: 18},3: { cellWidth: 20}},
		  theme: 'plain',
			body: [
			[{ content: '{translate key="Resources"}', styles: { fontStyle: 'bold'}},
			 { content: '{translate key="RequiresApproval"}', styles: { fontStyle: 'bold', fontSize: 7, halign: 'center'}},
			 { content: '{translate key="RequiresCheckInNotification"}', styles: { fontStyle: 'bold', fontSize: 7, halign: 'center'}},
			 { content: '{translate key="ReleasedIn"} ({translate key="minutes"})', styles: { fontStyle: 'bold', fontSize: 7, halign: 'center'}},
			],
			[{ content: '{$Resource->Name|escape:'javascript'}'},
			 { content: '{if $Resource->GetRequiresApproval()}X{/if}', styles: { halign: 'center'}},
			 { content: '{if $Resource->IsCheckInEnabled()}X{/if}', styles: { halign: 'center'}},
			 { content: '{if $Resource->IsAutoReleased()}{$Resource->GetAutoReleaseMinutes()}{/if}', styles: { halign: 'center'}},
			],
			{foreach from=$AvailableResources item=resource}
				{if is_array($AdditionalResourceIds) && in_array($resource->Id, $AdditionalResourceIds)}
					[{ content: '{$resource->Name|escape:'javascript'}'},
					 { content: '{if $resource->GetRequiresApproval()}X{/if}', styles: { fontStyle: 'bold', halign: 'center'}},
					 { content: '{if $resource->IsCheckInEnabled()}X{/if}', styles: { fontStyle: 'bold', halign: 'center'}},
					 { content: '{if $resource->IsAutoReleased()}{$resource->GetAutoReleaseMinutes()}{else} - {/if}', styles: { halign: 'center'}},
					],
				{/if}
			{/foreach}
			],
		});

		{if $ShowReservationDetails && is_array($Accessories) && $Accessories|default:array()|count > 0}
		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  columnStyles: { 1: { cellWidth: 18}},
		  theme: 'plain',
			body: [
			[{ content: '{translate key="Accessories"}', styles: { fontStyle: 'bold'}},
			 { content: '{translate key="Quantity"}', styles: { fontStyle: 'bold', fontSize: 7, halign: 'center'}},
			],
			{foreach from=$Accessories item=accessory}
				[{ content: '{$accessory->Name|escape:'javascript'}'},
				 { content: '{$accessory->QuantityReserved}', styles: { halign: 'center'}},
				],
			{/foreach}
			]
		});
		{/if}

		{if $ShowReservationDetails && $ShowParticipation && $Participants|default:array()|count > 0}
		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  columnStyles: { 1: { cellWidth: 80}},
		  theme: 'plain',
			body: [
			[{ content: '{translate key="Participants"}', styles: { fontStyle: 'bold'}},
			 { content: '{translate key="Email"}', styles: { fontStyle: 'bold', fontSize: 7}},
			],
			{foreach from=$Participants item=user}
				[{ content: '{$user->FullName|unescape:'html'|escape:'javascript'}'},
				 { content: '{$user->Email}'},
				],
			{/foreach}
			]
		});
		{/if}

		{if $ShowReservationDetails && $ShowParticipation && $Invitees|default:array()|count > 0}
		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  columnStyles: { 1: { cellWidth: 80}},
		  theme: 'plain',
			body: [
			[{ content: '{translate key="InvitationList"}', styles: { fontStyle: 'bold'}},
			 { content: '{translate key="Email"}', styles: { fontStyle: 'bold', fontSize: 7}},
			],
			{foreach from=$Invitees item=user}
				[{ content: '{$user->FullName|unescape:'html'|escape:'javascript'}'},
				 { content: '{$user->Email}'},
				],
			{/foreach}
			]
		});
		{/if}

		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  columnStyles: { 1: { cellWidth: 80}},
		  theme: 'plain',
			body: [
			[{ content: '{translate key="ReservationTitle"}', styles: { fontStyle: 'bold'}},
			],
			[{ content: '{$ReservationTitle|unescape:'html'|escape:'javascript'}'},
			],
			[{ content: '{translate key="ReservationDescription"}', styles: { fontStyle: 'bold'}},
			],
			[{ content: '{$Description|unescape:'html'|escape:'javascript'}'},
			],
			]
		});

		var bodyAttributes = [[{ content: '{translate key="AdditionalAttributes"}', styles: { fontStyle: 'bold'}}, { content: ''}]];

		if (allAttributes && Object.keys(allAttributes).length != 0) {
			for (var obj in allAttributes) {
				if (allAttributes[obj][0] == "4") {
					if (allAttributes[obj][2] == "1") {
						bodyAttributes.push([{ content: allAttributes[obj][1], styles: { fontStyle: 'bold'}}, { content: 'X', styles: { fontStyle: 'bold', halign: 'center'}}]);
					}
				} else {
					bodyAttributes.push([{ colSpan: 2, content: allAttributes[obj][1], styles: { fontStyle: 'bold'}}]);
					bodyAttributes.push([{ colSpan: 2, content: allAttributes[obj][2]}]);
				}
			}
			pdfDocument.autoTable({
				styles: { lineWidth: 0.02},
				columnStyles: { 1: { cellWidth: 10}},
				theme: 'plain',
				body: bodyAttributes,
			});
		}

		{if $RemindersEnabled}
		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  theme: 'plain',
			body: [
			[{ content: '{translate key="SendReminder"}', styles: { fontStyle: 'bold'}},
			{if $ReminderTimeStart neq ''}
			{assign var="ReminderBeforeStart" value="{translate key=ReminderBeforeStart}"}
			{ content: '{$ReminderTimeStart} {translate key=$ReminderIntervalStart} {$ReminderBeforeStart|escape:'javascript'}'},
			{/if}
			{if $ReminderTimeEnd neq ''}
			{assign var="ReminderBeforeEnd" value="{translate key=ReminderBeforeEnd}"}
			{ content: '{$ReminderTimeEnd} {translate key=$ReminderIntervalEnd} {$ReminderBeforeEnd|escape:'javascript'}'},
			{/if}
			],
			]
		});
		{/if}

		{if $Attachments|default:array()|count > 0}
		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  theme: 'plain',
			body: [
			[{ content: '{{translate key=Attachments}|escape:'javascript'} ({$Attachments|default:array()|count})', styles: { fontStyle: 'bold'}},
			],
			{foreach from=$Attachments item=attachment}
			[{ content: '{$attachment->FileName()|escape:'javascript'}'},
			],
			{/foreach}
			]
		});
		{/if}

		{if $Terms != null & $TermsAccepted}
		pdfDocument.autoTable({
		  styles: { lineWidth: 0.02},
		  columnStyles: { 1: { cellWidth: 10}},
		  theme: 'plain',
			body: [
			[{ content: "{translate key=IAccept|escape:'javascript'} {translate key=TheTermsOfService}", styles: { fontStyle: 'bold'}},
			 { content: 'X', styles: { fontStyle: 'bold', halign: 'center'}},
			],
			]
		});
		{/if}
		window.open(URL.createObjectURL(pdfDocument.output("blob")))
	});
});
