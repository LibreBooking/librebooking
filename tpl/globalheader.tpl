<!DOCTYPE html>
<html lang="{$HtmlLang}" dir="{$HtmlTextDirection}">

<head>
    <title>{if isset($TitleKey) && $TitleKey neq ''}{translate key=$TitleKey args=$TitleArgs}{else}{$Title}{/if}</title>
    <meta http-equiv="Content-Type" content="text/html; charset={$Charset}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex" />
    {if isset($ShouldLogout) && $ShouldLogout}
        <meta http-equiv="REFRESH"
            content="{$SessionTimeoutSeconds};URL={$Path}logout.php?{QueryStringKeys::REDIRECT}={$smarty.server.REQUEST_URI|urlencode}" />
    {/if}
    <link rel="shortcut icon" href="{$Path}{$FaviconUrl}" />
    <link rel="icon" href="{$Path}{$FaviconUrl}" />
    <!-- JavaScript -->
    {if isset($UseLocalJquery) && $UseLocalJquery}
        {jsfile src="js/jquery-3.3.1.min.js"}
        {jsfile src="js/jquery-migrate-3.0.1.min.js"}
        {jsfile src="js/jquery-ui.1.12.1.custom.min.js"}
        {jsfile src="bootstrap/js/bootstrap.bundle.min.js"}
    {else}
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-migrate-3.0.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
    {/if}

    <!-- End JavaScript -->

    <!-- CSS -->
    {if isset($UseLocalJquery) && $UseLocalJquery}
        {cssfile src="scripts/css/smoothness/jquery-ui.1.12.1.custom.min.css"}
        {cssfile src="css/bootstrap-icons/css/bootstrap-icons.min.css" rel="stylesheet"}
        {cssfile src="scripts/bootstrap/css/bootstrap.css" rel="stylesheet"}
        {if isset($Qtip) && $Qtip}
            {cssfile src="css/jquery.qtip.min.css" rel="stylesheet"}
        {/if}
        {if isset($Validator) && $Validator}
            {cssfile src="css/bootstrapValidator.min.css" rel="stylesheet"}
        {/if}
        {if isset($Trumbowyg) && $Trumbowyg}
            {cssfile src="css/trumbowyg.min.css" rel="stylesheet"}
        {/if}
        {if isset($DataTable) && $DataTable}
            {cssfile src="scripts/css/datatable/cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet"}
            {cssfile src="scripts/css/datatable/cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet"}
            {cssfile src="scripts/css/datatable/cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet"}
        {/if}
        {if isset($DatePicker) && $DatePicker}
            {cssfile src="scripts/css/flatpickr/cdn.jsdelivr.net/npm/flatpickr/4.6.13/dist/flatpickr.min.css" rel="stylesheet"}
        {/if}
    {else}
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css" type="text/css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/qtip2/3.0.3/jquery.qtip.min.css" type="text/css" />
        {if isset($Validator) && $Validator}
            <link rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css"
                type="text/css" />
        {/if}
        {if isset($Trumbowyg) && $Trumbowyg}
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css"
                type="text/css" />
        {/if}
        {if isset($DataTable) && $DataTable}
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" type="text/css" />
            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css"
                type="text/css">
        {/if}
        {if isset($DatePicker) && $DatePicker}
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
        {/if}
    {/if}
    {if isset($InlineEdit) && $InlineEdit}
        {cssfile src="scripts/js/x-editable/css/bootstrap-editable.css" rel="stylesheet"}
        {cssfile src="scripts/js/wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet"}
    {/if}
    {if isset($Select2) && $Select2}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {/if}
    {if isset($Timepicker) && $Timepicker}
        {cssfile src="scripts/css/timePicker.css" rel="stylesheet"}
    {/if}
    {if isset($Fullcalendar) && $Fullcalendar}
        {cssfile src="scripts/css/fullcalendar.min.css"}
        <link rel='stylesheet' type='text/css' href='{$Path}scripts/css/fullcalendar.print.css' media='print' />
    {/if}

    {jsfile src="js/jquery-ui-timepicker-addon.js"}
    {cssfile src="scripts/css/jquery-ui-timepicker-addon.css"}
    {cssfile src="librebooking.css"}
    {if isset($cssFiles) && $cssFiles neq ''}
        {assign var='CssFileList' value=$cssFiles|split:','}
        {foreach from=$CssFileList item=cssFile}
            {cssfile src=$cssFile}
        {/foreach}
    {/if}
    {if isset($CssUrl) && $CssUrl neq ''}
        {cssfile src=$CssUrl}
    {/if}
    {if isset($CssStylingFile) && $CssStylingFile neq ''}
        {cssfile src='styling-plugin.php'}
    {/if}
    {if isset($CssExtensionFile) && $CssExtensionFile neq ''}
        {cssfile src=$CssExtensionFile}
    {/if}

    {if isset($printCssFiles) && $printCssFiles neq ''}
        {assign var='PrintCssFileList' value=$printCssFiles|split:','}
        {foreach from=$PrintCssFileList item=cssFile}
            <link rel='stylesheet' type='text/css' href='{$Path}{$cssFile}' media='print' />
        {/foreach}
    {/if}

    <!-- End CSS -->
