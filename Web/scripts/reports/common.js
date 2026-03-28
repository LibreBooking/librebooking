window.reportsCleanText = function (value) {
  return (value || '').replace(/\s+/g, ' ').trim();
};

function ReportsCommon(opts) {
  return {
    init: function () {
      $(document).on('click', '#btnChart', function (e) {
        e.preventDefault();
        $('#approveDiv').modal('show');

        // Use a small delay to ensure the modal is displayed sooner
        setTimeout(function () {
          try {
            var chart = new ReportChart(opts.chartOpts);
            chart.generate();
            $('#report-results').hide();
          } catch (error) {
            console.error('Unable to generate report chart', error);
          } finally {
            $('#approveDiv').modal('hide');
          }
        }, 500);
      });
    },
  };
}
