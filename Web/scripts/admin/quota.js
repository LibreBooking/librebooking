function QuotaManagement(opts) {
	var options = opts;

	var elements = {

		addForm: $('#addQuotaForm'),
		deleteForm: $('#deleteQuotaForm'),
		deleteDialog: $('#deleteDialog'),
		enforceAllDayToggle: $('#enforce-all-day'),
		enforceHoursTimes: $('#enforce-hours-times'),
		enforceEveryDayToggle: $('#enforce-every-day'),
		enforceDays: $('#enforce-days'),
		enforceStartTime: $('#enforce-time-start'),
		enforceEndTime: $('#enforce-time-end')
	};

	var activeQuotaId = null;

	QuotaManagement.prototype.init = function () {

		$('#quotaList-content').on('click', '.delete', function (e) {
			e.preventDefault();
			setActiveQuotaId($(this).attr('quotaId'));
			elements.deleteDialog.modal('show');
		});

		$(".save").click(function () {
			$(this).closest('form').submit();
		});

		$(".cancel").click(function () {
			$(this).closest('.dialog').modal("hide");
		});

		elements.enforceAllDayToggle.on('change', handleEnforceAllDayToggle);

		elements.enforceEveryDayToggle.on('change', handleEnforceEveryDayToggle);

		ConfigureAsyncForm(elements.addForm, getSubmitCallback(options.actions.addQuota), null, handleAddError, { onBeforeSubmit: validateTimes });
		ConfigureAsyncForm(elements.deleteForm, getSubmitCallback(options.actions.deleteQuota), null, handleAddError);
	};

	var getSubmitCallback = function (action) {
		return function () {
			return options.submitUrl + "?qid=" + getActiveQuotaId() + "&action=" + action;
		};
	};

	var handleAddError = function (responseText) {
		alert(responseText);
	};

	var setActiveQuotaId = function (quotaId) {
		activeQuotaId = quotaId
	};

	var getActiveQuotaId = function () {
		return activeQuotaId;
	};

	var handleEnforceAllDayToggle = function () {
		if (elements.enforceAllDayToggle.is(':checked')) {
			elements.enforceHoursTimes.collapse('hide');
		} else {
			elements.enforceHoursTimes.collapse('show');
		}
	};

	var handleEnforceEveryDayToggle = function () {
		if (elements.enforceEveryDayToggle.is(':checked')) {
			elements.enforceDays.collapse('hide');
		}
		else {
			elements.enforceDays.collapse('show');
		}
	};

	var validateTimes = function () {
		if (document.getElementById('enforce-all-day').checked) {
			return true;
		}

		// NOTE(jlvillal): 2026-01-31: If we update datehelper.ValidateTimeRangeElements()
		// to handle the 'midnight' case we can simplify this code.
		var startElement = document.getElementById('enforce-time-start');
		var endElement = document.getElementById('enforce-time-end');
		// Preserve legacy behavior: allow midnight (00:00) as a valid end time,
		// representing an interval that spans into the next day.
		if (endElement && typeof endElement.value === 'string') {
			var endValue = endElement.value;
			if (endValue) {
				var timeParts = endValue.split(':');
				if (timeParts.length >= 2) {
					var endHour = parseInt(timeParts[0], 10);
					var endMinute = parseInt(timeParts[1], 10);
					if (!isNaN(endHour) && !isNaN(endMinute) && endHour === 0 && endMinute === 0) {
						return true;
					}
				}
			}
		}
		return dateHelper.ValidateTimeRangeElements(startElement, endElement);
	};
}
