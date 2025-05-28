function BlackoutManagement(opts) {
	var options = opts;

	var elements = {
		startDate: $("#formattedStartDate"),
		endDate: $("#formattedEndDate"),
		scheduleId: $("#scheduleId"),
		resourceId: $("#resourceId"),
		blackoutTable: $("#blackoutTable"),
		reservationTable: $("#reservationTable"),

		allResources: $('#allResources'),
		addResourceId: $('#addResourceId'),
		addScheduleId: $('#addScheduleId'),

		deleteDialog: $('#deleteDialog'),
		deleteRecurringDialog: $('#deleteRecurringDialog'),

		deleteForm: $('#deleteForm'),
		deleteRecurringForm: $('#deleteRecurringForm'),
		addBlackoutForm: $('#addBlackoutForm'),

		referenceNumberList: $(':hidden.reservationId'),

		deleteMultiplePrompt: $('#delete-selected'),
		deleteMultipleDialog: $('#deleteMultipleDialog'),
		deleteMultipleForm: $('#deleteMultipleForm'),
		deleteMultipleCheckboxes: $('.delete-multiple'),
		deleteMultipleSelectAll: $('#delete-all'),
		deleteMultipleCount: $('#deleteMultipleCount'),
		deleteMultiplePlaceHolder: $('#deleteMultiplePlaceHolder')
	};

	var blackoutId;

	BlackoutManagement.prototype.init = function () {

		wireUpUpdateButtons();

		$(".save").click(function () {
			$(this).closest('form').submit();
		});

		$(".cancel").click(function () {
			$(this).closest('.modal').modal("hide");
		});

		$('#result').on('click', '.reload', function (e) {
			location.reload();
		});

		$('#result').on('click', '.unblock', function (e) {
			$('#result').hide();
			$('#wait-box').modal('hide');
		});

		elements.blackoutTable.on('click', '.edit', function (e) {
			$('#update-spinner').removeClass('d-none');
			var tr = $(this).parents('tr');
			var id = tr.attr('data-blackout-id');

			$('#update-box').modal('show');

			var updateDiv = $('#update-contents');

			updateDiv.empty();
			updateDiv.load(opts.editUrl + id, function () {

				$('#update-spinner').addClass('d-none');

				ConfigureAsyncForm($('#editBlackoutForm'), getUpdateUrl, onAddSuccess, null, {
					onBeforeSubmit: onBeforeAddSubmit, target: '#result'
				});

				wireUpUpdateButtons();

				$(".save").click(function () {
					$(this).closest('form').submit();
				});

				$('.blackoutResources').click(function (e) {
					if ($(".blackoutResources input:checked").length == 0) {
						e.preventDefault();
					}
				});
				wireUpTimePickers();

			});
		});

		handleBlackoutApplicabilityChange();
		wireUpTimePickers();

		elements.blackoutTable.on('click', '.update', function (e) {
			e.preventDefault();

			var tr = $(this).parents('tr');
			var id = tr.attr('data-blackout-id');
			setActiveBlackoutId(id);
		});

		elements.blackoutTable.on('click', '.delete', function () {
			showDeleteBlackout();
		});

		elements.blackoutTable.on('click', '.delete-recurring', function () {
			showDeleteRecurringBlackout();
		});

		$('#showAll').click(function (e) {
			e.preventDefault();
			elements.startDate.val('');
			elements.endDate.val('');
			elements.scheduleId.val('');
			elements.resourceId.val('');

			filterReservations();
		});

		$('#filter').click(function (e) {
			e.preventDefault();
			filterReservations();
		});

		elements.deleteMultiplePrompt.click(function (e) {
			e.preventDefault();
			var checked = elements.blackoutTable.find('.delete-multiple:checked');
			elements.deleteMultipleCount.text(checked.length);
			elements.deleteMultiplePlaceHolder.empty();
			elements.deleteMultiplePlaceHolder.append(checked.clone());
			elements.deleteMultipleDialog.modal('show');
		});

		elements.deleteMultipleSelectAll.click(function (e) {
			e.stopPropagation();
			var isChecked = elements.deleteMultipleSelectAll.is(":checked");
			elements.deleteMultipleCheckboxes.prop('checked', isChecked);
			elements.deleteMultiplePrompt.toggleClass('d-none', !isChecked);
		});

		elements.deleteMultipleCheckboxes.click(function (e) {
			e.stopPropagation();
			var numberChecked = elements.reservationTable.find('.delete-multiple:checked').length;
			var allSelected = numberChecked == elements.reservationTable.find('.delete-multiple').length;
			elements.deleteMultipleSelectAll.prop('checked', allSelected);
			elements.deleteMultiplePrompt.toggleClass('d-none', numberChecked == 0);
		});

		ConfigureAsyncForm(elements.addBlackoutForm, getAddUrl, onAddSuccess, null, {
			onBeforeSubmit: onBeforeAddSubmit, target: '#result'
		});
		ConfigureAsyncForm(elements.deleteForm, getDeleteUrl, onDeleteSuccess, null, {
			onBeforeSubmit: onBeforeDeleteSubmit, target: '#result'
		});
		ConfigureAsyncForm(elements.deleteRecurringForm, getDeleteUrl, onDeleteSuccess, null, {
			onBeforeSubmit: onBeforeDeleteSubmit, target: '#result'
		});
		ConfigureAsyncForm(elements.deleteMultipleForm);
	};

	function showDeleteBlackout() {
		elements.deleteDialog.modal('show');
	}

	function showDeleteRecurringBlackout() {
		elements.deleteRecurringDialog.modal('show');
	}

	function setActiveBlackoutId(id) {
		blackoutId = id;
	}

	function getActiveBlackoutId() {
		return blackoutId;
	}

	function showWaitBox() {
		$('#update-box').modal('hide');
		$('#wait-box').modal('show');

		$('#result').hide();
		$('#creatingNotification').show();
	}

	function onBeforeAddSubmit(formData, jqForm, opts) {
		var isValid = BeforeFormSubmit(formData, jqForm, opts);

		if (isValid) {
			showWaitBox();
		}
		return isValid;
	}

	function onBeforeDeleteSubmit() {
		$('.modal').modal('hide');
		showWaitBox();
	}

	function onAddSuccess() {
		$('#creatingNotification').hide();
		$('#result').show();

		$("#reservationTable").find('.editable').each(function () {
			var refNum = $(this).find('.referenceNumber').text();
			$(this).attachReservationPopup(refNum, options.popupUrl);
		});

		$("#reservationTable").on('click', '.editable', function () {
			$(this).addClass('clicked');
			var td = $(this).find('.referenceNumber');
			viewReservation(td.text());
		});
	}

	function onDeleteSuccess() {
		location.reload();
	}

	function getDeleteUrl() {
		return opts.deleteUrl + getActiveBlackoutId();
	}

	function getAddUrl() {
		return opts.addUrl;
	}

	function getUpdateUrl() {
		return opts.updateUrl;
	}

	function filterReservations() {
		var filterQuery = 'sd=' + elements.startDate.val() + '&ed=' + elements.endDate.val() + '&sid=' + elements.scheduleId.val() + '&rid=' + elements.resourceId.val();

		window.location = document.location.pathname + '?' + encodeURI(filterQuery);
	}

	function viewReservation(referenceNumber) {
		window.location = options.reservationUrlTemplate.replace('[refnum]', referenceNumber);
	}

	function handleBlackoutApplicabilityChange() {
		elements.allResources.change(function () {
			if ($(this).is(':checked')) {
				elements.addResourceId.attr('disabled', 'disabled');
				elements.addScheduleId.removeAttr('disabled');
			}
			else {
				elements.addScheduleId.attr('disabled', 'disabled');
				elements.addResourceId.removeAttr('disabled');
			}
		});
	}

	function wireUpTimePickers() {
		$('.timepicker').timepicker({
			timeFormat: options.timeFormat
		});
	}

	function ChangeUpdateScope(updateScopeValue) {
		$('.hdnSeriesUpdateScope').val(updateScopeValue);
	}

	function wireUpUpdateButtons() {
		$('.btnUpdateThisInstance').click(function () {
			ChangeUpdateScope(options.scopeOpts.instance);
		});

		$('.btnUpdateAllInstances').click(function () {
			ChangeUpdateScope(options.scopeOpts.full);
		});
	}
}