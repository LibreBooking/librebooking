{include file='globalheader.tpl'}

<div id="page-import-ics" class="admin-page">

    <div class="card shadow col-12 col-sm-8 mx-auto">
        <div class="card-body">
            <h1 class="border-bottom mb-3">{translate key=ImportICS}</h1>

            <div>
                <div class="alert alert-info fst-italic">
                    <div class="note">{translate key=OnlyIcs}</div>
                    <div class="note">{translate key=IcsLocationsAsResources}</div>
                    <div class="note">{translate key=IcsMissingOrganizer}</div>
                </div>
                <div class="alert alert-warning">{translate key=IcsWarning}</div>
            </div>

            <div class="">

                <div id="importErrors" class="alert alert-danger d-none"></div>
                <div id="importResult" class="alert alert-success d-none">
                    <span>{translate key=RowsImported}</span>

                    <span id="importCount" class="fw-bold"></span>
                    <span>{translate key=RowsSkipped}</span>

                    <span id="importSkipped" class="fw-bold"></span>
                    <a href="{$smarty.server.SCRIPT_NAME}" class="alert-link">{translate key=Done}</a>
                </div>
                <form id="icsImportForm" method="post" enctype="multipart/form-data" ajaxAction="importIcs">
                    <div class="validationSummary alert alert-danger d-none">
                        <ul>
                            {async_validator id="fileExtensionValidator" key=""}
                            {async_validator id="importQuartzyValidator" key=""}
                        </ul>
                    </div>
                    <div class="mb-2">
                        <label for="importFile" class="visually-hidden">Import File</label>
                        <input type="file" {formname key=ICS_IMPORT_FILE} id="importFile" class="form-control"
                            accept=".ics" />
                    </div>

                    <div class="admin-update-buttons ">
                        <button id="btnUpload" type="button" class="btn btn-primary save"><i class="bi bi-upload"></i>
                            {translate key=Import}</button>
                        {indicator}
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
{csrf_token}

{include file="javascript-includes.tpl"}
{jsfile src="ajax-helpers.js"}
{jsfile src="js/jquery.form-3.09.min.js"}

<script type="text/javascript">
    $(document).ready(function() {

        var importForm = $('#icsImportForm');

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
            $('#importSkipped').text(responseText.skippedRows);
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