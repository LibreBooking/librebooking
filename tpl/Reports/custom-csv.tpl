{strip}
{* {strip} joins these lines; {linebreak} emits the row separators. *}
{assign var=isFirstCell value=true}
{foreach from=$Definition->GetColumnHeaders() item=column name=columnIterator}
    {if $ReportCsvColumnView->ShouldShowCol($column, $smarty.foreach.columnIterator.index)}
        {capture assign=columnTitle}
            {if $column->HasTitle()}
                {$column->Title()}
            {else}
                {translate key=$column->TitleKey()}
            {/if}
        {/capture}
        {if !$isFirstCell},{/if}
        "{$columnTitle|escape_csv}"
        {assign var=isFirstCell value=false}
    {/if}
{/foreach}
{linebreak}
{foreach from=$Report->GetData()->Rows() item=row}
    {assign var=isFirstCell value=true}
    {foreach from=$Definition->GetRow($row) item=data name=dataIterator}
        {if $ReportCsvColumnView->ShouldShowCell($smarty.foreach.dataIterator.index)}
            {if !$isFirstCell},{/if}
            "{$data->Value()|escape_csv}"
            {assign var=isFirstCell value=false}
        {/if}
    {/foreach}
    {linebreak}
{/foreach}
{/strip}
