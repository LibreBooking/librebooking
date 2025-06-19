{include file='globalheader.tpl' DataTable=true}

<div id="page-admin-credit-log" class="admin-page">
    <h1 class="border-bottom mb-3">{translate key=CreditHistory} - {$UserName}</h1>

    {if $ShowError}
        <div class="alert alert-danger">
            {translate key=UserNotFound}
        </div>
    {else}
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

    {/if}

</div>

{include file="javascript-includes.tpl" DataTable=true}
{datatable tableId=$tableId}

{include file='globalfooter.tpl'}