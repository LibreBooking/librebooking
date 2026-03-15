function GenerateReports(reportOptions) {
  var opts = reportOptions;

  var elements = {
    indicator: $('#indicator'),
    customReportForm: $('#customReportInput'),
    saveDialog: $('#saveDialog'),
    saveReportForm: $('#saveReportForm'),
    resultsDiv: $('#resultsDiv'),
  };

  GenerateReports.prototype.init = function () {
    $('#selectDiv input').click(function () {
      $('div.select-toggle').hide();

      var selectedItem = $(this).attr('id');
      if (selectedItem == 'results_list') {
        $('#listOfDiv').show();
      } else if (selectedItem != 'results_utilization') {
        $('#aggregateDiv').show();
      }
    });

    wireUpAutocompleteFilters();

    document.addEventListener('click', function (e) {
      if (e.target.matches('.dateinput')) {
        const radio = document.getElementById('range_within');
        radio.checked = true;
      }
    });

    $('#btnCustomReport').click(function (e) {
      e.preventDefault();

      var before = function () {
        elements.indicator.removeClass('d-none').insertBefore(elements.resultsDiv);
        elements.resultsDiv.html('');
      };

      var after = function (data) {
        elements.indicator.addClass('d-none');
        elements.resultsDiv.html(data);
      };

      ajaxPost(elements.customReportForm, opts.customReportUrl, before, after);
    });

    elements.saveDialog.on('shown.bs.modal', function () {
      $('#saveReportName').focus();
    });

    $(document).on('click', '#btnSaveReportPrompt', function (e) {
      e.preventDefault();
      e.stopPropagation();
      elements.saveDialog.find(':text').val('');
      elements.saveDialog.modal('show');
    });

    $('.save').on('click', function () {
      $(this).closest('form').submit();
    });

    $('#saveReportForm').submit(function (e) {
      handleSave(e);
    });

    $('#btnSaveReport').click(function (e) {
      handleSave(e);
    });
  };

  function wireUpAutocompleteFilters() {
    var selectFilterItem = function (filterDiv, selectedId, selectedName) {
      filterDiv.find('.filter-id').val(selectedId);
      filterDiv.find('.selected').text(selectedName).show();
      filterDiv.find('.filter-input').hide();
    };

    $('#user-filter').userAutoComplete(opts.userAutocompleteUrl, function (ui) {
      selectFilterItem($('#user-filter-div'), ui.item.value, ui.item.label);
    });

    $('#participant-filter').userAutoComplete(opts.userAutocompleteUrl, function (ui) {
      selectFilterItem($('#participant-filter-div'), ui.item.value, ui.item.label);
    });
  }

  var handleSave = function (e) {
    e.preventDefault();
    var before = function () {};

    var after = function (data) {
      elements.saveDialog.modal('hide');
      $('#saveMessage').show().delay(3000).fadeOut(1000);
    };

    ajaxPost($('#customReportInput, #saveReportForm'), opts.saveUrl, before, after);
  };
}
