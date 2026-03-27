function CannedReports(reportOptions) {
  var opts = reportOptions;

  var elements = {
    indicator: $('#indicator'),
    resultsDiv: $('#resultsDiv'),
  };

  this.init = function () {
    wireUpReportLinks();
  };

  var wireUpReportLinks = function () {
    $('#report-list a.report').on('click', function (e) {
      e.preventDefault();
      reportId = $(this).attr('reportId');
    });

    $('.runNow').on('click', function (e) {
      var before = function () {
        elements.indicator.removeClass('d-none').insertBefore(elements.resultsDiv);
        elements.resultsDiv.html('');
      };

      var after = function (data) {
        elements.indicator.addClass('d-none');
        elements.resultsDiv.html(data);
      };

      ajaxGet(opts.generateUrl + reportId, before, after);
    });

    $('.emailNow').on('click', function (e) {
      $('#emailDiv').dialog({ modal: true });
    });
  };
}
