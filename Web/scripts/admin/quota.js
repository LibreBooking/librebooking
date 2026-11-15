function QuotaManagement(opts) {
  var options = opts;

  var elements = {
    addForm: $('#addQuotaForm'),
    deleteForm: $('#deleteQuotaForm'),
    deleteDialog: document.getElementById('deleteDialog'),
    enforceAllDayToggle: document.getElementById('enforce-all-day'),
    enforceHoursTimes: document.getElementById('enforce-hours-times'),
    enforceEveryDayToggle: document.getElementById('enforce-every-day'),
    enforceDays: document.getElementById('enforce-days'),
    enforceStartTime: $('#enforce-time-start'),
    enforceEndTime: $('#enforce-time-end'),
  };

  var activeQuotaId = null;

  QuotaManagement.prototype.init = function () {
    $('#quotaList-content').on('click', '.delete', function (e) {
      e.preventDefault();
      setActiveQuotaId($(this).attr('quotaId'));
      setDialogVisible(elements.deleteDialog, true);
    });

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    wireCollapseToggle(elements.enforceAllDayToggle, elements.enforceHoursTimes, function () {
      return !elements.enforceAllDayToggle.checked;
    });

    wireCollapseToggle(elements.enforceEveryDayToggle, elements.enforceDays, function () {
      return !elements.enforceEveryDayToggle.checked;
    });

    ConfigureAsyncForm(elements.addForm, getSubmitCallback(options.actions.addQuota), null, handleAddError, {
      onBeforeSubmit: validateTimes,
    });

    ConfigureAsyncForm(elements.deleteForm, getSubmitCallback(options.actions.deleteQuota), null, handleAddError);
  };

  var getSubmitCallback = function (action) {
    return function () {
      return options.submitUrl + '?qid=' + getActiveQuotaId() + '&action=' + action;
    };
  };

  var handleAddError = function (responseText) {
    alert(responseText);
  };

  var setActiveQuotaId = function (quotaId) {
    activeQuotaId = quotaId;
  };

  var getActiveQuotaId = function () {
    return activeQuotaId;
  };

  var wireCollapseToggle = function (checkbox, collapseElement, getShouldShow) {
    if (!checkbox || !collapseElement) {
      return;
    }

    var sync = function () {
      syncCollapse(collapseElement, getShouldShow);
    };

    checkbox.addEventListener('change', sync);

    collapseElement.addEventListener('shown.bs.collapse', sync);
    collapseElement.addEventListener('hidden.bs.collapse', sync);
  };

  var validateTimes = function () {
    if (document.getElementById('enforce-all-day').checked) {
      return true;
    }

    var startElement = document.getElementById('enforce-time-start');
    var endElement = document.getElementById('enforce-time-end');

    return dateHelper.ValidateTimeRangeElements(startElement, endElement);
  };
}
