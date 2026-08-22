{if isset($UseLocalJquery) && $UseLocalJquery}
    {vendor_js src="lodash/4.6.13/js/lodash.4.6.13.min.js"}
    {vendor_js src="moment/2.13.0/js/moment.min.js"}
    {vendor_js src="jquery-form/3.09/jquery.form-3.09.min.js"}
    {vendor_js src="flatpickr/4.6.13/js/flatpickr.min.js"}
    {if isset($Validator) && $Validator}
        {vendor_js src="bootstrap-validator/0.6.0/js/bootstrapValidator.min.js"}
    {/if}
    {if isset($Trumbowyg) && $Trumbowyg}
        {vendor_js src="jquery-resizable/0.35/js/jquery-resizable.min.js"}
        {vendor_js src="dompurify/2.4.0/js/purify.min.js"}
        {vendor_js src="trumbowyg/2.27.3/js/trumbowyg.min.js"}
        {vendor_js src="trumbowyg/2.27.3/js/plugins/resizimg/trumbowyg.resizimg.min.js"}
    {/if}
    {if isset($DataTable) && $DataTable}
        {vendor_js src="datatables/3.0.1/js/dataTables.min.js"}
        {vendor_js src="datatables/3.0.1/js/dataTables.bootstrap5.min.js"}
        {vendor_js src="datatables-responsive/4.0.1/js/dataTables.responsive.min.js"}
        {vendor_js src="datatables-buttons/4.0.1/js/dataTables.buttons.min.js"}
        {vendor_js src="datatables-buttons/4.0.1/js/buttons.bootstrap5.min.js"}
        {vendor_js src="jszip/3.10.1/js/jszip.min.js"}
        {vendor_js src="pdfmake/0.2.7/js/pdfmake.min.js"}
        {vendor_js src="pdfmake/0.2.7/js/vfs_fonts.js"}
    {/if}
{else}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/lodash/4.16.3/lodash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.50/jquery.form.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"
        integrity="sha384-5JqMv4L/Xa0hfvtF06qboNdhvuYXUku9ZrhZh3bSk8VXF0A/RuSLHpLsSV9Zqhl6" crossorigin="anonymous">
    </script>
    {if isset($Validator) && $Validator}
        <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
    {/if}
    {if isset($Trumbowyg) && $Trumbowyg}
        <script src="//rawcdn.githack.com/RickStrahl/jquery-resizable/0.35/dist/jquery-resizable.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.4.0/purify.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/plugins/resizimg/trumbowyg.resizimg.min.js">
        </script>
    {/if}
    {if isset($DataTable) && $DataTable}
        <script src="https://cdn.datatables.net/3.0.1/js/dataTables.min.js" integrity="sha384-stLBvQI26SIgZOke0kAZbmVfcxb89mRjkgJ/aRVpYms4krlKaYvhLnDow4tbqS4U" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/3.0.1/js/dataTables.bootstrap5.min.js" integrity="sha384-4d8X9sr6Gnv9AgIQn6bv3lmQxj5fD+9bVAun0/XMmdy7oPRvT0adfiUUiiYpi4Ck" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/responsive/4.0.1/js/dataTables.responsive.min.js" integrity="sha384-C0VOmWDEHSWlpFIv8zJotfCUs4Ejf8BYURTNmgfGzfRq7nrdQXO9Z0VcyrQ5GMGn" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/buttons/4.0.1/js/dataTables.buttons.min.js" integrity="sha384-lnmZrBuLlzWz9qGhfn0/0MTMN7gEMk3UMg4XlCGPvLGQtwHsP+6EXEMUQrwrF9a2" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/buttons/4.0.1/js/buttons.bootstrap5.min.js" integrity="sha384-yCE1DqXYAduX6eIFQKSAy0I/zqnRZpRsHxILgx3STQiVoO8OEBnmysybTp3DSZkk" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" integrity="sha384-+mbV2IY1Zk/X1p/nWllGySJSUN8uMs+gUAN10Or95UBH0fpj6GfKgPmgC5EXieXG" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
    {/if}
{/if}
{if isset($InlineEdit) && $InlineEdit}
    {*The version of X-editable that supports Bootstrap 5 does not have a CDN link*}
    {vendor_js src="x-editable/1.5.1/js/bootstrap-editable.js"}
{/if}
{if isset($Select2) && $Select2}
    {if isset($UseLocalJquery) && $UseLocalJquery}
        {vendor_js src="select2/4.1.0/js/select2.min.js"}
    {else}
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    {/if}
{/if}
{if isset($Fullcalendar) && $Fullcalendar}
    {vendor_js src="fullcalendar/6.1.20/js/index.global.min.js"}
    {if $HtmlLang != 'en'}
        {vendor_js src="fullcalendar/6.1.20/js/locales-all.global.min.js"}
    {/if}
{/if}

{jsfile src="phpscheduleit.js"}
