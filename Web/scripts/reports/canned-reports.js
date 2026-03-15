function CannedReports(reportOptions) {
  var opts = reportOptions;

  var elements = {
    indicator: $('#indicator'),
    resultsDiv: $('#resultsDiv'),
  };

  this.init = function () {
    wireUpReportLinks();
    /*
		$(document).on('click', '#btnPrint', function (e) {
			e.preventDefault();

			var url = opts.printUrl + reportId;
			window.open(url);
		});

		$(document).on('click', '#btnCsv', function (e) {
			e.preventDefault();

			var url = opts.csvUrl + reportId;
			window.open(url);
		});
*/
    // $(document).on('click', '#btnChart', function(e) {
    // 	e.preventDefault();
    //
    // 	var chart = new Chart();
    // 	chart.generate();
    // 	$('#report-results').hide();
    // });
    /*
		$('.cancel').click(function (e) {
			e.preventDefault();
			$(this).closest('.dialog').dialog('close');
		});
*/
    //		elements.sendEmailButton.click(function (e) {
    //			e.preventDefault();
    //			var before = function () {
    //				elements.sendEmailButton.hide();
    //				elements.emailIndicator.show()
    //			};
    //			var after = function (data) {
    //				$('#emailSent').show().delay(3000).fadeOut(1000);
    //				elements.emailIndicator.hide();
    //				elements.sendEmailButton.show();
    //				$('#emailDiv').dialog('close');
    //			};
    //
    //			ajaxPost(elements.emailForm, opts.emailUrl + reportId, before, after);
    //		});
  };

  var wireUpReportLinks = function () {
    $('#report-list a.report').click(function (e) {
      e.preventDefault();
      reportId = $(this).attr('reportId');
    });

    $('.runNow').click(function (e) {
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

    $('.emailNow').click(function (e) {
      $('#emailDiv').dialog({ modal: true });
    });
  };
}
