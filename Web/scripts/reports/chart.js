var TimeTickFormatter = function (format, val) {
	var numdays = Math.floor(val / 86400);
	var numhours = Math.floor((val % 86400) / 3600);
	var numminutes = Math.floor(((val % 86400) % 3600) / 60);

	var hoursAndMinutes = numhours + "h " + numminutes + "m ";

	if (numdays > 0) {
		return numdays + "d " + hoursAndMinutes;
	}
	return hoursAndMinutes;
};

function Chart(options) {
	var chartDiv = $('#chartdiv');
	var chartIndicator = $('#chart-indicator');

	this.clear = function () {
		chartDiv.empty();
		chartDiv.hide();
	};

	this.generate = function () {
		var resultsDiv = $('#report-results');
		chartDiv.show();
		chartIndicator.show();

		var chartType = resultsDiv.attr('chart-type');

		var series = null;
		if (chartType == 'totalTime') {
			series = new TotalTimeSeries();
		}
		else if (chartType == 'total') {
			series = new TotalSeries();
		}
		else {
			series = new DateSeries(options);
		}

		$('#report-results>tbody>tr').each(function () {
			series.Add($(this));
		});

        var plot = $.jqplot('chartdiv', series.GetData(), {
			axesDefaults:{
				tickRenderer:$.jqplot.CanvasAxisTickRenderer,
				// tickOptions:{
				// 	fontSize:'10pt'
				// }
			},
			seriesDefaults:{
				renderer:series.GetGraphRenderer(),
				rendererOptions:{ fillToZero:true },
				pointLabels:{show:true}

			},
			series: series.GetLabels(),
			legend:series.GetLegendOptions(),
			axes:{
				xaxis:{
					renderer:series.GetXAxisRenderer(),
					tickOptions:{
						angle:-30,
						formatString:series.GetXAxisFormat(),
						formatter:series.GetXAxisFormatter()
					}
					// tickInterval:'1 day',
					// min:series.GetXAxisMin()
				},
				yaxis:{
					// pad:1.05,
					tickOptions:{ formatString:series.GetYAxisFormatString(), formatter:series.GetTickFormatter()},
					min:0
				}
			},
            highlighter: {
                // sizeAdjust: 10,
                tooltipLocation: 'n',
                tooltipAxes: 'y',
                // tooltipFormatString: '%.2f',
                useAxesFormatters: false
            },
            cursor: {
                show: true
            }
		});
		plot.replot({resetAxes:true});
		chartIndicator.hide();
	};

	function Series() {
		this.Add = function (row) {
		};

		this.GetData = function () {
			return [];
		};

		this.GetLabels = function () {
			return [];
		};

		this.GetTickFormatter = function () {
			return $.jqplot.DefaultTickFormatter;
		};

		this.GetGraphRenderer = function () {
			return $.jqplot.BarRenderer;
		};

		this.GetXAxisRenderer = function () {
			return $.jqplot.CategoryAxisRenderer;
		};

		this.GetXAxisFormat = function () {
			return "%d";
		};

		this.GetXAxisFormatter = function () {
			return $.jqplot.DefaultTickFormatter;
		};

		this.GetXAxisMin = function () {
			return '';
		};

		this.GetYAxisFormatString = function() {
            return '%d';
        };

		this.GetLegendOptions = function () {
			return {
				show:false
			}
		};
	}

	function TotalSeries() {
		this.series = [];
		this.labels = [];

		this.Add = function (row) {
			var itemLabel = row.find('td[chart-column-type="label"]').text();
			var val = parseInt(row.find('td[chart-column-type="total"]').attr("chart-value"));
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
			return TimeTickFormatter;
		};
	}

	TotalTimeSeries.prototype = new TotalSeries();

	function DateSeries(options) {
		this.labels = [];
		this.groups = [];
		this.min = null;
		this.first = true;

		this.Add = function (row) {
			var date = new Date(row.find('td[chart-column-type="date"]').attr('chart-value'));
			var groupCell = row.find('td[chart-group="r"],td[chart-group="a"]');
			var groupId = groupCell.attr('chart-value');
			var groupName = groupCell.text();
            var totalValue = row.find('td[chart-column-type="total"]').attr('chart-value');
			var total = _.isEmpty(totalValue) ? 1 : parseInt(totalValue);

			if (!this.groups[groupId]) {
				this.groups[groupId] = new this.GroupSeries(groupName, groupId);
			}
			this.groups[groupId].AddDate(date, total);

			if (this.first) {
				this.min = date;
				this.first = false;
			}
		};

		this.dataLoaded = false;
		this.GetData = function () {
			var data = [];
			if (!this.dataLoaded)
			{
				for (var group in this.groups) {
					data.push(this.groups[group].GetData());

					this.labels.push({label:this.groups[group].GetLabel()})
				}
				this.dataLoaded = true;
			}

			return data;
		};

		this.GetLabels = function () {
			if (this.labels.length <= 0) {
				for (var group in this.groups) {
					this.labels.push({label:this.groups[group].GetLabel()})
				}
			}

			return this.labels;
		};

		this.GetLegendOptions = function () {
			return {
				show:true,
				placement:'outsideGrid',
				fontSize:'10pt'
			}
		};

		this.GroupSeries = function (label, groupId) {
			var groupLabel = label;
			var series = [];
			var id = groupId;

			this.AddDate = function (date, count) {
				if (count === '' || count === undefined)
				{
					count = 0;
				}
				if (series[date]) {
					series[date] += count;
				}
				else {
					series[date] = count;
				}
			};

			this.GetLabel = function () {
				return groupLabel;
			};

			this.GetData = function () {
				var data = [];
				for (var date in series) {
					data.push([date, series[date]])
				}
				return data;
			};

			this.GetId = function () {
				return id;
			};
		};

        this.GetXAxisRenderer = function () {
            return $.jqplot.DateAxisRenderer;
        };

        this.GetXAxisFormat = function() {
            return options.dateAxisFormat;
        };

        this.GetGraphRenderer = function () {
            return $.jqplot.LineRenderer;
        };
    }

	DateSeries.prototype = new Series();
}
