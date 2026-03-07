{include file='globalheader.tpl'}

<div id="page-import-quartzy" class="admin-page">

    <div class="card shadow col-12 col-sm-8 mx-auto">
        <div class="card-body">
            <h1 class="border-bottom mb-3">{translate key=ImportQuartzy}</h1>

            <div class="">
                <div>
                    <div class="alert alert-info fst-italic">
                        <div class="note">Export your Quartzy data <a
                                href="https://support.quartzy.com/hc/en-us/articles/214823208" target="_blank"
                                class="alert-link">following
                                these
                                instructions</a></div>
                        <div class="note">Users will imported with the password <span class="fw-bold">p@ssw0rd!</span>
                        </div>
                    </div>
                    <div class="alert alert-warning">Please do not make any changes to the Quartzy export file. Your
                        data cannot be imported if this file is altered in any way.
                    </div>
                </div>

                <div id="importErrors" class="alert alert-danger d-none"></div>
                <div id="importResult" class="alert alert-success d-none">
                    <span>{translate key=RowsImported}</span>

                    <div id="importCount" class="fw-bold"></div>
                    <span>{translate key=RowsSkipped}</span>

                    <div id="importSkipped" class="fw-bold"></div>
                    <a href="{$smarty.server.SCRIPT_NAME}">{translate key=Done}</a>
                </div>

                <form id="quartzyImportForm" method="post" enctype="multipart/form-data" ajaxAction="importQuartzy">
                    <div class="validationSummary alert alert-danger d-none">
                        <ul>
                            {async_validator id="fileExtensionValidator" key=""}
                            {async_validator id="importQuartzyValidator" key=""}
                        </ul>
                    </div>

                    <div>
                        <label for="importFile" class="visually-hidden">Import File</label>
                        <input type="file" name="quartzyFile" id="importFile" class="form-control" accept=".zip" />
                    </div>

                    <div class="form-check">
                        <label class="form-check-label fw-bold" for="includeBookings">Include Bookings</label>
                        <span>(this can take up to 20 minutes)</span>
                        <input class="form-check-input" type="checkbox" id="includeBookings" name="includeBookings" />

                    </div>

                    <div class="admin-update-buttons">
                        <button id="btnUpload" type="button" class="btn btn-primary save"><i class="bi bi-upload"></i>
                            {translate key=Import}</button>
                        {indicator}
                    </div>
                    {csrf_token}
                </form>
            </div>

        </div>
    </div>
</div>


{include file="javascript-includes.tpl"}
{jsfile src="ajax-helpers.js"}
{vendor_js src="jquery-form/3.09/jquery.form-3.09.min.js"}

<script type="text/javascript">
    $(document).ready(function() {

        var importForm = $('#quartzyImportForm');

        var defaultSubmitCallback = function(form) {
            return function() {
                return '{$smarty.server.SCRIPT_NAME}?action=' + form.attr('ajaxAction');
            };
        };

        var importHandler = function(responseText, form) {
            if (!responseText) {
                return;
            }

            $('#importCount').text(responseText.importCount);
            $('#importSkipped').text(responseText.skippedRows.length > 0 ? responseText.skippedRows.join(
                ',') : '-');
            $('#importResult').removeClass('d-none');

            var errors = $('#importErrors');
            errors.empty();
            if (responseText.messages && responseText.messages.length > 0) {
                var messages = responseText.messages.join('</li><li>');
                errors.html('<div>' + messages + '</div>').removeClass('d-none');
            }
        };

        $('#btnUpload').click(function(e) {
            e.preventDefault();
            importForm.submit();
        });

        ConfigureAsyncForm(importForm, defaultSubmitCallback(importForm), importHandler);
    });
</script>

{include file='globalfooter.tpl'}