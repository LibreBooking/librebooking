{include file='globalheader.tpl' Select2=true DataTable=true}

<div class="page-search-reservations">
    <div class="card shadow mb-2">
        <div class="card-body">
            <form role="form" name="searchForm" id="searchForm" method="post" class="row gy-2"
                action="{$smarty.server.SCRIPT_NAME}?action=search">
                <h1 class="text-center border-bottom mb-2">{translate key="SearchReservations"}</h1>
                <div class="form-group col-sm-4">
                    <label for="userFilter" class="fw-bold">{translate key=User}</label>
                    <div class="position-relative">
                        <input id="userFilter" type="text" class="form-control form-control-sm"
                            value="{$UserNameFilter}" placeholder="{translate key=User}" />
                        <span class="searchclear bi bi-x-circle-fill" ref="userFilter,userId"></span>
                        <input id="userId" type="hidden" {formname key=USER_ID} value="{$UserIdFilter}" />
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label for="resources" class="fw-bold">{translate key=Resources}</label>
                    <select id="resources" class="form-select form-select-sm" multiple="multiple"
                        {formname key=RESOURCE_ID multi=true}>
                        {foreach from=$Resources item=resource}
                            <option value="{$resource->GetId()}">{$resource->GetName()}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="form-group col-sm-4">
                    <label for="schedules" class="fw-bold">{translate key=Schedules}</label>
                    <select id="schedules" class="form-select" multiple="multiple"
                        {formname key=SCHEDULE_ID multi=true}>
                        {foreach from=$Schedules item=schedule}
                            <option value="{$schedule->GetId()}">{$schedule->GetName()}</option>
                        {/foreach}
                    </select>
                </div>

                <div class="form-group col-sm-4">
                    <label for="title" class="fw-bold">{translate key=Title}</label>
                    <input type="search" id="title" class="form-control form-control-sm"
                        {formname key=RESERVATION_TITLE} placeholder="{translate key=Title}" />
                </div>

                <div class="form-group col-sm-4">
                    <label for="description" class="fw-bold">{translate key=Description}</label>
                    <input type="search" id="description" class="form-control form-control-sm"
                        {formname key=DESCRIPTION} placeholder="{translate key=Description}" />
                </div>

                <div class="form-group col-sm-4">
                    <label for="referenceNumber" class="fw-bold">{translate key=ReferenceNumber}</label>
                    <input type="search" id="referenceNumber" class="form-control form-control-sm"
                        {formname key=REFERENCE_NUMBER} placeholder="{translate key=ReferenceNumber}" />
                </div>

                <div class="form-group d-flex">
                    <div class="btn-group-sm" data-bs-toggle="buttons">
                        <input type="radio" class="btn-check" id="today" value="today"
                            {formname key=AVAILABILITY_RANGE} />
                        <label class="btn btn-outline-primary" for="today">
                            <span class="d-none d-sm-inline-block">{translate key=Today}</span>
                            <span> {format_date date=$Today key=calendar_dates}</span>
                        </label>

                        <input type="radio" class="btn-check" id="tomorrow" value="tomorrow"
                            {formname key=AVAILABILITY_RANGE} />
                        <label class="btn btn-outline-primary" for="tomorrow">
                            <span class="d-none d-sm-inline-block">{translate key=Tomorrow}</span>
                            <span> {format_date date=$Tomorrow key=calendar_dates}</span>
                        </label>

                        <input type="radio" class="btn-check" id="thisweek" value="thisweek"
                            {formname key=AVAILABILITY_RANGE} />
                        <label class="btn btn-outline-primary" for="thisweek">
                            <span class="d-none d-sm-inline-block">{translate key=ThisWeek}</span>
                            <span class="d-sm-none">{translate key=Week}</span>
                        </label>

                        <input type="radio" class="btn-check" id="daterange" value="daterange" checked
                            {formname key=AVAILABILITY_RANGE} />
                        <label class="btn btn-outline-primary " for="daterange">
                            <i class="bi bi-calendar3-range"></i>
                            <span class="d-none d-sm-inline-block"> {translate key=DateRange}</span>
                        </label>
                    </div>
                    <div class="d-flex ms-5">
                        <div class="form-group px-2">
                            <label for="beginDate" class="visually-hidden">{translate key=BeginDate}</label>
                            <input type="date" id="beginDate" class="form-control form-control-sm dateinput"
                                placeholder="{translate key=BeginDate}" />
                            <input type="hidden" id="formattedBeginDate" {formname key=BEGIN_DATE}
                                value="{formatdate date=$BeginDate key=system}" />
                        </div>
                        <div class="form-group">
                            <label for=" endDate" class="visually-hidden">{translate key=EndDate}</label>
                            <input type="date" id="endDate" class="form-control form-control-sm dateinput"
                                placeholder="{translate key=EndDate}" />
                            <input type="hidden" id="formattedEndDate" {formname key=END_DATE}
                                value="{formatdate date=$EndDate key=system}" />
                        </div>
                    </div>
                </div>


                <div class="form-group d-grid gy-3">
                    <button type="submit" class="btn btn-primary" value="submit" {formname key=SUBMIT}>
                        <i class="bi bi-search me-1"></i>{translate key=SearchReservations}</button>
                    <div class="text-center">{indicator}</div>
                </div>
            </form>
        </div>
    </div>

    <div id="reservation-results"></div>

    {csrf_token}

    {include file="javascript-includes.tpl" Select2=true Qtip=2 Clear=true DataTable=true}
    {jsfile src="js/jquery.cookie.js"}
    {jsfile src="ajax-helpers.js"}
    {jsfile src="resourcePopup.js"}
    {jsfile src="autocomplete.js"}
    {jsfile src="reservationPopup.js"}
    {jsfile src="reservation-search.js"}
    {jsfile src="search-clear.js"}

    {control type="DatePickerSetupControl" ControlId="beginDate" AltId="formattedBeginDate" DefaultDate=$BeginDate}
    {control type="DatePickerSetupControl" ControlId="endDate" AltId="formattedEndDate" DefaultDate=$EndDate}

    <script type="text/javascript">
        $(document).ready(function() {
            var opts = {
                autocompleteUrl: "{$Path}ajax/autocomplete.php?type={AutoCompleteType::User}",
                reservationUrlTemplate: "{$Path}reservation.php?{QueryStringKeys::REFERENCE_NUMBER}=[refnum]",
                popupUrl: "{$Path}ajax/respopup.php"
            };

            var search = new ReservationSearch(opts);
            search.init();


            $('#resources').select2({
                placeholder: '{translate key=Resources}'
            });

            $('#schedules').select2({
                placeholder: '{translate key=Schedules}'
            });
        });
    </script>
</div>

{include file='globalfooter.tpl'}