function BlackoutManagement(opts) {
  var options = opts;

  var elements = {
    startDate: $('#startDate'),
    endDate: $('#endDate'),
    scheduleId: $('#scheduleId'),
    resourceId: $('#resourceId'),
    blackoutTable: $('#blackoutTable'),
    reservationTable: $('#reservationTable'),

    allResources: $('#allResources'),
    addResourceId: $('#addResourceId'),
    addScheduleId: $('#addScheduleId'),

    deleteDialog: document.getElementById('deleteDialog'),
    deleteRecurringDialog: document.getElementById('deleteRecurringDialog'),

    deleteForm: $('#deleteForm'),
    deleteRecurringForm: $('#deleteRecurringForm'),
    addBlackoutForm: $('#addBlackoutForm'),

    referenceNumberList: $(':hidden.reservationId'),

    deleteMultiplePrompt: $('#delete-selected'),
    deleteMultipleDialog: document.getElementById('deleteMultipleDialog'),
    deleteMultipleForm: $('#deleteMultipleForm'),
    deleteMultipleSelectAll: $('#delete-all'),
    deleteMultipleCount: $('#deleteMultipleCount'),
    deleteMultiplePlaceHolder: $('#deleteMultiplePlaceHolder'),
  };

  var blackoutId;

  BlackoutManagement.prototype.init = function () {
    wireUpUpdateButtons();

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('#result').on('click', '.reload', function (e) {
      location.reload();
    });

    $('#result').on('click', '.unblock', function (e) {
      $('#result').hide();
      setDialogVisible('#wait-box', false);
    });

    elements.blackoutTable.on('click', '.edit', function (e) {
      $('#update-spinner').removeClass('d-none');
      var tr = $(this).parents('tr');
      var id = tr.attr('data-blackout-id');

      setDialogVisible('#update-box', true);

      var updateDiv = $('#update-contents');

      updateDiv.empty();
      updateDiv.load(opts.editUrl + id, function () {
        $('#update-spinner').addClass('d-none');

        ConfigureAsyncForm($('#editBlackoutForm'), getUpdateUrl, onAddSuccess, null, {
          onBeforeSubmit: onBeforeAddSubmit,
          target: '#result',
        });

        wireUpUpdateButtons();

        $('.save').click(function () {
          $(this).closest('form').submit();
        });

        $('.blackoutResources').click(function (e) {
          if ($('.blackoutResources input:checked').length == 0) {
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
      setDialogVisible(elements.deleteMultipleDialog, true);
    });

    elements.deleteMultipleSelectAll.click(function (e) {
      e.stopPropagation();
      var isChecked = elements.deleteMultipleSelectAll.is(':checked');
      elements.blackoutTable.find('.delete-multiple').prop('checked', isChecked);
      elements.deleteMultiplePrompt.toggleClass('d-none', !isChecked);
    });

    elements.blackoutTable.on('click', '.delete-multiple', function (e) {
      e.stopPropagation();
      var allCheckboxes = elements.blackoutTable.find('.delete-multiple');
      var numberChecked = allCheckboxes.filter(':checked').length;
      var allSelected = numberChecked == allCheckboxes.length;
      elements.deleteMultipleSelectAll.prop('checked', allSelected);
      elements.deleteMultiplePrompt.toggleClass('d-none', numberChecked == 0);
    });

    ConfigureAsyncForm(elements.addBlackoutForm, getAddUrl, onAddSuccess, null, {
      onBeforeSubmit: onBeforeAddSubmit,
      target: '#result',
    });
    ConfigureAsyncForm(elements.deleteForm, getDeleteUrl, onDeleteSuccess, null, {
      onBeforeSubmit: onBeforeDeleteSubmit,
      target: '#result',
    });
    ConfigureAsyncForm(elements.deleteRecurringForm, getDeleteUrl, onDeleteSuccess, null, {
      onBeforeSubmit: onBeforeDeleteSubmit,
      target: '#result',
    });
    ConfigureAsyncForm(elements.deleteMultipleForm);
  };

  function showDeleteBlackout() {
    setDialogVisible(elements.deleteDialog, true);
  }

  function showDeleteRecurringBlackout() {
    setDialogVisible(elements.deleteRecurringDialog, true);
  }

  function setActiveBlackoutId(id) {
    blackoutId = id;
  }

  function getActiveBlackoutId() {
    return blackoutId;
  }

  function showWaitBox() {
    setDialogVisible('#update-box', false);
    setDialogVisible('#wait-box', true);

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

  function onBeforeDeleteSubmit(formData, jqForm) {
    var modal = jqForm[0].closest('.modal');
    setDialogVisible(modal, false);
    showWaitBox();
  }

  function onAddSuccess() {
    $('#creatingNotification').hide();
    $('#result').show();

    $('#reservationTable')
      .find('.editable')
      .each(function () {
        var refNum = $(this).attr('data-refnum');
        $(this).find('.reservationTitle').attachReservationPopup(refNum, options.popupUrl);
      });

    $('#reservationTable').on('click', '.editable', function () {
      $(this).addClass('clicked');
      viewReservation($(this).attr('data-refnum'));
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
    var filterQuery =
      'sd=' +
      elements.startDate.val() +
      '&ed=' +
      elements.endDate.val() +
      '&sid=' +
      elements.scheduleId.val() +
      '&rid=' +
      elements.resourceId.val();

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
      } else {
        elements.addScheduleId.attr('disabled', 'disabled');
        elements.addResourceId.removeAttr('disabled');
      }
    });
  }

  function wireUpTimePickers() {
    document.querySelectorAll('.timepicker').forEach((el) => {
      dateHelper.initTimePicker(el);
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
