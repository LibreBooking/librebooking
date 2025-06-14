<div class="card shadow">
	<div class="card-body">
		{if $Report->ResultCount() > 0}
			<div id="report-actions" class="text-center">
				<div class="btn-group btn-group-sm">
					<a href="#" class="btn btn-primary" id="btnChart"><i
							class="bi bi-graph-up me-1"></i>{translate key=ViewAsChart}</a>
					{if !$HideSave}
						<a href="#" class="btn btn-primary" id="btnSaveReportPrompt" data-bs-toggle="modal"
							data-bs-target="#saveDialog"><i class="bi bi-floppy me-1"></i>{translate key=SaveThisReport}</a>
					{/if}
				</div>
			</div>
			<div class="table-responsive mt-3">
				{assign var=tableId value="report-results"}
				<table id="{$tableId}" chart-type="{$Definition->GetChartType()}"
					class="table table-striped table-hover border-top w-100">
					<thead>
						<tr>
							{foreach from=$Definition->GetColumnHeaders() item=column}
								{capture name="columnTitle"}
									{if $column->HasTitle()}{$column->Title()}
									{else}
										{translate key=$column->TitleKey()}
									{/if}
								{/capture}
								<th data-columnTitle="{$smarty.capture.columnTitle}">
									{$smarty.capture.columnTitle}
								</th>
							{/foreach}
						</tr>
					</thead>
					<tbody>
						{foreach from=$Report->GetData()->Rows() item=row}
							<tr>
								{foreach from=$Definition->GetRow($row) item=cell}
									<td chart-value="{$cell->ChartValue()}" chart-column-type="{$cell->GetChartColumnType()}"
										chart-group="{$cell->GetChartGroup()}">{$cell->Value()}</td>
								{/foreach}
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
			{datatable tableId=$tableId}
			<h4>{$Report->ResultCount()} {translate key=Rows}
				{if $Definition->GetTotal() != ''}
					| {$Definition->GetTotal()} {translate key=Total}
				{/if}
			</h4>
		{else}
			<h2 id="report-no-data" class="no-data fs-2 fs-italic text-center">{translate key=NoResultsFound}</h2>
		{/if}
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#report-no-data, #report-results').trigger('loaded');
	});
</script>