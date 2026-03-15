function ReportsCommon(opts) {
  return {
    init: function () {
      $(document).on('click', '#btnChart', function (e) {
        e.preventDefault();
        $('#approveDiv').modal('show');

        // Use a small delay to ensure the modal is displayed sooner
        setTimeout(function () {
          try {
            var chart = new Chart(opts.chartOpts);
            chart.generate();
            $('#report-results').hide();
          } catch (error) {
            console.error('Unable to generate report chart', error);
          } finally {
            $('#approveDiv').modal('hide');
          }
        }, 500);
      });
      /*
                        $('body').click(function (e) {
                            if (!$(e.target).closest('#customize-columns').length && !$(e.target).closest('#btnCustomizeColumns').length) {
                                $('#customize-columns').hide();
                            }
                        });
            
                        function showColumn(title, show) {
                            var reportResults = $('#report-results');
                            var th = reportResults.find('th[data-columnTitle="' + title + '"]');
                            var allCells = th.closest('tr').children();
                            var normalIndex = allCells.index(th) + 1;
                            var colSelector = 'td:nth-child(' + normalIndex + ')';
                            var col = reportResults.find(colSelector);
            
                            if (show) {
                                th.show();
                                col.show();
                            }
                            else {
                                th.hide();
                                col.hide();
                            }
                        }
            
                        function initColumns(savedColumns) {
                            if (savedColumns.length == 0) {
                                return;
                            }
                            $.each(getAllColumnTitles(), function (i, title) {
                                if (savedColumns.length < 1) {
                                    showColumn(title, false);
                                }
                                else if ($.inArray(title, savedColumns) == -1) {
                                    showColumn(title, false);
                                }
                            });
                        }
            
                        function getAllColumnTitles() {
                            return $.map($('#report-results').find('th'), function (v) {
                                return $(v).attr('data-columnTitle');
                            });
                        }
            
                        function saveSelectedCols(selectedColumns) {
                            $('#selectedColumns').val(selectedColumns);
            
                            ajaxPost($('#saveSelectedColumns'), null, null, function () { });
                        }
            
                        $(document).on('loaded', '#report-results', function (e) {
                            $('#chartdiv').empty();
                            var separator = '!s!';
                            var selectedCols = $('#selectedColumns').val();
                            var savedCols = selectedCols ? selectedCols.split(separator) : [];
                            initColumns(savedCols);
            
                            var items = [];
                            var allColumns = getAllColumnTitles();
                            $.each(allColumns, function (i, title) {
                                var checked = savedCols.length == 0 || $.inArray(title, savedCols) != -1 ? ' checked="checked" ' : '';
                                items.push('<div><label><input type="checkbox"' + checked + 'value="' + title + '"/> ' + title + '</label></div>');
                            });
            
                            var customizeColumns = $('#customize-columns');
                            customizeColumns.empty();
                            $('<div/>', { 'class': '', html: items.join('') }).appendTo(customizeColumns);
            
                            var btnCustomizeColumns = $('#btnCustomizeColumns');
            
                            customizeColumns.find(':checkbox').unbind('click');
            
                            customizeColumns.on('click', ':checkbox', function (e) {
                                showColumn($(this).val(), $(this).is(':checked'));
            
                                var columnsToSave = $.map(customizeColumns.find(':checked'), function (checkbox) {
                                    return $(checkbox).val();
                                });
            
                                saveSelectedCols(columnsToSave.join(separator));
                            });
            
                            btnCustomizeColumns.unbind('click').on('click', function (e) {
                                e.preventDefault();
                                customizeColumns.position({ my: 'right top', at: 'right bottom', of: btnCustomizeColumns, collision: 'fit' });
                                customizeColumns.show();
                            });
                        });
            
                        $('.dialog .cancel').click(function (e) {
                            $(this).closest('.dialog').dialog("close");
                        });
                        */
    },
  };
}
