<div class="clear"></div>
<div class="modal" id="approveDiv" tabindex="-1" role="dialog" aria-labelledby="approveDivLabel"
	data-bs-backdrop="static" aria-hidden="true">
	{include file="wait-box.tpl" translateKey='Working'}
</div>

<div id="chartdiv" class="card shadow w-100 my-2" style="display:none;height:400px"></div>

<!--[if lt IE 9]>{vendor_js src="jqplot/1.0.9/excanvas.js"}<![endif]-->
{vendor_js src="jqplot/1.0.9/jquery.jqplot.min.js"}
{vendor_js src="jqplot/1.0.9/plugins/jqplot.barRenderer.min.js"}
{vendor_js src="jqplot/1.0.9/plugins/jqplot.categoryAxisRenderer.min.js"}
{vendor_js src="jqplot/1.0.9/plugins/jqplot.canvasAxisTickRenderer.min.js"}
{vendor_js src="jqplot/1.0.9/plugins/jqplot.canvasTextRenderer.min.js"}
{vendor_js src="jqplot/1.0.9/plugins/jqplot.pointLabels.min.js"}
{vendor_js src="jqplot/1.0.9/plugins/jqplot.dateAxisRenderer.min.js"}