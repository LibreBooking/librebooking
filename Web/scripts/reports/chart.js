var TimeTickFormatter = function (format, val) {
  var totalSeconds = parseInt(val, 10);
  if (isNaN(totalSeconds)) {
    return '';
  }
  var numdays = Math.floor(totalSeconds / 86400);
  var numhours = Math.floor((totalSeconds % 86400) / 3600);
  var numminutes = Math.floor(((totalSeconds % 86400) % 3600) / 60);
  var hoursAndMinutes = numhours + 'h ' + numminutes + 'm';
  if (numdays > 0) {
    return numdays + 'd ' + hoursAndMinutes;
  }
  return hoursAndMinutes;
};

// Default series colors: blue, coral, green, amber, purple, orange, teal, red (cycles for >8 series)
const chartPalette = ['#3ba7e8', '#ff7f50', '#4caf50', '#ffca28', '#8e44ad', '#d35400', '#16a085', '#c0392b'];

function pad(value) {
  return String(value).padStart(2, '0');
}

function getSeriesColor(index) {
  return chartPalette[index % chartPalette.length];
}

function formatDateValue(date, format) {
  var normalizedFormat = format || '%e/%m';
  normalizedFormat = normalizedFormat.replace(/\$/g, '%');
  var replacements = {
    '%e': date.getDate(),
    '%d': date.getDate(),
    '%m': pad(date.getMonth() + 1),
    '%y': ('' + date.getFullYear()).slice(-2),
    '%Y': date.getFullYear(),
    '%H': pad(date.getHours()),
    '%M': pad(date.getMinutes()),
  };
  return normalizedFormat.replace(/%[edmyYHM]/g, function (token) {
    return replacements[token] !== undefined ? replacements[token] : token;
  });
}

