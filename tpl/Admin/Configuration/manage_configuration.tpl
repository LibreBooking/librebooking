{include file='globalheader.tpl'}

<div id="page-manage-configuration" class="admin-page">

    <h1 class="border-bottom mb-3">{translate key=ManageConfiguration}</h1>

    {if $ShowScriptUrlWarning}
        <div class="alert alert-danger">
            {translate key=ScriptUrlWarning args="$CurrentScriptUrl,$SuggestedScriptUrl"}
        </div>
    {/if}

    {if $IsEnvPresent}
        <div class="alert alert-warning">
            {translate key=ConfigurationEnvWarning}
        </div>
    {/if}

    <form id="frmConfigFile" method="GET" action="{$SCRIPT_NAME}" role="form">
        <div class="form-group mb-2">
            <label for="cf" class="fw-bold">{translate key=File}</label>
            <select name="cf" id="cf" class="form-select">
                {foreach from=$ConfigFiles item=file}
                    {assign var=selected value=""}
                    {if $file->Location eq $SelectedFile}{assign var=selected value="selected='selected'"}{/if}
                    <option value="{$file->Location}" {$selected}>{$file->Name}</option>
                {/foreach}
            </select>
        </div>
    </form>

    {function name="list_settings"}
        <div class="row gy-4 mb-3">
            {foreach from=$settings item=setting}
                {assign value='col-sm-6' var=rowCss}
                {assign var="name" value=$setting->Name}
                {assign var="disabled" value=$setting->hasEnv ? 'disabled' : ''}
                <div class="{$rowCss}">
                    <div class="form-group">
                        <label for="{$name}" class="fw-bold" data-bs-toggle="tooltip" title="{$setting->Description}">{$setting->Label} <span class="fw-normal">({$setting->Key})</span></label>
                        {if $setting->Key == $DefaultTimezoneKey}
                            <select id="{$name}" name="{$name}" {$disabled} class="form-select">
                                {html_options values=$TimezoneValues output=$TimezoneOutput selected=$setting->Value}
                            </select>
                        {elseif $setting->Key == $DefaultLanguageKey}
                            <select id="{$name}" name="{$name}" {$disabled} class="form-select">
                                {object_html_options options=$Languages key='GetLanguageCode' label='GetDisplayName' selected=$setting->Value|strtolower}
                            </select>
                        {elseif $setting->Key == $DefaultHomepageKey}
                            <select id="default__homepage" name="{$name}" {$disabled} class="form-select">
                                {html_options options=$setting->Choices  selected=$setting->Value}
                            </select> <a href="#" id="applyHomepage" class="link-primary">{translate key=ApplyToCurrentUsers}</a>
                        {elseif $setting->IsPrivate}
                            <input id="{$name}" type="password" size="50" name="{$name}" {$disabled} value="{$setting->Value|escape}" class="form-control" />
                        {elseif is_array($setting->Choices)}
                            <select id="{$name}" name="{$name}" class="form-select">
                                {html_options options=$setting->Choices  selected=$setting->Value}
                            </select>
                        {elseif $setting->Type == ConfigSettingType::String}
                            <input id="{$name}" type="text" size="50" name="{$name}" {$disabled} value="{$setting->Value|escape}" class="form-control" />
                        {elseif $setting->Type == ConfigSettingType::Integer}
                            <input id="{$name}" type="number" size="50" name="{$name}" {$disabled} value="{$setting->Value|escape}" class="form-control" />
                        {else}
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="radio{$name}t" type="radio" value="true" name="{$name}" {$disabled} {if $setting->Value} checked="checked" {/if} />
                                    <label class="form-check-label" for="radio{$name}t">{translate key="True"}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="radio{$name}f" type="radio" value="false" name="{$name}" {$disabled} {if !$setting->Value} checked="checked" {/if} />
                                    <label class="form-check-label" for="radio{$name}f">{translate key="False"}</label>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            {/foreach}
        </div>
    {/function}


    {if !$IsPageEnabled}
        <div class="alert alert-danger">
            {translate key=ConfigurationUiNotEnabled}
        </div>
    {/if}

    {if !$IsConfigFileWritable}
        <div class="alert alert-danger">
            {translate key=ConfigurationFileNotWritable}
        </div>
    {/if}

    {if $IsPageEnabled && $IsConfigFileWritable}
        <div class="card shadow">
            <div class="card-body">
                {assign var=HelpUrl value="https://github.com/LibreBooking/app/wiki/Administration"}
                <h3 class="text-center border-bottom mb-3">{translate key=ConfigurationUpdateHelp args=$HelpUrl}</h3>
                <div id="updatedMessage" class="alert alert-success" style="display:none;">
                    {translate key=ConfigurationUpdated}
                </div>
                <div id="configSettings">
                    <div class="d-grid">
                        <input type="button" value="{translate key=Update}" class='btn btn-primary save' />
                    </div>

                    <form id="frmConfigSettings" method="post" ajaxAction="{ConfigActions::Update}" action="{$smarty.server.SCRIPT_NAME}">
                        <div class="accordion my-3" id="accordionConfig">
                            {if !empty($Settings)}
                                <div>
                                    <div class="accordion-item shadow mb-2">
                                        <h2 class="accordion-header text-capitalize">
                                            <button class="accordion-button text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#{translate key=GeneralConfigSettings}">
                                                {translate key=GeneralConfigSettings}
                                            </button>
                                        </h2>
                                        <div id="{translate key=GeneralConfigSettings}" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <fieldset>
                                                    <div class="no-style config-settings">
                                                        {list_settings settings=$Settings}
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            
                            {foreach from=$SectionSettings key=section item=settings}
                                <div>
                                    <div class="accordion-item shadow mb-2">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#{$section}">
                                                {$section}
                                            </button>
                                        </h2>
                                        <div id="{$section}" class="accordion-collapse collapse">
                                            <div class="accordion-body">
                                                <fieldset>
                                                    <div class="no-style config-settings">
                                                        {list_settings settings=$settings}
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                        <input type="hidden" name="setting_names" value="{$SettingNames}" />
                    </form>

                    <div class="d-grid">
                        <input type="button" value="{translate key=Update}" class='btn btn-primary save' />
                    </div>

                </div>

                <form id="updateHomepageForm" method="post" ajaxAction="{ConfigActions::SetHomepage}" action="{$smarty.server.SCRIPT_NAME}">
                    <input type="hidden" name="homepage_id" id="homepage_id" />
                </form>

                {csrf_token}
            </div>
        </div>

        {include file="javascript-includes.tpl"}

        {jsfile src="ajax-helpers.js"}
        {jsfile src="js/jquery.form-3.09.min.js"}
        {jsfile src="admin/configuration.js"}
        <script type="text/javascript">
            $(document).ready(function() {
                var config = new Configuration();
                config.init();
            });
        </script>
        <div class="modal" id="waitModal" tabindex="-1" role="dialog" aria-labelledby="waitModalLabel" data-bs-backdrop="static" aria-hidden="true">
            {include file="wait-box.tpl" translateKey='Working'}
        </div>
    {/if}
</div>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
{include file='globalfooter.tpl'}