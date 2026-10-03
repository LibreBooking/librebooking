{strip}
{* {strip} joins these lines; {linebreak} emits the row separators. *}
{foreach from=$Definition->GetColumnHeaders() item=column name=columnIterator}
    {if $ReportCsvColumnView->ShouldShowCol($column, $smarty.foreach.columnIterator.index)}
        "
        {if $column->HasTitle()}
            {$column->Title()}
        {else}
            {translate key=$column->TitleKey()}
        {/if}
        ",
    {/if}
{/foreach}
{linebreak}
{foreach from=$Report->GetData()->Rows() item=row}
    {foreach from=$Definition->GetRow($row) item=data name=dataIterator}
        {if $ReportCsvColumnView->ShouldShowCell($smarty.foreach.dataIterator.index)}
            "{$data->Value()|escape}",
        {/if}
    {/foreach}
    {linebreak}
{/foreach}
{/strip}