function ReportChart(options) {
  var chartDiv = $('#chartdiv');
  var chartIndicator = $('#chart-indicator');
  var currentChart = null;
  var chartJsPluginRegistered = false;

  this.clear = function () {
    if (currentChart) {
      currentChart.destroy();
      currentChart = null;
    }
    chartDiv.empty();
    chartDiv.hide();
  };

  this.generate = function () {
    var resultsDiv = $('#report-results');
    chartDiv.show();
    if (chartIndicator.length) {
      chartIndicator.show();
    }

    var chartType = resultsDiv.attr('chart-type');
    var series = null;
    if (chartType == 'totalTime') {
      series = new TotalTimeSeries();
    } else if (chartType == 'total') {
      series = new TotalSeries();
    } else {
      series = new DateSeries(options);
    }

    $('#report-results>tbody>tr').each(function () {
      series.Add($(this));
    });

    var renderChart = function () {
      var height = getResponsiveChartHeight(series.GetChartHeight());
      var renderArea = ensureCanvas(height);
      if (currentChart) {
        currentChart.destroy();
        currentChart = null;
      }
      var chartConfig = series.GetChartConfig();
      applyChartTitle(chartConfig, chartType);
      // Replace dense legend with horizontal scroll for large number of series.
      if (chartConfig.options && chartConfig.options.plugins && chartConfig.options.plugins.legend) {
        chartConfig.options.plugins.legend.display = false;
      }
      currentChart = new window.Chart(renderArea.canvas.getContext('2d'), chartConfig);
      renderScrollableLegend(currentChart, renderArea.legendHost, series.GetLegendOptions());
      if (chartIndicator.length) {
        chartIndicator.hide();
      }
    };

    ensureChartJsLoaded(function (isLoaded) {
      if (!isLoaded) {
        console.error('Chart.js is not loaded. Report chart cannot be rendered.');
        if (chartIndicator.length) {
          chartIndicator.hide();
        }
        return;
      }
      renderChart();
    });
  };

  function ensureCanvas(height) {
    var actions = chartDiv.find('#chart-actions').detach();
    chartDiv.css({ height: 'auto', 'min-height': '0' }).empty();
    if (actions.length) {
      chartDiv.append(actions);
      actions.removeClass('d-none').addClass('d-flex');
      actions
        .find('#btnDownloadChartImage')
        .off('click.chart')
        .on('click.chart', function () {
          downloadChartImage();
        });
    }

    var canvasWrapper = document.createElement('div');
    canvasWrapper.style.cssText = 'position: relative; width: 100%; height: ' + height + 'px;';
    chartDiv.append(canvasWrapper);
    var canvas = document.createElement('canvas');
    canvas.id = 'report-chart-canvas';
    canvas.style.cssText = 'width: 100%; height: 100%;';
    canvasWrapper.appendChild(canvas);

    var legendHost = document.createElement('div');
    legendHost.id = 'report-chart-scroll-legend';
    legendHost.style.cssText =
      'margin-top: 10px; overflow-x: auto; overflow-y: hidden; white-space: nowrap; width: 100%;';
    chartDiv.append(legendHost);

    return {
      canvas: canvas,
      legendHost: legendHost,
    };
  }

  function renderScrollableLegend(chart, legendHost, legendOptions) {
    if (!legendHost) {
      return;
    }
    legendHost.innerHTML = '';

    if (!legendOptions || !legendOptions.show) {
      legendHost.style.display = 'none';
      return;
    }

    var datasets = chart && chart.data ? chart.data.datasets : [];
    if (!datasets || datasets.length <= 1) {
      legendHost.style.display = 'none';
      return;
    }

    legendHost.style.display = 'block';
    for (let datasetIndex = 0; datasetIndex < datasets.length; datasetIndex++) {
      var dataset = datasets[datasetIndex];
      var item = document.createElement('button');
      item.type = 'button';
      item.className = 'btn btn-sm btn-outline-secondary';
      item.style.cssText =
        'display: inline-flex; align-items: center; margin-right: 8px; margin-bottom: 6px; max-width: 340px;';
      item.title = dataset.label || '';

      var colorSwatch = document.createElement('span');
      colorSwatch.style.cssText =
        'display: inline-block; width: 12px; height: 12px; min-width: 12px; border-radius: 2px; margin-right: 6px;';
      colorSwatch.style.background = dataset.borderColor || dataset.backgroundColor || '#999';

      var label = document.createElement('span');
      label.textContent = dataset.label || '';
      label.style.cssText = 'overflow: hidden; text-overflow: ellipsis; white-space: nowrap;';

      item.appendChild(colorSwatch);
      item.appendChild(label);

      var syncState = function () {
        var visible = chart.isDatasetVisible(datasetIndex);
        item.style.opacity = visible ? '1' : '0.45';
      };

      item.addEventListener('click', function () {
        chart.setDatasetVisibility(datasetIndex, !chart.isDatasetVisible(datasetIndex));
        chart.update();
        syncState();
      });

      syncState();
      legendHost.appendChild(item);
    }
  }

  function downloadChartImage() {
    var canvas = document.getElementById('report-chart-canvas');
    if (!canvas) {
      return;
    }

    var legendItems = getLegendItemsForExport();
    var exportCanvas = canvas;
    if (legendItems.length > 1) {
      exportCanvas = createExportCanvasWithLegend(canvas, legendItems);
    }

    var link = document.createElement('a');
    link.href = exportCanvas.toDataURL('image/png');
    var baseTitle = (document && document.title ? document.title : '') || 'report-chart';
    var safeName = baseTitle
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');
    if (!safeName) {
      safeName = 'report-chart';
    }
    link.download = safeName + '.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

  function getLegendItemsForExport() {
    if (!currentChart || !currentChart.data || !currentChart.data.datasets) {
      return [];
    }

    var items = [];
    for (var i = 0; i < currentChart.data.datasets.length; i++) {
      if (!currentChart.isDatasetVisible(i)) {
        continue;
      }
      var dataset = currentChart.data.datasets[i];
      items.push({
        label: dataset.label || '',
        color: dataset.borderColor || dataset.backgroundColor || '#999',
      });
    }
    return items;
  }

  function createExportCanvasWithLegend(sourceCanvas, legendItems) {
    var padding = 16;
    var rowHeight = 20;
    var markerSize = 11;
    var legendWidth = 420;
    var exportCanvas = document.createElement('canvas');
    exportCanvas.width = sourceCanvas.width + legendWidth + padding;
    exportCanvas.height = Math.max(sourceCanvas.height, legendItems.length * rowHeight + padding * 2);

    var ctx = exportCanvas.getContext('2d');
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
    ctx.drawImage(sourceCanvas, 0, 0);

    var legendX = sourceCanvas.width + padding;
    var y = padding;
    ctx.font = '13px sans-serif';
    ctx.textBaseline = 'middle';

    for (var i = 0; i < legendItems.length; i++) {
      var item = legendItems[i];
      ctx.fillStyle = item.color;
      ctx.fillRect(legendX, y - Math.floor(markerSize / 2), markerSize, markerSize);

      ctx.fillStyle = '#1f2937';
      ctx.fillText(item.label, legendX + markerSize + 8, y);
      y += rowHeight;
    }

    return exportCanvas;
  }

  function getResponsiveChartHeight(preferredHeight) {
    var viewportHeight = window.innerHeight || 900;
    var responsiveHeight = Math.round(viewportHeight * 0.62);
    var baseHeight = preferredHeight || responsiveHeight;
    return Math.max(340, Math.min(680, baseHeight));
  }

  function ensureChartJsLoaded(callback) {
    if (typeof window.Chart === 'undefined') {
      callback(false);
      return;
    }
    if (chartJsPluginRegistered || typeof window.ChartDataLabels === 'undefined') {
      callback(true);
      return;
    }
    window.Chart.register(window.ChartDataLabels);
    chartJsPluginRegistered = true;
    callback(true);
  }

  function applyChartTitle(chartConfig, chartType) {
    if (!chartConfig.options) {
      chartConfig.options = {};
    }
    if (!chartConfig.options.plugins) {
      chartConfig.options.plugins = {};
    }

    var titleText = resolveChartTitle(chartType);
    chartConfig.options.plugins.title = {
      display: titleText.length > 0,
      text: titleText,
      padding: { top: 6, bottom: 14 },
      font: { size: 15, weight: 'bold' },
    };
  }

  function resolveChartTitle(chartType) {
    var explicitTitle = (options && options.chartTitle ? options.chartTitle : '').trim();
    if (explicitTitle.length > 0) {
      return explicitTitle;
    }

    var sourceTitle = ($('#resultsDiv').attr('data-report-title') || '').trim();
    if (sourceTitle.length > 0) {
      return sourceTitle;
    }

    var headingTitle = (
      $('#resultsDiv h1:first, #resultsDiv h2:first, #resultsDiv h3:first').first().text() || ''
    ).trim();
    if (headingTitle.length > 0) {
      return headingTitle;
    }

    if (chartType == 'totalTime') {
      return 'Total Time';
    }
    if (chartType == 'total') {
      return 'Total';
    }
    return 'Report';
  }
}

function Series() {
  this.Add = function (row) {};
  this.GetData = function () {
    return [];
  };
  this.GetLabels = function () {
    return [];
  };
  this.GetTickFormatter = function () {
    return function (value) {
      return value;
    };
  };
  this.GetDataLabelFormatter = function () {
    return function (value) {
      return value;
    };
  };
  this.GetTooltipFormatter = function () {
    return function (context) {
      var label = context.dataset.label ? context.dataset.label + ': ' : '';
      return label + context.formattedValue;
    };
  };
  this.GetGraphRenderer = function () {
    return 'bar';
  };
  this.GetChartHeight = function () {
    return null;
  };
  this.GetLegendOptions = function () {
    return { show: false, position: 'top' };
  };
  this.GetChartConfig = function () {
    var rawData = this.GetData();
    var labels = [],
      values = [];
    if (rawData.length > 0 && rawData[0].length > 0) {
      for (var i = 0; i < rawData[0].length; i++) {
        labels.push(rawData[0][i][0]);
        values.push(rawData[0][i][1]);
      }
    }
    return {
      type: this.GetGraphRenderer(),
      data: {
        labels: labels,
        datasets: [
          {
            label: '',
            data: values,
            backgroundColor: getSeriesColor(0),
            borderColor: getSeriesColor(0),
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: this.GetLegendOptions().show, position: this.GetLegendOptions().position },
          tooltip: { callbacks: { label: this.GetTooltipFormatter() } },
          datalabels: {
            formatter: this.GetDataLabelFormatter(),
            anchor: 'end',
            align: 'end',
            clamp: true,
            color: '#111827',
            font: { weight: 'bold' },
          },
        },
        scales: {
          x: { ticks: { maxRotation: 30, minRotation: 30 } },
          y: {
            beginAtZero: true,
            ticks: { callback: this.GetTickFormatter() },
          },
        },
      },
    };
  };
}

function TotalSeries() {
  this.series = [];
  this.labels = [];
  this.Add = function (row) {
    var itemLabel = row.find('td[chart-column-type="label"]').text();
    var val = parseInt(row.find('td[chart-column-type="total"]').attr('chart-value'), 10);
    this.series.push([itemLabel, val]);
  };
  this.GetData = function () {
    return [this.series];
  };
  this.GetLabels = function () {
    return this.labels;
  };
}
TotalSeries.prototype = new Series();

function TotalTimeSeries() {
  this.series = [];
  this.labels = [];
  this.GetTickFormatter = function () {
    return function (value) {
      return TimeTickFormatter(null, value);
    };
  };
  this.GetDataLabelFormatter = function () {
    return function (value) {
      return TimeTickFormatter(null, value);
    };
  };
  this.GetTooltipFormatter = function () {
    return function (context) {
      var label = context.label ? context.label + ': ' : '';
      var value = context.parsed && context.parsed.y !== undefined ? context.parsed.y : context.raw;
      return label + TimeTickFormatter(null, value);
    };
  };
}
TotalTimeSeries.prototype = new TotalSeries();

function DateSeries(options) {
  this.groups = {};
  this.dataLoaded = false;
  this.cachedData = null;

  this.Add = function (row) {
    var rawDate = row.find('td[chart-column-type="date"]').attr('chart-value') || '';
    var dateParts = rawDate.split('-');
    var date =
      dateParts.length === 3
        ? new Date(parseInt(dateParts[0], 10), parseInt(dateParts[1], 10) - 1, parseInt(dateParts[2], 10))
        : new Date(rawDate);
    var groupCell = row.find('td[chart-group="r"],td[chart-group="a"]');
    var groupId = groupCell.attr('chart-value');
    var groupName = groupCell.text();
    var totalValue = row.find('td[chart-column-type="total"]').attr('chart-value');
    var total = _.isEmpty(totalValue) ? 1 : parseInt(totalValue, 10);
    var timestamp = date.getTime();
    if (!this.groups[groupId]) {
      this.groups[groupId] = new this.GroupSeries(groupName, groupId);
    }
    this.groups[groupId].AddDate(timestamp, total);
  };

  this.GroupSeries = function (label, groupId) {
    var groupLabel = label;
    var points = {};
    var id = groupId;
    this.AddDate = function (timestamp, count) {
      count = count === '' || count === undefined ? 0 : count;
      points[timestamp] = (points[timestamp] || 0) + count;
    };
    this.GetLabel = function () {
      return groupLabel;
    };
    this.GetPoints = function () {
      return points;
    };
    this.GetId = function () {
      return id;
    };
  };

  this.GetXAxisFormat = function () {
    return options.dateAxisFormat;
  };
  this.GetGraphRenderer = function () {
    return 'line';
  };
  this.GetLegendOptions = function () {
    return { show: true, position: 'right' };
  };
  this.GetChartHeight = function () {
    return null;
  };

  this.GetChartData = function () {
    if (this.dataLoaded) {
      return this.cachedData;
    }
    var timestamps = [],
      timestampMap = {},
      datasets = [],
      labels = [],
      colorIndex = 0;
    for (var group in this.groups) {
      if (!this.groups.hasOwnProperty(group)) continue;
      var groupPoints = this.groups[group].GetPoints();
      for (var timestamp in groupPoints) {
        if (!timestampMap[timestamp]) {
          timestampMap[timestamp] = true;
          timestamps.push(parseInt(timestamp, 10));
        }
      }
    }
    timestamps.sort(function (a, b) {
      return a - b;
    });
    for (var i = 0; i < timestamps.length; i++) {
      labels.push(formatDateValue(new Date(timestamps[i]), this.GetXAxisFormat()));
    }
    for (var seriesId in this.groups) {
      if (!this.groups.hasOwnProperty(seriesId)) continue;
      var seriesPoints = this.groups[seriesId].GetPoints();
      var seriesData = [];
      for (var j = 0; j < timestamps.length; j++) {
        seriesData.push(seriesPoints[timestamps[j]] !== undefined ? seriesPoints[timestamps[j]] : null);
      }
      var color = getSeriesColor(colorIndex);
      datasets.push({
        label: this.groups[seriesId].GetLabel(),
        data: seriesData,
        borderColor: color,
        backgroundColor: color,
        pointBackgroundColor: color,
        pointBorderColor: color,
        pointRadius: 3,
        pointHoverRadius: 4,
        tension: 0,
        fill: false,
        spanGaps: true,
      });
      colorIndex++;
    }
    this.cachedData = { labels: labels, datasets: datasets };
    this.dataLoaded = true;
    return this.cachedData;
  };

  this.GetChartConfig = function () {
    var chartData = this.GetChartData();
    return {
      type: this.GetGraphRenderer(),
      data: chartData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
          legend: { display: this.GetLegendOptions().show, position: this.GetLegendOptions().position },
          tooltip: { callbacks: { label: this.GetTooltipFormatter() } },
          datalabels: {
            formatter: this.GetDataLabelFormatter(),
            align: 'top',
            anchor: 'end',
            clamp: true,
            color: '#111827',
            font: { size: 10 },
          },
        },
        scales: {
          x: { ticks: { maxRotation: 30, minRotation: 30 } },
          y: {
            beginAtZero: true,
            ticks: { callback: this.GetTickFormatter() },
          },
        },
      },
    };
  };
}
DateSeries.prototype = new Series();

window.ReportChart = ReportChart;
