function MonitorDisplay(opts) {
  var options = opts;
  var elements = {
    schedules: $('#schedules'),
    resources: $('#resources'),
    format: $('#format'),
    days: $('#days'),
    schedulePlaceholder: $('#monitor-display-schedule'),
  };

  MonitorDisplay.prototype.init = function () {
    elements.schedules.on('change', function (e) {
      var scheduleId = $(this).val();

      ajaxGet(options.resourcesUrl + scheduleId, null, function (data) {
        var first = elements.resources.find('option').first().clone();
        elements.resources.empty();
        elements.resources.append(first);
        $.each(data, function (key, resource) {
          elements.resources.append('<option value=' + resource.id + '>' + resource.name + '</option>');
        });

        refreshDisplay();
      });
    });

    elements.resources.on('change', function (e) {
      refreshDisplay();
    });

    elements.format.on('change', function (e) {
      refreshDisplay();
    });

    elements.days.on('change', function (e) {
      refreshDisplay();
    });

    wireUpRefresh();
  };

  function refreshDisplay() {
    var scheduleId = elements.schedules.val();
    var resourceId = elements.resources.val();
    var days = elements.days.val();
    var format = elements.format.val();

    ajaxGet(
      options.scheduleUrl
        .replace('[sid]', scheduleId)
        .replace('[rid]', resourceId)
        .replace('[days]', days)
        .replace('[format]', format),
      null,
      function (data) {
        elements.schedulePlaceholder.html(data);
        $('#scheduleName').html(elements.schedules.find('option:selected').text());
      }
    );
  }

  function wireUpRefresh() {
    refreshDisplay();

    setInterval(refreshDisplay, 60000);
  }
}
