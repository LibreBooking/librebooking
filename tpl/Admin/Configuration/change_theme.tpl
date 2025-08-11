{include file='globalheader.tpl' }

<div id="page-change-theme" class="admin-page">
    <div class="card shadow col-12 col-sm-8 mx-auto">
        <div class="card-body">
            <h1 class="border-bottom mb-3">{translate key=LookAndFeel}</h1>

            <div id="successMessage" class="alert alert-success d-none">
                {translate key=ThemeUploadSuccess}
            </div>

            <form id="elementForm" action="{$smarty.server.SCRIPT_NAME}" ajaxAction="update" method="post"
                enctype="multipart/form-data">
                <div class="validationSummary alert alert-danger d-none" id="validationErrors">
                    <ul>
                        {async_validator id="logoFileExt"}
                        {async_validator id="cssFileExt"}
                        {async_validator id="faviconFileExt"}
                        {async_validator id="logoFile"}
                        {async_validator id="cssFile"}
                        {async_validator id="faviconFile"}
                    </ul>
                </div>

                <ul class="list-group mb-2">

                    <li class="list-group-item">
                        <h4>{translate key="Logo"} (*.png, *.gif, *.jpg - Recommended height 75px)</h4>
                        <img src="{$ScriptUrl}/img/{$LogoUrl}" class="d-block mx-auto mw-100" />
                        <div class="d-flex align-items-center justify-content-center my-2">
                            <a href="{$ScriptUrl}/img/{$LogoUrl}" download="{$ScriptUrl}/img/{$LogoUrl}"
                                class="link-primary"><i class="bi bi-download me-1"></i>{$LogoUrl}</a>
                            <div class="vr mx-1"></div>
                            <a href="#" id="removeLogo" class="link-danger text-decoration-none"><i
                                    class="bi bi-trash3-fill me-1"></i>{translate key=Remove}</a>
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="file" {formname key=LOGO_FILE} class="form-control" id="logoFile"
                                accept=".png, .gif, .jpg, .jpeg" />
                            <a href="#" class="clearInput inline input-group-text"><span
                                    class="visually-hidden">{translate key=Delete}</span><i
                                    class="bi bi-x-square-fill text-danger"></i></a>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <h4>Favicon (*.ico, *.png, *.gif, *.jpg - Recommended size 48px x 48px)</h4>
                        <img src="{$ScriptUrl}/{$FaviconUrl}" class="d-block mx-auto" />
                        <div class="d-flex align-items-center justify-content-center my-2">
                            <a href="{$ScriptUrl}/{$FaviconUrl}" download="{$ScriptUrl}/img/{$FaviconUrl}"
                                class="link-primary"><i class="bi bi-download me-1"></i>{$FaviconUrl}</a>
                            <div class="vr mx-1"></div>
                            <a href="#" id="removeFavicon" class="link-danger text-decoration-none"><i
                                    class="bi bi-trash3-fill me-1"></i>{translate key=Remove}</a>
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="file" {formname key=FAVICON_FILE} class="form-control" id="faviconFile"
                                accept=".png, .gif, .jpg, .jpeg, .ico" />
                            <a href="#" class="clearInput inline input-group-text"><span
                                    class="visually-hidden">{translate key=Delete}</span><i
                                    class="bi bi-x-square-fill text-danger"></i></a>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div>
                            <h4>{translate key="CssFile"} (*.css)</h4>
                            <div class="d-flex align-items-center justify-content-center my-2">
                                <a href="{$ScriptUrl}/css/{$CssUrl}" download="{$ScriptUrl}/css/{$CssUrl}"
                                    class="link-primary"><i class="bi bi-download me-1"></i>{$CssUrl}</a>
                                <div class="vr mx-1"></div>
                                <a href="#" id="removeCss" class="link-danger text-decoration-none"><i
                                        class="bi bi-trash3-fill me-1"></i>{translate key=Remove}</a>
                            </div>
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="file" {formname key=CSS_FILE} class="form-control" id="cssFile"
                                accept=".css" />
                            <a href="#" class="clearInput input-group-text"><span
                                    class="visually-hidden">{translate key=Delete}</span><i
                                    class="bi bi-x-square-fill text-danger"></i></a>
                        </div>
                    </li>

                </ul>

                <div class="d-grid">
                    <button type="button" class="btn btn-primary update" name="{Actions::SAVE}" id="saveButton">
                        {translate key='Update'}
                    </button>
                </div>

                {csrf_token}

            </form>
        </div>
    </div>

    <div class="modal" id="waitModal" tabindex="-1" role="dialog" aria-labelledby="waitModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        {include file="wait-box.tpl" translateKey='Working'}
    </div>

    {include file="javascript-includes.tpl"}

    {jsfile src="ajax-helpers.js"}
    {jsfile src="js/jquery.form-3.09.min.js"}
    {jsfile src="js/ajaxfileupload.js"}
    {jsfile src="ajax-form-submit.js"}

    <script type="text/javascript">
        $('document').ready(function() {

            $('#elementForm').bindAjaxSubmit($('#saveButton'), $('#successMessage'), $('#wait-box'));

            $('.clearInput').click(function(e) {
                e.preventDefault();
                $(this).prev('input').val('');
            });

            $('#removeLogo').click(function(e) {
                e.preventDefault();

                PerformAsyncAction($(this), function() {
                    return '{$smarty.server.SCRIPT_NAME}?action=removeLogo';
                });
            });

            $('#removeFavicon').click(function(e) {
                e.preventDefault();

                PerformAsyncAction($(this), function() {
                    return '{$smarty.server.SCRIPT_NAME}?action=removeFavicon';
                });
            });

            $('#removeCss').click(function(e) {
                e.preventDefault();

                PerformAsyncAction($(this), function() {
                    return '{$smarty.server.SCRIPT_NAME}?action=removeCss';
                });
            });
        });
    </script>

</div>
{include file='globalfooter.tpl'}