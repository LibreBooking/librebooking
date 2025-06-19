<div style="overflow-x:auto;">
    {assign var=tableId value='credit-log-list'}
    <table class="table table-striped table-hover border-top w-100" id="{$tableId}">
        <thead>
            <tr>
                <th>{translate key=Date}</th>
                <th>{translate key=Note}</th>
                <th>{translate key=CreditsBefore}</th>
                <th>{translate key=CreditsAfter}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$CreditLog item=log}
                <tr>
                    <td>{formatdate date=$log->Date timezone=$Timezone key='general_datetime'}</td>
                    <td>{$log->Note}</td>
                    <td>{$log->OriginalCreditCount}</td>
                    <td>{$log->CreditCount}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>
{datatable tableId={$tableId}}