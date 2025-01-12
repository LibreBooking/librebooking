function ScheduleManagement(opts) {
	var options = opts;

	var elements = {
		activeId: $('#activeId'),

		layoutDialog: $('#changeLayoutDialog'),
		deleteDialog: $('#deleteDialog'),
		addDialog: $('#addDialog'),

		changeLayoutForm: $('#changeLayoutForm'),
		placeholderForm: $('#placeholderForm'),
		deleteForm: $('#deleteForm'),

		addForm: $('#addScheduleForm'),
		addName: $('#addName'),

		reservableEdit: $('#reservableEdit'),
		blockedEdit: $('#blockedEdit'),
		layoutTimezone: $('#layoutTimezone'),
		quickLayoutConfig: $('#quickLayoutConfig'),
		quickLayoutStart: $('#quickLayoutStart'),
		quickLayoutEnd: $('#quickLayoutEnd'),
		createQuickLayout: $('#createQuickLayout'),

		daysVisible: $('#daysVisible'),
		dayOfWeek: $('#dayOfWeek'),
		deleteDestinationScheduleId: $('#targetScheduleId'),
		usesSingleLayout: $('#usesSingleLayout'),

		addScheduleButton: $('#add-schedule'),

		peakTimesDialog: $('#peakTimesDialog'),
		peakTimesForm: $('#peakTimesForm'),
		peakEveryDay: $('#peakEveryDay'),
		peakDayList: $('#peakDayList'),
		peakAllYear: $('#peakAllYear'),
		peakDateRange: $('#peakDateRange'),
		peakAllDay: $('#peakAllDay'),
		peakTimes: $('#peakTimes'),
		deletePeakTimesButton: $('#deletePeakBtn'),
		deletePeakTimes: $('#deletePeakTimes'),

		availabilityDialog: $('#availabilityDialog'),
		availableStartDateTextbox: $('#availabilityStartDate'),
		availableStartDate: $('#formattedBeginDate'),
		availableEndDateTextbox: $('#availabilityEndDate'),
		availableEndDate: $('#formattedEndDate'),
		availableAllYear: $('#availableAllYear'),
		availabilityForm: $('#availabilityForm'),

		concurrentForm: $('#concurrentForm'),

		switchLayoutButton: $('.switchLayout'),
		switchLayoutForm: $('#switchLayoutForm'),
		switchLayoutDialog: $('#switchLayoutDialog'),

		concurrentMaximumForm: $('#concurrentMaximumForm'),
		concurrentMaximumDialog: $('#concurrentMaximumDialog'),
		maximumConcurrentUnlimited: $('#maximumConcurrentUnlimited'),
		maximumConcurrent: $('#maximumConcurrent'),

		resourcesPerReservationForm: $('#resourcesPerReservationForm'),
		resourcesPerReservationDialog: $('#resourcesPerReservationDialog'),
		resourcesPerReservationUnlimited: $('#resourcesPerReservationUnlimited'),
		resourcesPerReservationResources: $('#resourcesPerReservationResources'),

		layoutSlotForm: $('#layoutSlotForm'),
		slotStartDate: $('#slotStartDate'),
		slotEndDate: $('#slotEndDate'),
		slotId: $('#slotId'),
		deleteCustomLayoutDialog: $('#deleteCustomLayoutDialog'),
		deleteSlotStartDate: $('#deleteSlotStartDate'),
		deleteSlotEndDate: $('#deleteSlotEndDate'),
		cancelDeleteSlot: $('#cancelDeleteSlot'),
		deleteCustomTimeSlotForm: $('#deleteCustomTimeSlotForm'),
		deleteSlot: $('#deleteSlot'),
		confirmCreateSlotDialog: $('#confirmCreateSlotDialog'),
		cancelCreateSlot: $('#cancelCreateSlot'),
	};

	ScheduleManagement.prototype.init = function () {
		var schedulesTable = $('#schedulesTable_wrapper');

		schedulesTable.on('click', '.update', function (e) {
			e.preventDefault();
			var id = $(this).closest('.scheduleDetails').attr('data-schedule-id');
			setActiveScheduleId(id);
		});

		schedulesTable.on('click', '.renameButton', function (e) {
			e.stopPropagation();
			$(this).closest('.scheduleDetails').find('.scheduleName').editable('toggle');
		});

		schedulesTable.on('click', '.dayName', function (e) {
			e.stopPropagation();
			$(this).editable('toggle');
		});

		schedulesTable.on('click', '.daysVisible', function (e) {
			e.stopPropagation();
			$(this).editable('toggle');
		});

		schedulesTable.on('click', '.changeScheduleAdmin', function (e) {
			e.stopPropagation();
			$(this).closest('.scheduleDetails').find('.scheduleAdmin').editable('toggle');
		});

		schedulesTable.on('click', '.changeLayoutButton', function (e) {
			var id = getActiveScheduleId();
			var reservable = $(this).closest('.scheduleDetails').find('.reservableSlots');
			var blocked = $(this).closest('.scheduleDetails').find('.blockedSlots');
			var timezone = $(this).closest('.scheduleDetails').find('.timezone');
			var usesDailyLayouts = $(this).closest('.scheduleDetails').find('.usesDailyLayouts');

			if ($(e.target).data('layout-type') == 0) {
				showChangeLayout(e, reservable, blocked, timezone, (usesDailyLayouts.val() == 'false'));
			} else {
				showChangeCustomLayout(id);
			}
			return false;
		});

		schedulesTable.on('click', '.makeDefaultButton, .enableSubscription, .disableSubscription', function (e) {
			var action;
			if ($(this).hasClass('makeDefaultButton')) {
				action = options.makeDefaultAction;
			} else if ($(this).hasClass('enableSubscription')) {
				action = options.enableSubscriptionAction;
			} else if ($(this).hasClass('disableSubscription')) {
				action = options.disableSubscriptionAction;
			}

			if (action) {
				PerformAsyncAction($(this), getSubmitCallback(action), $('#action-indicator'));
			}
		});

		schedulesTable.on('click', '.deleteScheduleButton', function (e) {
			showDeleteDialog(e);
			return false;
		});

		schedulesTable.on('click', '.showAllDailyLayouts', function (e) {
			e.preventDefault();
			$(this).next('.allDailyLayouts').toggle();
		});

		schedulesTable.on('click', '.changePeakTimes', function (e) {
			e.preventDefault();
			showPeakTimesDialog(getActiveScheduleId());
		});

		schedulesTable.on('click', '.changeAvailability', function (e) {
			e.preventDefault();
			showAvailabilityDialog(getActiveScheduleId());
		});

		schedulesTable.on('click', '.toggleConcurrent', function (e) {
			e.preventDefault();
			var toggle = $(e.target);
			var container = toggle.parent('.concurrentContainer');
			toggleConcurrentReservations(getActiveScheduleId(), toggle, container);
		});

		schedulesTable.on('click', '.defaultScheduleStyle', function (e) {
			e.stopPropagation();
			$(this).editable('toggle');
		});

		schedulesTable.on('click', '.switchLayout', function (e) {
			e.preventDefault();
			$('#switchLayoutTypeId').val($(e.target).data('switch-to'));
			elements.switchLayoutDialog.modal('show');
		});

		schedulesTable.on('click', '.changeScheduleConcurrentMaximum', function (e) {
			e.preventDefault();
			var concurrent = $(e.target).closest('.maximumConcurrentContainer').data('concurrent');
			elements.maximumConcurrentUnlimited.attr('checked', concurrent == "0");
			elements.maximumConcurrent.val(concurrent);
			elements.maximumConcurrent.attr('disabled', concurrent == "0");
			elements.concurrentMaximumDialog.modal('show');
		});

		schedulesTable.on('click', '.changeResourcesPerReservation', function (e) {
			e.preventDefault();
			var maximum = $(e.target).closest('.resourcesPerReservationContainer').data('maximum');
			elements.resourcesPerReservationUnlimited.attr('checked', maximum == "0");
			elements.resourcesPerReservationResources.val(maximum);
			elements.resourcesPerReservationResources.attr('disabled', maximum == "0");
			elements.resourcesPerReservationDialog.modal('show');
		});

		elements.deletePeakTimesButton.click(function (e) {
			e.preventDefault();
			elements.deletePeakTimes.val('1');
		});

		elements.availableAllYear.on('click', function (e) {
			if ($(e.target).is(':checked')) {
				elements.availableStartDateTextbox.prop('disabled', true);
				elements.availableEndDateTextbox.prop('disabled', true);
			}
			else {
				elements.availableStartDateTextbox.prop('disabled', false);
				elements.availableEndDateTextbox.prop('disabled', false);
			}
		});

		$(".save").click(function (e) {
			e.preventDefault();
			e.stopPropagation();
			$(this).closest('form').submit();
		});

		$(".cancel").click(function () {
			$(this).closest('.dialog').dialog("close");
		});

		elements.quickLayoutConfig.change(function () {
			createQuickLayout();
		});

		elements.quickLayoutStart.change(function () {
			createQuickLayout();
		});

		elements.quickLayoutEnd.change(function () {
			createQuickLayout();
		});

		elements.createQuickLayout.click(function (e) {
			e.preventDefault();
			createQuickLayout();
		});

		elements.usesSingleLayout.change(function () {
			toggleLayoutChange($(this).is(':checked'));
		});

		elements.addScheduleButton.click(function (e) {
			e.preventDefault();
			elements.addDialog.modal('show');
		});

		elements.addDialog.on('shown.bs.modal', function () {
			elements.addName.focus();
		});

		elements.cancelDeleteSlot.click(function (e) {
			elements.deleteCustomLayoutDialog.hide();
		});

		elements.cancelCreateSlot.click(function (e) {
			elements.confirmCreateSlotDialog.hide();
		});

		elements.maximumConcurrentUnlimited.on('click', function (e) {
			if (elements.maximumConcurrentUnlimited.is(":checked")) {
				elements.maximumConcurrent.attr('disabled', true);
			}
			else {
				elements.maximumConcurrent.attr('disabled', false);
			}
		});

		elements.resourcesPerReservationUnlimited.on('click', function (e) {
			if (elements.resourcesPerReservationUnlimited.is(":checked")) {
				elements.resourcesPerReservationResources.attr('disabled', true);
			}
			else {
				elements.resourcesPerReservationResources.attr('disabled', false);
			}
		});

		$('.autofillBlocked').click(function (e) {
			e.preventDefault();
			autoFillBlocked();
		});

		wireUpPeakTimeToggles();

		ConfigureAsyncForm(elements.changeLayoutForm, getSubmitCallback(options.changeLayoutAction));
		ConfigureAsyncForm(elements.addForm, getSubmitCallback(options.addAction), null, handleAddError);
		ConfigureAsyncForm(elements.deleteForm, getSubmitCallback(options.deleteAction));
		ConfigureAsyncForm(elements.peakTimesForm, getSubmitCallback(options.peakTimesAction), refreshPeakTimes);
		ConfigureAsyncForm(elements.availabilityForm, getSubmitCallback(options.availabilityAction), refreshAvailability);
		ConfigureAsyncForm(elements.switchLayoutForm, getSubmitCallback(options.switchLayout));
		ConfigureAsyncForm(elements.deleteCustomTimeSlotForm, getSubmitCallback(options.deleteLayoutSlot), afterDeleteSlot);
		ConfigureAsyncForm(elements.concurrentMaximumForm, getSubmitCallback(options.maximumConcurrentAction));
		ConfigureAsyncForm(elements.resourcesPerReservationForm, getSubmitCallback(options.maximumResourcesAction));
	};

	var getSubmitCallback = function (action) {
		return function () {
			return options.submitUrl + "?sid=" + elements.activeId.val() + "&action=" + action;
		};
	};

	var createQuickLayout = function () {
		var intervalMinutes = elements.quickLayoutConfig.val();
		var startTime = elements.quickLayoutStart.val();
		var endTime = elements.quickLayoutEnd.val();

		if (intervalMinutes != '' && startTime != '' && endTime != '') {
			var layout = '';
			var blocked = '';

			if (startTime != '00:00') {
				blocked += '00:00 - ' + startTime + "\n";
			}

			if (endTime != '00:00') {
				blocked += endTime + ' - 00:00';
			}

			var startTimes = startTime.split(":");
			var endTimes = endTime.split(":");

			var currentTime = new Date();
			currentTime.setHours(startTimes[0]);
			currentTime.setMinutes(startTimes[1]);

			var endDateTime = new Date();
			endDateTime.setHours(endTimes[0]);
			endDateTime.setMinutes(endTimes[1]);

			var nextTime = new Date(currentTime);

			var intervalMilliseconds = 60 * 1000 * intervalMinutes;
			while (currentTime.getTime() < endDateTime.getTime()) {
				nextTime.setTime(nextTime.getTime() + intervalMilliseconds);

				layout += getFormattedTime(currentTime) + ' - ';
				layout += getFormattedTime(nextTime) + '\n';

				currentTime.setTime(currentTime.getTime() + intervalMilliseconds);
			}

			$('.reservableEdit:visible', elements.layoutDialog).val(layout);
			$('.blockedEdit:visible', elements.layoutDialog).val(blocked);
		}
	};

	var getFormattedTime = function (date) {
		var hour = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
		var minute = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
		return hour + ":" + minute;
	};

	var autoFillBlocked = function () {

		function splitAndTrim(line) {
			return _.map(_.split(line, '-'), _.trim);
		}

		var blocked = '';

		var reservableText = _.trim($('.reservableEdit:visible', elements.layoutDialog).val());
		var reservable = _.split(reservableText, "\n");
		if (reservable.length === 0) {
			$('.blockedEdit:visible', elements.layoutDialog).val("00:00 - 00:00");
			return;
		}

		var startIndex = 0;
		if (!_.startsWith(reservable[0], '00:00') && !_.startsWith(reservable[0], '0:00')) {
			blocked += "00:00 - " + splitAndTrim(reservable)[0] + "\n";
			startIndex = 1;
		}

		for (var i = startIndex; i < reservable.length; i++) {
			var firstIteration = i === 0;
			var lastIteration = i + 1 === reservable.length;

			if (_.isEmpty(_.trim(reservable[i]))) {
				continue;
			}

			var current = splitAndTrim(reservable[i]);
			var previous = null;
			if (!firstIteration) {
				previous = splitAndTrim(reservable[i - 1]);
			}

			if (!firstIteration && !lastIteration && current[0] != previous[1]) {
				blocked += previous[1] + " - " + current[0] + "\n";
			}

			if (lastIteration && current[1] != '00:00') {
				blocked += current[1] + ' - 00:00' + "\n";
			}
		}

		$('.blockedEdit:visible', elements.layoutDialog).val(blocked);
	};

	var handleAddError = function (responseText) {
		$('#addScheduleResults').text(responseText);
		$('#addScheduleResults').show();
	};

	var setActiveScheduleId = function (scheduleId) {
		elements.activeId.val(scheduleId);
	};

	var getActiveScheduleId = function () {
		return elements.activeId.val();
	};

	var showChangeLayout = function (e, reservableDiv, blockedDiv, timezone, usesSingleLayout) {
		elements.changeLayoutForm.find('.validationSummary ').addClass('no-show');
		$.each(reservableDiv, function (index, val) {
			var slots = reformatTimeSlots($(val));
			$('#' + $(val).attr('ref')).val(slots);
		});

		$.each(blockedDiv, function (index, val) {
			var slots = reformatTimeSlots($(val));
			$('#' + $(val).attr('ref')).val(slots);
		});

		elements.layoutTimezone.val(timezone.val());
		elements.usesSingleLayout.prop('checked', false);

		if (usesSingleLayout) {
			elements.usesSingleLayout.prop('checked', true);
		}
		elements.usesSingleLayout.trigger('change');

		elements.layoutDialog.modal("show");
	};

	var toggleLayoutChange = function (useSingleLayout) {
		if (useSingleLayout) {
			$('#dailySlots').hide();
			$('#staticSlots').show();
		}
		else {
			$('#staticSlots').hide();
			$('#dailySlots').show();
		}
	};

	var showDeleteDialog = function (e) {
		var scheduleId = getActiveScheduleId();
		elements.deleteDestinationScheduleId.children().removeAttr('disabled');
		elements.deleteDestinationScheduleId.children('option[value="' + scheduleId + '"]').attr('disabled', 'disabled');
		elements.deleteDestinationScheduleId.val('');

		elements.deleteDialog.modal('show');
	};

	var reformatTimeSlots = function (div) {
		var text = $.trim(div.text());
		text = text.replace(/\s\s+/g, ' ');
		text = text.replace(/\s*,\s*/g, '\n');
		return text;
	};

	var showPeakTimesDialog = function (scheduleId) {
		var peakPlaceHolder = $('[data-schedule-id=' + scheduleId + ']').find('.peakPlaceHolder');

		var times = peakPlaceHolder.find('.peakTimes');
		var days = peakPlaceHolder.find('.peakDays');
		var months = peakPlaceHolder.find('.peakMonths');

		if (times.length > 0) {
			var allDay = times.data('all-day');
			var startTime = times.data('start-time');
			var endTime = times.data('end-time');

			var everyday = days.data('everyday');
			var days = days.data('weekdays').split(",");

			var allYear = months.data('all-year');
			var beginMonth = months.data('begin-month');
			var beginDay = months.data('begin-day');
			var endMonth = months.data('end-month');
			var endDay = months.data('end-day');

			if (allDay == 1) {
				elements.peakAllDay.prop('checked', true);
			}
			else {
				elements.peakAllDay.prop('checked', false);
				$('#peakStartTime').val(startTime);
				$('#peakEndTime').val(endTime);
			}

			elements.peakEveryDay.attr('checked', everyday == 1);

			_.each($('#peakDayList').find(':checked'), function (e) {
				$(e).closest('label').button('toggle');
			});

			_.each(days, function (day) {
				$('#peakDay' + day).closest('label').button('toggle');
			});

			if (allYear == 1) {
				elements.peakAllYear.prop('checked', true);
			}
			else {
				elements.peakAllYear.prop('checked', false);
				$('#peakBeginMonth').val(beginMonth);
				$('#peakBeginDay').val(beginDay);
				$('#peakEndMonth').val(endMonth);
				$('#peakEndDay').val(endDay);
			}

			peakOnAllDayChanged();
			peakOnEveryDayChanged();
			peakOnAllYearChanged();
		}

		elements.deletePeakTimes.val('');
		elements.peakTimesDialog.modal('show');
	};

	var peakOnEveryDayChanged = function () {
		if ((elements.peakEveryDay).is(':checked')) {
			elements.peakDayList.addClass('no-show');
		}
		else {
			elements.peakDayList.removeClass('no-show');
		}
	};

	var peakOnAllYearChanged = function () {
		if ((elements.peakAllYear).is(':checked')) {
			elements.peakDateRange.addClass('no-show');
		}
		else {
			elements.peakDateRange.removeClass('no-show');
		}
	};

	var peakOnAllDayChanged = function () {
		if ((elements.peakAllDay).is(':checked')) {
			elements.peakTimes.addClass('no-show');
		}
		else {
			elements.peakTimes.removeClass('no-show');
		}
	};

	var refreshPeakTimes = function (resultHtml) {
		$('[data-schedule-id=' + getActiveScheduleId() + ']').find('.peakPlaceHolder').html(resultHtml);
		elements.peakTimesDialog.modal('hide');
	};

	var wireUpPeakTimeToggles = function () {
		elements.peakEveryDay.on('click', function (e) {
			peakOnEveryDayChanged();
		});

		elements.peakAllYear.on('click', function (e) {
			peakOnAllYearChanged();
		});

		elements.peakAllDay.on('click', function (e) {
			peakOnAllDayChanged();
		});
	};

	var showAvailabilityDialog = function (scheduleId) {
		var placeholder = $('[data-schedule-id=' + scheduleId + ']').find('.availabilityPlaceHolder');
		var dates = placeholder.find('.availableDates');

		var hasAvailability = dates.data('has-availability') == '1';

		// elements.availableAllYear.prop('checked', !hasAvailability);
		elements.availableStartDateTextbox.datepicker("setDate", dates.data('start-date'));
		elements.availableStartDate.trigger('change');

		elements.availableEndDateTextbox.datepicker("setDate", dates.data('end-date'));
		elements.availableEndDate.trigger('change');

		if (!hasAvailability) {
			elements.availableAllYear.trigger('click');
		}

		elements.availabilityDialog.modal('show');
	};

	var refreshAvailability = function (resultHtml) {
		$('[data-schedule-id=' + getActiveScheduleId() + ']').find('.availabilityPlaceHolder').html(resultHtml);
		elements.availabilityDialog.modal('hide');
	};

	var toggleConcurrentReservations = function (scheduleId, toggle, container) {
		var allow = toggle.data('allow') == 1;
		if (allow) {
			container.find('.allowConcurrentYes').addClass('no-show');
			container.find('.allowConcurrentNo').removeClass('no-show');
		}
		else {
			container.find('.allowConcurrentYes').removeClass('no-show');
			container.find('.allowConcurrentNo').addClass('no-show');
		}
		elements.concurrentForm.submit();

		toggle.data('allow', allow ? '0' : '1');
	};

	var _fullCalendar = null;
	var showChangeCustomLayout = function (scheduleId) {
		var customLayoutScheduleId = scheduleId;

		$('#customLayoutDialog').unbind();

		function updateEvent(event) {
			elements.slotStartDate.val(event.start.format('YYYY-MM-DD HH:mm'));
			elements.slotEndDate.val(event.end.format('YYYY-MM-DD HH:mm'));
			elements.slotId.val(event.id);
			ajaxPost(elements.layoutSlotForm, options.submitUrl + '?action=' + options.updateLayoutSlot + '&sid=' + getActiveScheduleId(), null, function (data) {
				_fullCalendar.fullCalendar('refetchEvents');
			});
		}

		$('#customLayoutDialog').unbind('shown.bs.modal');
		$('#customLayoutDialog').on('shown.bs.modal', function () {
			if (_fullCalendar != null) {
				_fullCalendar.fullCalendar('destroy');
			}
			var calendar = $('#calendar');
			_fullCalendar = calendar.fullCalendar({
				header: {
					left: 'prev,next,today', center: 'title', right: 'month,agendaWeek,agendaDay'
				},
				buttonText: opts.calendarOptions.buttonText,
				allDaySlot: false,
				defaultDate: opts.calendarOptions.defaultDate,
				defaultView: 'month',
				eventSources: [{
					url: opts.calendarOptions.eventsUrl, type: 'GET', data: {
						dr: 'events', sid: scheduleId
					}
				}],
				dayClick: function (date, jsEvent, view) {
					if (view.name == 'month') {
						calendar.fullCalendar('changeView', 'agendaDay');
						calendar.fullCalendar('gotoDate', date);
					}
				},
				selectable: true,
				selectHelper: true,
				editable: true,
				droppable: true,
				eventOverlap: false,
				select: function (start, end, jsEvent, view) {
					if (view.name != 'month') {
						elements.confirmCreateSlotDialog.show();
						elements.confirmCreateSlotDialog.position({
							my: 'left bottom', at: 'left top', of: jsEvent
						});
						$('#confirmCreateOK').unbind('click');
						$('#confirmCreateOK').click(function (e) {
							elements.slotStartDate.val(start.format('YYYY-MM-DD HH:mm'));
							elements.slotEndDate.val(end.format('YYYY-MM-DD HH:mm'));
							ajaxPost(elements.layoutSlotForm, options.submitUrl + '?action=' + options.addLayoutSlot + '&sid=' + getActiveScheduleId(), null, function () {
								_fullCalendar.fullCalendar('refetchEvents');
								elements.confirmCreateSlotDialog.hide();
							});
						});
					}
				},
				eventClick: function (event, jsEvent, view) {
					elements.deleteSlotStartDate.val(event.start.format('YYYY-MM-DD HH:mm'));
					elements.deleteSlotEndDate.val(event.end.format('YYYY-MM-DD HH:mm'));
					elements.deleteCustomLayoutDialog.show();
					elements.deleteCustomLayoutDialog.position({
						my: 'left bottom', at: 'left top', of: jsEvent
					});
				},
				eventDrop: function (event, delta, revertFunc) {
					updateEvent(event);
				},
				eventResize: function (event, delta, revertFunc, jsEvent, ui, view) {
					updateEvent(event);
				}
			});
		});

		$('#customLayoutDialog').modal('show');
	};

	function afterDeleteSlot() {
		elements.deleteCustomLayoutDialog.hide();
		_fullCalendar.fullCalendar('refetchEvents');
	}
}