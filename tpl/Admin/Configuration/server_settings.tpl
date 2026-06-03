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