</head>

<body data-bs-theme='{$cssTheme}'>

    {if !isset($HideNavBar) || $HideNavBar == false}
        <div class="d-flex align-items-center gap-2 m-2">
            <a class="navbar-brand" href="{$HomeUrl}">
                <img src="{$Path}img/{$LogoUrl}?{$Version}" alt="{$Title}" class="logo">
            </a>
            <div class="border-start ps-2 d-flex flex-column">
                {if $CompanyName neq ''}
                    <h5 class="mb-0"><a class="link-primary" href="{$CompanyUrl}">{$CompanyName}</a></h5>
                {/if}
                <h5 class="mb-0"><a class="link-primary" href="{$HomeUrl}">{$AppTitle}</a></h5>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg bg-light shadow-sm py-2 sticky-top">
            <div class="container-fluid">
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                    data-bs-target="#librebooking-navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="librebooking-navigation">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        {if isset($LoggedIn) && $LoggedIn}
                            <li class="nav-item" id="navDashboard"><a class="nav-link link-primary"
                                    href="{$Path}{Pages::DASHBOARD}">{translate key="Dashboard"}</a></li>
                            <li class="nav-item dropdown" id="navMyAccountDropdown">
                                <a href="#" class="nav-link  link-primary dropdown-toggle" role="button"
                                    data-bs-toggle="dropdown">{translate key="MyAccount"}</a>
                                <ul class="dropdown-menu">
                                    <li id="navProfile"><a class="dropdown-item"
                                            href="{$Path}{Pages::PROFILE}">{translate key="Profile"}</a></li>
                                    <li id="navPassword"><a class="dropdown-item"
                                            href="{$Path}{Pages::PASSWORD}">{translate key="ChangePassword"}</a></li>
                                    <li id="navNotification">
                                        <a class="dropdown-item"
                                            href="{$Path}{Pages::NOTIFICATION_PREFERENCES}">{translate key="NotificationPreferences"}</a>
                                    </li>
                                    {if isset($ShowParticipation) && $ShowParticipation}
                                        <li id="navInvitations">
                                            <a class="dropdown-item"
                                                href="{$Path}{Pages::PARTICIPATION}">{translate key="OpenInvitations"}</a>
                                        </li>
                                    {/if}
                                    {if isset($CreditsEnabled) && $CreditsEnabled}
                                        <li id="navUserCredits">
                                            <a class="dropdown-item" href="{$Path}{Pages::CREDITS}">{translate key="Credits"}</a>
                                        </li>
                                    {/if}
                                </ul>
                            </li>
                            <li class="nav-item dropdown" id="navScheduleDropdown">
                                <a href="#" class="nav-link link-primary dropdown-toggle" role="button"
                                    data-bs-toggle="dropdown">{translate key="Schedule"}</a>
                                <ul class="dropdown-menu">
                                    <li id="navBookings"><a class="dropdown-item"
                                            href="{$Path}{Pages::SCHEDULE}">{translate key="Bookings"}</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li id="navMyCalendar"><a class="dropdown-item"
                                            href="{$Path}{Pages::MY_CALENDAR}">{translate key="MyCalendar"}</a></li>
                                    <li id="navResourceCalendar"><a class="dropdown-item"
                                            href="{$Path}{Pages::CALENDAR}">{translate key="ResourceCalendar"}</a></li>
                                    <!--<li class="menuitem"><a href="#">{translate key="Current Status"}</a></li>-->
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li id="navFindATime"><a class="dropdown-item"
                                            href="{$Path}{Pages::OPENINGS}">{translate key="FindATime"}</a>
                                    </li>
                                    <li id="navFindATime"><a class="dropdown-item"
                                            href="{$Path}{Pages::SEARCH_RESERVATIONS}">{translate key="SearchReservations"}</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown" id="navReportsDropdown">
                                <a href="#" class="nav-link link-primary dropdown-toggle" role="button"
                                    data-bs-toggle="dropdown">{translate key="Check"}</a>
                                <ul class="dropdown-menu">
                                    <li id="CheckResources"><a class="dropdown-item"
                                            href="{$Path}view_resources.php">{translate key=Resources}</a>
                                    </li>
                                    <li id="CheckSchedules"><a class="dropdown-item"
                                            href="{$Path}view_schedules.php">{translate key=Schedules}</a>
                                    </li>
                                </ul>
                            </li>
                            {if isset($CanViewAdmin) && $CanViewAdmin}
                                <li class="nav-item dropdown" id="navApplicationManagementDropdown">
                                    <a href="#" class="nav-link link-primary dropdown-toggle" role="button"
                                        data-bs-toggle="dropdown">{translate key="ApplicationManagement"}</a>
                                    <ul class="dropdown-menu">
                                        <li id="navManageReservations"><a class="dropdown-item"
                                                href="{$Path}admin/manage_reservations.php">{translate key="ManageReservations"}</a>
                                        </li>
                                        <li id="navManageBlackouts"><a class="dropdown-item"
                                                href="{$Path}admin/manage_blackouts.php">{translate key="ManageBlackouts"}</a>
                                        </li>
                                        <li id="navManageQuotas"><a class="dropdown-item"
                                                href="{$Path}admin/manage_quotas.php">{translate key="ManageQuotas"}</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li id="navManageSchedules"><a class="dropdown-item"
                                                href="{$Path}admin/manage_schedules.php">{translate key="ManageSchedules"}</a>
                                        <li id="navManageResources"><a class="dropdown-item"
                                                href="{$Path}admin/manage_resources.php">{translate key="ManageResources"}</a>
                                        </li>
                                        <li id="navManageAccessories"><a class="dropdown-item"
                                                href="{$Path}admin/manage_accessories.php">{translate key="ManageAccessories"}</a>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li id="navManageUsers"><a class="dropdown-item"
                                                href="{$Path}admin/manage_users.php">{translate key="ManageUsers"}</a>
                                        </li>
                                        <li id="navManageGroups"><a class="dropdown-item"
                                                href="{$Path}admin/manage_groups.php">{translate key="ManageGroups"}</a>
                                        </li>

                                        <li id="navManageAnnouncements"><a class="dropdown-item"
                                                href="{$Path}admin/manage_announcements.php">{translate key="ManageAnnouncements"}</a>
                                        </li>
                                        <li class="divider"></li>
                                        {if isset($PaymentsEnabled) && $PaymentsEnabled}
                                            <li id="navManagePayments"><a class="dropdown-item"
                                                    href="{$Path}admin/manage_payments.php">{translate key="ManagePayments"}</a>
                                            </li>
                                        {/if}
                                        <li id="navManageAttributes"><a class="dropdown-item"
                                                href="{$Path}admin/manage_attributes.php">{translate key="CustomAttributes"}</a>
                                        </li>
                                    </ul>
                                </li>
                            {/if}
                            {if isset($CanViewResponsibilities) && $CanViewResponsibilities}
                                <li class="nav-item dropdown" id="navResponsibilitiesDropdown">
                                    <a href="#" class="nav-link link-primary dropdown-toggle" role="button"
                                        data-bs-toggle="dropdown">{translate key="Responsibilities"}</a>
                                    <ul class="dropdown-menu">
                                        {if isset($CanViewGroupAdmin) && $CanViewGroupAdmin}
                                            <li id="navResponsibilitiesGAUsers"><a class="dropdown-item"
                                                    href="{$Path}admin/manage_group_users.php">{translate key="ManageUsers"}</a>
                                            </li>
                                            <li id="navResponsibilitiesGAReservations"><a class="dropdown-item"
                                                    href="{$Path}admin/manage_group_reservations.php">{translate key="GroupReservations"}</a>
                                            </li>
                                            <li id="navResponsibilitiesGAGroups"><a class="dropdown-item"
                                                    href="{$Path}admin/manage_admin_groups.php">{translate key="ManageGroups"}</a>
                                            </li>
                                        {/if}
                                        {if (isset($CanViewResourceAdmin) && $CanViewResourceAdmin) || (isset($CanViewScheduleAdmin) && $CanViewScheduleAdmin)}
                                            <li id="navResponsibilitiesRAResources"><a class="dropdown-item"
                                                    href="{$Path}admin/manage_admin_resources.php">{translate key="ManageResources"}</a>
                                            </li>
                                            <li id="navResponsibilitiesRABlackouts"><a class="dropdown-item"
                                                    href="{$Path}admin/manage_blackouts.php">{translate key="ManageBlackouts"}</a>
                                            </li>
                                        {/if}
                                        {if isset($CanViewResourceAdmin) && $CanViewResourceAdmin}
                                            <li id="navResponsibilitiesRAReservations">
                                                <a class="dropdown-item"
                                                    href="{$Path}admin/manage_resource_reservations.php">{translate key="ResourceReservations"}</a>
                                            </li>
                                        {/if}
                                        {if isset($CanViewScheduleAdmin) && $CanViewScheduleAdmin}
                                            <li id="navResponsibilitiesSASchedules">
                                                <a class="dropdown-item"
                                                    href="{$Path}admin/manage_admin_schedules.php">{translate key="ManageSchedules"}</a>
                                            </li>
                                            <li id="navResponsibilitiesSAReservations">
                                                <a class="dropdown-item"
                                                    href="{$Path}admin/manage_schedule_reservations.php">{translate key="ScheduleReservations"}</a>
                                            </li>
                                        {/if}
                                        <li id="navResponsibilitiesAnnouncements">
                                            <a class="dropdown-item"
                                                href="{$Path}admin/manage_announcements.php">{translate key="ManageAnnouncements"}</a>
                                        </li>
                                    </ul>
                                </li>
                            {/if}
                            {if isset($CanViewReports) && $CanViewReports}
                                <li class="nav-item dropdown" id="navReportsDropdown">
                                    <a href="#" class="nav-link link-primary dropdown-toggle" role="button"
                                        data-bs-toggle="dropdown">{translate key="Reports"}</a>
                                    <ul class="dropdown-menu">
                                        <li id="navGenerateReport">
                                            <a class="dropdown-item"
                                                href="{$Path}reports/{Pages::REPORTS_GENERATE}">{translate key=GenerateReport}</a>
                                        </li>
                                        <li id="navSavedReports">
                                            <a class="dropdown-item"
                                                href="{$Path}reports/{Pages::REPORTS_SAVED}">{translate key=MySavedReports}</a>
                                        </li>
                                        <li id="navCommonReports">
                                            <a class="dropdown-item"
                                                href="{$Path}reports/{Pages::REPORTS_COMMON}">{translate key=CommonReports}</a>
                                        </li>
                                    </ul>
                                </li>
                            {/if}
                        {/if}

                    </ul>
                    <ul class="navbar-nav navbar-right">
                        {if isset($ShowScheduleLink) && $ShowScheduleLink}
                            <li class="nav-item dropdown" id="navScheduleDropdown">
                                <a href="#" class="nav-link link-primary dropdown-toggle" role="button"
                                    data-bs-toggle="dropdown">{translate key="Schedule"}</a>
                                <ul class="dropdown-menu">
                                    <li id="navViewSchedule"><a class="dropdown-item"
                                            href="view-schedule.php">{translate key='ViewSchedule'}</a>
                                    </li>
                                    <li id="navViewCalendar"><a class="dropdown-item"
                                            href="view-calendar.php">{translate key='ViewCalendar'}</a>
                                    </li>
                                </ul>
                            </li>
                        {/if}
                        {if isset($CanViewAdmin) && $CanViewAdmin}
                            <li class="nav-item dropdown" id="navHelpDropdown">
                                <a href="#" class="nav-link link-primary dropdown-toggle" role="button"
                                    data-bs-toggle="dropdown">
                                    <span class="visually-hidden">Configuration</span>
                                    <i class="bi bi-gear-fill"></i>
                                    {if isset($ShowNewVersion) && $ShowNewVersion}<span
                                            class="badge badge-new-version new-version"
                                        id="newVersionBadge">{translate key=NewVersion}</span>{/if}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    {if isset($EnableConfigurationPage) && $EnableConfigurationPage}
                                        <li id="navManageConfiguration"><a class="dropdown-item"
                                                href="{$Path}admin/manage_configuration.php">{translate key="ManageConfiguration"}</a>
                                        </li>
                                    {/if}
                                    <li id="navEmailTemplates"><a class="dropdown-item"
                                            href="{$Path}admin/manage_email_templates.php">{translate key="ManageEmailTemplates"}</a>
                                    </li>
                                    <li id="navLookAndFeel"><a class="dropdown-item"
                                            href="{$Path}admin/manage_theme.php">{translate key="LookAndFeel"}</a>
                                    </li>
                                    <li id="navImport"><a class="dropdown-item"
                                            href="{$Path}admin/ics_import.php">{translate key="Import"}</a>
                                    </li>
                                    <li id="navServerSettings"><a class="dropdown-item"
                                            href="{$Path}admin/server_settings.php">{translate key="ServerSettings"}</a>
                                    </li>
                                    <li id="navDataCleanup"><a class="dropdown-item"
                                            href="{$Path}admin/data_cleanup.php">{translate key="DataCleanup"}</a>
                                    </li>
                                    {if isset($ShowNewVersion) && $ShowNewVersion}
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li id="navNewVersion" class="new-version">
                                            <a class="dropdown-item"
                                                href="https://github.com/LibreBooking/app/releases">{translate key=WhatsNew}</a>
                                        </li>
                                    {/if}
                                </ul>
                            </li>
                        {/if}
                        <li class="nav-item dropdown" id="navHelpDropdown">
                            <a href="#" class="nav-link link-primary dropdown-toggle" role="button"
                                data-bs-toggle="dropdown">{translate key="Help"}</a>
                            <ul class="dropdown-menu  dropdown-menu-end">
                                <li id="navHelp"><a class="dropdown-item"
                                        href="https://github.com/LibreBooking/app/wiki">{translate key=Help}</a>
                                </li>
                                {if isset($CanViewAdmin) && $CanViewAdmin}
                                    <li id="navHelpAdmin"><a class="dropdown-item"
                                            href="https://github.com/LibreBooking/app/wiki/Administration">{translate key=Administration}</a>
                                    </li>
                                {/if}
                                <li id="navAbout"><a class="dropdown-item"
                                        href="{$Path}help.php?ht=about">{translate key=About}</a></li>
                            </ul>
                        </li>
                        {if isset($LoggedIn) && $LoggedIn}
                            <li class="nav-item" id="navSignOut"><a class="nav-link link-primary"
                                    href="{$Path}logout.php">{translate key="SignOut"}</a></li>
                        {else}
                            <li class="nav-item" id="navLogIn"><a class="nav-link  link-primary"
                                    href="{$Path}index.php">{translate key="LogIn"}</a></li>
                        {/if}
                    </ul>
                </div>
            </div>
        </nav>
    {/if}

<div id="main" class="container-fluid my-3">