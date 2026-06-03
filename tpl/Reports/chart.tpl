<div class="clear"></div>
<div class="modal" id="approveDiv" tabindex="-1" role="dialog" aria-labelledby="approveDivLabel"
	data-bs-backdrop="static" aria-hidden="true">
	{include file="wait-box.tpl" translateKey='Working'}
</div>

<div id="chartdiv" class="card shadow w-100 my-2 p-3" style="display:none;">
	<div id="chart-actions" class="d-none justify-content-end gap-2 mb-3">
		<button type="button" class="btn btn-outline-primary btn-sm" id="btnDownloadChartImage">
			<i class="bi bi-image me-1"></i>{translate key=Export} PNG
		</button>
	</div>
</div>
{vendor_js src="chart.js/4.4.9/js/chart.umd.min.js"}
{vendor_js src="chartjs-plugin-datalabels/2.2.0/js/chartjs-plugin-datalabels.min.js"}
