function SavedReports(reportOptions) {
  var opts = reportOptions;

  var elements = {
    indicator: $('#indicator'),
    resultsDiv: $('#resultsDiv'),
    emailForm: $('#emailForm'),
    deleteForm: $('#deleteForm'),
    sendEmailButton: $('#btnSendEmail'),
    emailIndicator: $('#sendEmailIndicator'),
    deleteReportButton: $('#btnDeleteReport'),
  };

  var reportId = 0;

  this.init = function () {
    ConfigureAsyncForm(
      elements.emailForm,
      function () {
        return opts.emailUrl + reportId;
      },
      function (data) {
        $('#emailSent').show().delay(3000).fadeOut(1000);
        $('#emailDiv').modal('hide');
      }
    );

    ConfigureAsyncForm(elements.deleteForm, function () {
      return opts.deleteUrl + reportId;
    });

    wireUpReportLinks();

    $('.save').on('click', function () {
      $(this).closest('form').submit();
    });
  };

  var wireUpReportLinks = function () {
    $('#report-list a.report').on('click', function (e) {
      e.preventDefault();
      reportId = $(this).closest('tr').attr('reportId');
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
      $('#emailDiv').modal('show');
    });

    $('.delete').on('click', function (e) {
      $('#deleteDiv').modal('show');
    });
  };

  /**

	 // TODO: NK 2012-07-17 scheduled reports on hold for now
	 function InitializeRepeatElements() {
	 elements.repeatOptions.change(function () {
	 ChangeRepeatOptions();
	 });
	 }

	 function InitializeRepeatOptions() {
	 if (options.repeatType) {
	 elements.repeatOptions.val(options.repeatType);
	 $('#repeat_every').val(options.repeatInterval);
	 for (var i = 0; i < options.repeatWeekdays.length; i++) {
	 var id = "#repeatDay" + options.repeatWeekdays[i];
	 $(id).attr('checked', true);
	 }

	 $("#repeatOnMonthlyDiv :radio[value='" + options.repeatMonthlyType + "']").attr('checked', true);

	 ChangeRepeatOptions();
	 }
	 }

	 function ChangeRepeatOptions() {
	 var repeatDropDown = elements.repeatOptions;
	 if (repeatDropDown.val() != 'none') {
	 $('#repeatUntilDiv').show();
	 }
	 else {
	 $('div[id!=repeatOptions]', elements.repeatDiv).hide();
	 }

	 if (repeatDropDown.val() == 'daily') {
	 $('.weeks', elements.repeatDiv).hide();
	 $('.months', elements.repeatDiv).hide();
	 $('.years', elements.repeatDiv).hide();

	 $('.days', elements.repeatDiv).show();
	 }

	 if (repeatDropDown.val() == 'weekly') {
	 $('.days', elements.repeatDiv).hide();
	 $('.months', elements.repeatDiv).hide();
	 $('.years', elements.repeatDiv).hide();

	 $('.weeks', elements.repeatDiv).show();
	 }

	 if (repeatDropDown.val() == 'monthly') {
	 $('.days', elements.repeatDiv).hide();
	 $('.weeks', elements.repeatDiv).hide();
	 $('.years', elements.repeatDiv).hide();

	 $('.months', elements.repeatDiv).show();
	 }

	 if (repeatDropDown.val() == 'yearly') {
	 $('.days', elements.repeatDiv).hide();
	 $('.weeks', elements.repeatDiv).hide();
	 $('.months', elements.repeatDiv).hide();

	 $('.years', elements.repeatDiv).show();
	 }
	 }

	 */
}
