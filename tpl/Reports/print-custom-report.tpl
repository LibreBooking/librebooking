<!DOCTYPE HTML>
<html lang="{$HtmlLang}" dir="{$HtmlTextDirection}">

<head>
	<title>{if $TitleKey neq ''}{translate key=$TitleKey args=$TitleArgs}{else}{$Title}{/if}</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$Charset}" />
	{vendor_js src="jquery/3.3.1/jquery-3.3.1.min.js"}
</head>

<body>
	{translate key=Created}: {format_date date=Date::Now() key=general_datetime}
	<table width="100%" border="1">
		<tr>
			{foreach from=$Definition->GetColumnHeaders() item=column name=columnIterator}
				{if $ReportCsvColumnView->ShouldShowCol($column, $smarty.foreach.columnIterator.index)}
					{capture name="columnTitle"}
						{if $column->HasTitle()}{$column->Title()}
						{else}
							{translate key=$column->TitleKey()}
						{/if}
					{/capture}
					<th data-columnTitle="{$smarty.capture.columnTitle}">
						{$smarty.capture.columnTitle}
					</th>
				{/if}
			{/foreach}
		</tr>
		{foreach from=$Report->GetData()->Rows() item=row}
			<tr>
				{foreach from=$Definition->GetRow($row) item=data name=dataIterator}
					{if $ReportCsvColumnView->ShouldShowCell($smarty.foreach.dataIterator.index)}
						<td>{$data->Value()|escape}&nbsp;</td>
					{/if}
				{/foreach}
			</tr>
		{/foreach}
	</table>
	{$Report->ResultCount()} {translate key=Rows}
	{if $Definition->GetTotal() != ''}
		| {$Definition->GetTotal()} {translate key=Total}
	{/if}

	{jsfile src="reports/common.js"}

	<script type="text/javascript">
		var common = new ReportsCommon({
			scriptUrl: '{$ScriptUrl}'
		});
		common.init();
		window.print();
	</script>

</body>

</html>