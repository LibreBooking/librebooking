function CannedReports(reportOptions) {
  var opts = reportOptions;
  var reportId = 0;
  var selectedReportTitle = '';

  var elements = {
    indicator: $('#indicator'),
    resultsDiv: $('#resultsDiv'),
  };

  this.init = function () {
    wireUpReportLinks();
  };

  var getCannedReportTitle = function (link) {
    var groupTitle = reportsCleanText(link.closest('tr').find('td:first').text());
    var rangeTitle = reportsCleanText(link.text());
    if (groupTitle.length && rangeTitle.length) {
      return groupTitle + ' - ' + rangeTitle;
    }
    return groupTitle || rangeTitle;
  };

  var wireUpReportLinks = function () {
    $('#report-list a.report').on('click', function (e) {
      e.preventDefault();
      reportId = $(this).attr('reportId');
    });

    $('.runNow').on('click', function (e) {
      selectedReportTitle = getCannedReportTitle($(this));

      var before = function () {
        elements.indicator.removeClass('d-none').insertBefore(elements.resultsDiv);
        elements.resultsDiv.attr('data-report-title', selectedReportTitle);
        elements.resultsDiv.html('');
      };

      var after = function (data) {
        elements.indicator.addClass('d-none');
        elements.resultsDiv.html(data);
        elements.resultsDiv.attr('data-report-title', selectedReportTitle);
      };

      ajaxGet(opts.generateUrl + reportId, before, after);
    });

    $('.emailNow').on('click', function (e) {
      $('#emailDiv').dialog({ modal: true });
    });
  };
}
