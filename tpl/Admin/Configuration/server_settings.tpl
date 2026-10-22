{include file='globalheader.tpl'}

<div id="server-settings-page" class="admin-page">
    <div class="card shadow col-12 col-sm-8 mx-auto">
        <div class="card-body">
            <h1 class="border-bottom mb-3">{translate key=ServerSettings}</h1>

            <ul class="indented">
                <li>Current Time: {$currentTime}</li>
                <li>Image Upload Physical Directory: {$imageUploadDirectory} ({translate key=Permissions}
                    : {$imageUploadDirPermissions}) <a
                        href="{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}=changePermissions"
                        class="link-primary">Try to apply
                        correct permissions</a></li>
                <li>Template Cache Directory: {$templateCacheDirectory} <a
                        href="{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACTION}=flush" class="link-primary">Try to
                        flush cached
                        files</a>
                </li>
            </ul>

            <h2 class="mt-4 fs-4">Versions</h2>
            <div class="table-responsive">
                <table id="server-versions" class="table table-sm table-striped">
                    <tbody>
                        <tr><th scope="row">LibreBooking</th><td>{$appVersion}</td></tr>
                        <tr><th scope="row">Database Schema</th><td>{$databaseSchemaVersion|escape}</td></tr>
                        <tr><th scope="row">Database Server</th><td>{$databaseServerVersion|escape}</td></tr>
                        <tr><th scope="row">Smarty</th><td>{$smartyVersion}</td></tr>
                    </tbody>
                </table>
            </div>

            <h2 class="mt-4 fs-4">PHP</h2>
            <div class="table-responsive">
                <table id="server-php-info" class="table table-sm table-striped">
                    <tbody>
                        {foreach from=$phpInfo key=label item=value}
                            <tr><th scope="row">{$label}</th><td>{$value|escape}</td></tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>

            <h2 class="mt-4 fs-4">PHP Settings</h2>
            <div class="table-responsive">
                <table id="server-ini-settings" class="table table-sm table-striped">
                    <tbody>
                        {foreach from=$iniSettings key=name item=value}
                            <tr><th scope="row"><code>{$name}</code></th><td>{$value|escape}</td></tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>

            <h2 class="mt-4 fs-4">Plugins</h2>
            <ul class="indented">
                {foreach from=$plugins key=category item=items}
                    <li>{$category}
                        <ul>
                            {foreach from=$items item=pluginName}
                                <li>{$pluginName}</li>
                            {/foreach}
                        </ul>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
</div>

{include file="javascript-includes.tpl"}
{include file='globalfooter.tpl'}
