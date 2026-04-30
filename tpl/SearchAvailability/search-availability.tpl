{include file='globalheader.tpl' Select2=true}

<div class="page-search-availability">
    <div class="card shadow mb-3">
        <div class="card-body">
            <h1 class="text-center border-bottom mb-2">{translate key="FindATime"}</h1>
            <form role="form" name="searchForm" id="searchForm" method="post"
                action="{$smarty.server.SCRIPT_NAME}?action=search">
                <div class="row gy-2 mb-2">
                    <div class="form-group col-12 col-sm-2 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="anyResource" checked="checked" />
                            <label class="form-check-label" for="anyResource">{translate key=AnyResource}</label>
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-10">
                        <label for="resourceGroups" class="visually-hidden">{translate key=Resources}</label>
                        <select id="resourceGroups" class="form-select form-select-sm" multiple="multiple"
                            {formname key=RESOURCE_ID multi=true} disabled="disabled">
                            {foreach from=$Resources item=resource}
                                <option value="{$resource->GetId()}">{$resource->GetName()}</option>
                            {/foreach}
                        </select>
                    </div>

                    <div class="form-group col-6 col-sm-2">
                        <div class="input-group input-group-sm">
                            <input type="number" min="0" step="1" value="0" class="form-control hours-minutes"
                                id="hours" {formname key=HOURS} autofocus="autofocus" />
                            <label class="input-group-text hours-minutes" for="hours">{translate key=Hours}</label>
                        </div>
                    </div>
                    <div class="form-group col-6 col-sm-2 me-1 me-md-5">
                        <div class="input-group input-group-sm">
                            <input type="number" min="0" step="5" value="30" class="form-control hours-minutes"
                                id="minutes" {formname key=MINUTES} />
                            <label class="input-group-text hours-minutes" for="minutes">{translate key=Minutes}</label>
                        </div>
                    </div>

                    <div class="form-group col-12 col-sm-7 d-flex align-items-center flex-wrap gap-2">
                        <div class="form-check d-inline-block me-2">
                            <input class="form-check-input" type="checkbox" id="specificTime"
                                {formname key=SPECIFIC_TIME} />
                            <label class="form-check-label" for="specificTime">{translate key=SpecificTime}</label>
                        </div>
                        <select {formname key=BEGIN_TIME} id="startTime" aria-label="{translate key=StartTime}"
                            class="form-select form-select-sm w-auto timepicker" data-format='{$TimeFormat}'
                            data-step='30' data-default="{Date::Now()->format('H:00')}" disabled="disabled">
                        </select>
                        <div class='mx-1'>-</div>
                        <select {formname key=END_TIME} id="endTime" aria-label="{translate key=EndTime}"
                            class="form-select form-select-sm w-auto timepicker" data-format='{$TimeFormat}'
                            data-step='30' data-default="{Date::Now()->AddHours(1)->format('H:00')}"
                            disabled="disabled">
                        </select>
                    </div>
                </div>

                <div class="form-group d-flex flex-wrap mb-2">
                    <div class="btn-group-sm form-group" role="group" data-bs-toggle="buttons">
                        <input type="radio" id="today" class="btn-check" checked value="today"
                            {formname key=AVAILABILITY_RANGE} />
                        <label class="btn btn-outline-primary" for="today">
                            <span class="d-none d-sm-inline-block">{translate key=Today}</span>
                            <span class="d-inline-block"> {format_date date=$Today key=calendar_dates}</span>
                        </label>

                        <input type="radio" id="tomorrow" class="btn-check" value="tomorrow"
                            {formname key=AVAILABILITY_RANGE} />
                        <label class="btn btn-outline-primary" for="tomorrow">
                            <div class="d-none d-sm-inline-block">{translate key=Tomorrow}</div>
                            <div class="d-inline-block"> {format_date date=$Tomorrow key=calendar_dates}</div>
                        </label>

                        <input type="radio" id="thisweek" class="btn-check" value="thisweek"
                            {formname key=AVAILABILITY_RANGE} />
                        <label class="btn btn-outline-primary" for="thisweek">
                            <div class="d-none d-sm-inline-block">{translate key=ThisWeek}</div>
                            <div class="d-inline-block d-sm-none">{translate key=Week}</div>
                        </label>

                        <input type="radio" id="daterange" class="btn-check" value="daterange"
                            {formname key=AVAILABILITY_RANGE} />
                        <label class="btn btn-outline-primary" for="daterange">
                            <i class="bi bi-calendar3-range"></i>
                            <div class="d-none d-sm-inline-block"> {translate key=DateRange}</div>
                        </label>
                    </div>

                    <div class="d-flex flex-wrap">
                        <div class="form-group px-2">
                            <label for="beginDate" class="visually-hidden">{translate key=BeginDate}</label>
                            <input type="text" id="BeginDate" class="form-control form-control-sm"
                                placeholder="{translate key=BeginDate}" disabled="disabled" {formname key=BEGIN_DATE} />
                        </div>
                        <div class="form-group">
                            <label for="endDate" class="visually-hidden">{translate key=EndDate}</label>
                            <input type="text" id="EndDate" class="form-control form-control-sm"
                                placeholder="{translate key=EndDate}" disabled="disabled" {formname key=END_DATE} />
                        </div>
                    </div>
                </div>

                <div class="form-group mb-2">
                    {control type="RecurrenceControl"}
                </div>

                <div class="form-group mb-2">
                    <a class="link-primary" href="#" data-bs-toggle="collapse" data-bs-target="#advancedSearchOptions">
                        <i class="bi bi-plus-circle-fill"></i>
                        {translate key=MoreOptions}
                    </a>
                </div>

                <div class="collapse row mb-2" id="advancedSearchOptions">
                    <div class="form-group col-6">
                        <label for="maxCapacity" class="hidden">{translate key=MinimumCapacity}</label>
                        <input type='number' id='maxCapacity' min='0' size='5' maxlength='5'
                            class="form-control form-control-sm" {formname key=MAX_PARTICIPANTS}
                            value="{$MaxParticipantsFilter|default:''}" placeholder="{translate key=MinimumCapacity}" />

                    </div>
                    <div class="form-group col-6">
                        <label for="resourceType" class="hidden">{translate key=ResourceType}</label>
                        <select id="resourceType" {formname key=RESOURCE_TYPE_ID} {formname key=RESOURCE_TYPE_ID}
                            class="form-select form-select-sm">
                            <option value="">- {translate key=ResourceType} -</option>
                            {object_html_options options=$ResourceTypes label='Name' key='Id' selected=$ResourceTypeIdFilter}
                        </select>
                    </div>

                    {foreach from=$ResourceAttributes item=attribute}
                        {control type="AttributeControl" attribute=$attribute align='vertical' searchmode=true namePrefix='r' class="col-sm-6 col-12"}
                    {/foreach}

                    {foreach from=$ResourceTypeAttributes item=attribute}
                        {control type="AttributeControl" attribute=$attribute align='vertical' searchmode=true namePrefix='rt' class="col-sm-6 col-12"}
                    {/foreach}
                </div>

                <div class="form-group d-grid gy-2">
                    <button type="submit" class="btn btn-primary" value="submit" {formname key=SUBMIT}>
                        <i class="bi bi-search me-1"></i>{translate key=FindATime}
                    </button>
                    <div class="text-center">{indicator}</div>
                </div>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
    <div id="availability-results"></div>


    {csrf_token}

    {include file="javascript-includes.tpl" Select2=true}
    {vendor_js src="jqtree/1.8.11/js/tree.jquery.js"}
    {vendor_js src="jquery-cookie/1.3.1/js/jquery.cookie.js"}
    {jsfile src="ajax-helpers.js"}
    {jsfile src="availability-search.js"}
    {jsfile src="resourcePopup.js"}
    {jsfile src="date-helper.js"}
    {jsfile src="recurrence.js"}

    {control type="DatePickerSetupControl" ControlId="BeginDate" DefaultDate=$Today MinDate=$Today}
    {control type="DatePickerSetupControl" ControlId="EndDate" DefaultDate=$Tomorrow MinDate=$Today}
    {control type="DatePickerSetupControl" ControlId="EndRepeat" DefaultDate=$Tomorrow}
    {control type="DatePickerSetupControl" ControlId="RepeatDate" Multiple=false}

    <script type="text/javascript">
        $(document).ready(function() {

            var recurOpts = {
                repeatType: '',
                repeatInterval: '',
                repeatMonthlyType: '',
                repeatWeekdays: []
            };

            var recurrence = new Recurrence(recurOpts);
            recurrence.init();

            var opts = {
                reservationUrlTemplate: "{$Path}{Pages::RESERVATION}?{QueryStringKeys::RESOURCE_ID}=[rid]&{QueryStringKeys::START_DATE}=[sd]&{QueryStringKeys::END_DATE}=[ed]"
            };
            var search = new AvailabilitySearch(opts);
            search.init();

            $('#resourceGroups').select2({
                placeholder: '{translate key=Resources}'
            });

            document.querySelectorAll('.timepicker').forEach(el => {
                dateHelper.initTimePicker(el);
            });
        });
    </script>

</div>

{include file='globalfooter.tpl'}
