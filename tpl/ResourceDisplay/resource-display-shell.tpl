{include file='globalheader.tpl' HideNavBar=true cssFiles='css/schedule.css' DatePicker=true}

<div id="page-resource-display-resource">

        <div class="resource-display date-picker ms-4">
                <div class="date form-group w-50">
                        <label class="fw-bold text-uppercase fs-6 text-secondary"
                                for="availabilityStartDate">{translate key='Date'}</label>
                        <input type="date" id="availabilityStartDate" class="form-control w-auto hasDatepicker" />
                        <input type="hidden" id="formattedBeginDate" />
                </div>
        </div>

        <div id="placeholder"></div>
</div>

<div class="modal" id="wait-modal" tabindex="-1" role="dialog" aria-labelledby="waitModalLabel" data-bs-backdrop="static"
        aria-hidden="true">
        {include file="wait-box.tpl" translateKey='Working'}
</div>

{include file="javascript-includes.tpl" DatePicker=true}
{jsfile src="resourceDisplay.js"}
{jsfile src="ajax-helpers.js"}
{jsfile src="autocomplete.js"}

{control type="DatePickerSetupControl" 
ControlId="availabilityStartDate" 
AltId="formattedBeginDate"
MaxDate=$MaxFutureDate 
AltFormat=Resources::GetInstance()->GetDateFormat('schedule_daily')}

<script type="text/javascript">
        function getResourceId() {
                return '{$PublicResourceId}';
        }
        $(function() {
                var resourceDisplay = new ResourceDisplay();
                resourceDisplay.initDisplay({
                        url: '{$smarty.server.SCRIPT_NAME}?dr=resource&rid={$PublicResourceId}&dr=display',
                        userAutocompleteUrl: "ajax/autocomplete.php?type={AutoCompleteType::User}&as=1",
                        allowAutocomplete: {if $AllowAutocomplete}true{else}false{/if},
                        initialDate: '{$InitialDate}',
                        MaxDate: '{$MaxFutureDate}'
                });
        });
</script>

{include file='globalfooter.tpl'}