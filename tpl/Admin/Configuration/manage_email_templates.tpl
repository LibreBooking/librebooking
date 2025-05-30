{include file='globalheader.tpl'}

<div id="page-manage-email-templates" class="admin-page">

    <div class="card shadow col-12 col-sm-8 mx-auto">
        <div class="card-body row">
            <h1 class="border-bottom mb-3">{translate key=ManageEmailTemplates}</h1>

            <div class="form-group col-sm-8 col-6 mb-3">
                <select id="templateOpts" title="{translate key=EmailTemplate}" class="form-select">
                    <option value="">--- {translate key=SelectEmailTemplate} ---</option>
                    {foreach from=$Templates item=template}
                        <option value="{$template->FileName()}">{$template->Name()}</option>
                    {/foreach}
                </select>

            </div>

            <div class="form-group col-sm-4 col-6">
                <select id="languageOpts" title="{translate key=Language}" class="form-select">
                    {foreach from=$Languages item=language}
                        <option value="{$language->LanguageCode}" {if $Language==$language->LanguageCode}selected="selected"
                            {/if}>{$language->DisplayName}</option>
                    {/foreach}
                </select>
            </div>

            <div id="editEmailSection" class="d-none">
                <div class="mb-2">
                    <form role="form" id="updateEmailForm" ajaxAction="{EmailTemplatesActions::Update}" method="post">
                        <div class="form-group">
                            <textarea id="templateContents" {formname key=EMAIL_CONTENTS}
                                title="{translate key=EmailTemplate}" class="form-control mb-2" rows="20"
                                style="width:100%"></textarea>
                        </div>

                        <div class="form-group">
                            {indicator}
                            {update_button submit=true}
                            <input id="reloadEmailContents" type="button" class="btn btn-outline-secondary"
                                value="{translate key=ReloadOriginalContents}" />
                        </div>

                        <input type="hidden" id="templatePath" {formname key=EMAIL_TEMPLATE_NAME} />
                        <input type="hidden" id="selectedLanguage" {formname key=EMAIL_TEMPLATE_LANGUAGE} />
                        {csrf_token}
                    </form>
                </div>

                <div id="updateSuccess" class="alert alert-success d-none">
                    <i class="bi bi-check-circle me-1"></i> {translate key=UpdateEmailTemplateSuccess}
                </div>

                <div id="updateFailed" class="alert alert-warning d-none">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>{translate key=UpdateEmailTemplateFailure}
                </div>
            </div>

            {include file="javascript-includes.tpl"}

            {jsfile src="ajax-helpers.js"}
            {jsfile src="admin/email-templates.js"}
            <script type="text/javascript">
                $(document).ready(function() {
                    var opts = {
                        scriptUrl: '{$smarty.server.SCRIPT_NAME}'
                    };
                    var emails = new EmailTemplateManagement(opts);
                    emails.init();
                });
            </script>
            <div id="wait-box" class="wait-box">
                {include file="wait-box.tpl" translateKey='Working'}
            </div>
        </div>
    </div>
</div>

{include file='globalfooter.tpl'}