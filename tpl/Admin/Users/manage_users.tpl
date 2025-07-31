{include file='globalheader.tpl' InlineEdit=true DataTable=true}

<div id="page-manage-users" class="admin-page">

    <div class="border-bottom mb-3 clearfix">
        <div class="dropdown admin-header-more float-end">
            <div class="btn-group btn-group-sm">
                <a role="menuitem" href="#" id="add-user" class="add-link add-user add-group btn btn-primary"><i
                        class="bi bi-plus-circle-fill me-1 add icon"></i>{translate key="AddUser"}
                </a>
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="moreUserActions"
                    data-bs-toggle="dropdown">
                    <span class="visually-hidden">{translate key="More"}</span>
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="moreUserActions">
                    <li role="presentation">
                        <a role="menuitem" href="#" id="invite-users" class="add-link add-user dropdown-item"><i
                                class="bi bi-send me-1"></i>{translate key="InviteUsers"}

                        </a>
                    </li>
                    <li role="presentation">
                        <a role="menuitem" href="#" id="import-users" class="add-link add-user dropdown-item"><i
                                class="bi bi-download me-1"></i>{translate key="Import"}
                        </a>
                    </li>
                    <li role="presentation">
                        <a role="menuitem" href="{$ExportUrl}" download="{$ExportUrl}" id="export-users"
                            class="add-link add-user dropdown-item" target="_blank"><i class="bi bi-upload
                        me-1"></i>{translate key="Export"}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <h1 class="float-start">{translate key=ManageUsers}</h1>
    </div>

    <div class="card shadow mb-2">
        <div class="card-body">
            <form id="filterForm" class="row" role="form">
                <div class="form-group col-sm-4">
                    <label class="fw-bold" for="userSearch"><i
                            class="bi bi-person-fill me-1"></i>{translate key=FindUser}
                        | {html_link href=$smarty.server.SCRIPT_NAME key=AllUsers}</label>
                    <input type="text" id="userSearch" class="form-control" />
                </div>
                <div class="form-group col-sm-2">
                    <label class="fw-bold" for="filterStatusId">{translate key=Status}</label>
                    <select id="filterStatusId" class="form-select">
                        {html_options selected=$FilterStatusId options=$statusDescriptions}
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                {assign var=tableId value='userList'}
                <table class="table table-striped table-hover w-100 admin-panel" id="{$tableId}">
                    <thead>
                        <tr>
                            <th>{translate key='Name'}</th>
                            <th>{translate key='Username'}</th>
                            <th>{translate key='Email'}</th>
                            <th>{translate key='Phone'}</th>
                            <th>{translate key='Organization'}</th>
                            <th>{translate key='Position'}</th>
                            <th>{translate key='Created'}</th>
                            <th>{translate key='LastLogin'}</th>
                            <th class="action">{translate key='Status'}</th>
                            {if $CreditsEnabled}
                                <th class="action">{translate key='Credits'}</th>
                            {/if}
                            {if $PerUserColors}
                                <th class="action">{translate key='Color'}</th>
                            {/if}
                            <th>{translate key='Actions'}</th>
                            <th class="action-delete">
                                <div class="form-check checkbox-single">
                                    <input class="form-check-input" type="checkbox" id="delete-all"
                                        aria-label="{translate key=All}" title="{translate key=All}" />
                                </div>
                                <a href="#" id="delete-selected" class="d-none" title="{translate key=Delete}">
                                    <span class="visually-hidden">{translate key=Delete}</span>
                                    <span class="bi bi-trash3-fill text-danger icon remove"></span>
                                </a>
                            </th>
                            {assign var=attributes value=$AttributeList}
                            {if $attributes|default:array()|count > 0}
                                <th>{translate key='More'}
                                </th>
                            {/if}
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$users item=user}
                            {assign var=id value=$user->Id}
                            <tr data-userId="{$id}">
                                <td>{fullname first=$user->First|unescape:'html' last=$user->Last|unescape:'html' ignorePrivacy="true"}</td>
                                <td>{$user->Username}</td>
                                <td><a href="mailto:{$user->Email}" class="link-primary">{$user->Email}</a></td>
                                <td>{$user->Phone}</td>
                                <td>{$user->Organization}</td>
                                <td>{$user->Position}</td>
                                <td>{format_date date=$user->DateCreated key=short_datetime timezone=$Timezone}</td>
                                <td>{format_date date=$user->LastLogin key=short_datetime timezone=$Timezone}</td>
                                <td class="action"><a href="#"
                                        class="update changeStatus link-primary">{$statusDescriptions[$user->StatusId]}</a>
                                    {indicator id="userStatusIndicator"}
                                </td>
                                {if $CreditsEnabled}
                                    <td class="text-end">
                                        <span class="propertyValue inlineUpdate changeCredits fw-bold text-decoration-underline"
                                            data-type="number" data-pk="{$id}" data-value="{$user->CurrentCreditCount}"
                                            data-name="{FormKeys::CREDITS}">{$user->CurrentCreditCount}</span>
                                        <a href="credit_log.php?{QueryStringKeys::USER_ID}={$id}"
                                            title="{translate key=CreditHistory}" class="link-primary">
                                            <span class="no-color">{translate key=CreditHistory}</span>
                                            <i class="bi bi-list-task"></i>
                                        </a>
                                    </td>
                                {/if}
                                {if $PerUserColors}
                                    <td class="action">
                                        <a href="#" class="update changeColor link-primary">{translate key='Edit'}</a>
                                        {if !empty($user->ReservationColor)}
                                            <div class="user-color update changeColor rounded"
                                                style="background-color:{$user->ReservationColor}">
                                                &nbsp;
                                            </div>
                                        {/if}
                                    </td>
                                {/if}

                                <td width="100">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-primary update edit">
                                            <span class="visually-hidden">{translate key=Update}</span>
                                            <i class="bi bi-pencil-square"></i></button>
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                            <span class="visually-hidden">{translate key=More}</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation"><a role="menuitem" href="#"
                                                    class="dropdown-item update edit">{translate key="Edit"}</a>
                                            </li>
                                            <li role="presentation"><a role="menuitem" href="#"
                                                    class="dropdown-item update changePermissions">{translate key="Permissions"}</a>
                                            </li>
                                            <li role="presentation"><a role="menuitem" href="#"
                                                    class="dropdown-item update changeGroups">{translate key="Groups"}</a>
                                            </li>
                                            <li role="presentation"><a role="menuitem" href="#"
                                                    class="dropdown-item update viewReservations">{translate key="Reservations"}</a>
                                            </li>
                                            <li role="presentation"><a role="menuitem" href="#"
                                                    class="dropdown-item update resetPassword">{translate key="ChangePassword"}</a>
                                            </li>

                                        </ul>
                                    </div>
                                    |
                                    <span class="inline">
                                        <a href="#" class="update delete">
                                            <span class="visually-hidden">{translate key=Delete}</span>
                                            <span class="bi bi-trash3-fill text-danger icon remove"></span>
                                        </a>
                                    </span>
                                </td>
                                <td class="action-delete">
                                    <div class="form-check checkbox-single">
                                        <input {formname key=USER_ID multi=true} class="delete-multiple form-check-input"
                                            type="checkbox" id="delete{$id}" value="{$id}"
                                            aria-label="{translate key=Delete}" title="{translate key=Delete}" />
                                    </div>
                                </td>

                                {if $attributes|default:array()|count > 0}
                                    <td class="customAttributes" userId="{$id}">
                                        {assign var=changeAttributeAction value=ManageUsersActions::ChangeAttribute}
                                        {assign var=attributeUrl value="`$smarty.server.SCRIPT_NAME`?action=`$changeAttributeAction`"}
                                        {foreach from=$AttributeList item=attribute}
                                            {include file='Admin/InlineAttributeEdit.tpl' url=$attributeUrl id=$id attribute=$attribute value=$user->GetAttributeValue($attribute->Id())}
                                        {/foreach}
                                    </td>
                                {/if}
                            {/foreach}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="addUserDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <form id="addUserForm" class="form" role="form" method="post" ajaxAction="{ManageUsersActions::AddUser}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">{translate key=AddUser}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body row gy-2">
                        <div class="col-12">
                            <div id="addUserResults" class="validationSummary alert alert-danger d-none">
                                <ul>
                                    {async_validator id="addUserEmailformat" key="ValidEmailRequired"}
                                    {async_validator id="addUserUniqueemail" key="UniqueEmailRequired"}
                                    {async_validator id="addUserUsername" key="UniqueUsernameRequired"}
                                    {async_validator id="addAttributeValidator" key=""}
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="fw-bold" for="addUsername">{translate key="Username"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                <input type="text" {formname key="USERNAME"} class="required form-control has-feedback"
                                    required id="addUsername" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="fw-bold" for="addEmail">{translate key="Email"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                <input type="text" {formname key="EMAIL"} class="required form-control has-feedback"
                                    required id="addEmail" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group has-feedback">
                                <label class="fw-bold" for="addFname">{translate key="FirstName"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                <input type="text" {formname key="FIRST_NAME"}
                                    class="required form-control has-feedback" required id="addFname" />
                                <i class="glyphicon glyphicon-asterisk form-control-feedback"
                                    data-bv-icon-for="addFname"></i>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group has-feedback">
                                <label class="fw-bold" for="addLname">{translate key="LastName"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                <input type="text" {formname key="LAST_NAME"} class="required form-control has-feedback"
                                    required id="addLname" />
                                <i class="glyphicon glyphicon-asterisk form-control-feedback"
                                    data-bv-icon-for="addLname"></i>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group has-feedback">
                                <label class="fw-bold" for="addPassword">{translate key="Password"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                <input type="text" {formname key="PASSWORD"} class="required form-control has-feedback"
                                    required id="addPassword" />
                                <i class="glyphicon glyphicon-asterisk form-control-feedback"
                                    data-bv-icon-for="addPassword"></i>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group has-feedback">
                                <label class="fw-bold" for="addTimezone">{translate key="Timezone"}</label>
                                <select id="addTimezone" {formname key='TIMEZONE'} class="form-select">
                                    {html_options values=$Timezones output=$Timezones selected=$Timezone}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="fw-bold" for="addPhone">{translate key="Phone"}</label>
                                <input type="text" {formname key="PHONE"} class="form-control" id="addPhone" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="fw-bold" for="addOrganization">{translate key="Organization"}</label>
                                <input type="text" {formname key="ORGANIZATION"} class="form-control"
                                    id="addOrganization" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label class="fw-bold" for="addPosition">{translate key="Position"}</label>
                                <input type="text" {formname key="POSITION"} class="form-control" id="addPosition" />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group has-feedback">
                                <label class="fw-bold" for="addGroup">{translate key="Group"}</label>
                                <select id="addGroup" {formname key='GROUP_ID'} class="form-select">
                                    <option value="">{translate key=None}</option>
                                    {object_html_options options=$Groups label=Name key=Id}
                                </select>
                            </div>
                        </div>
                        {if $AttributeList|default:array()|count > 0}
                            <div class="col-12 col-sm-6">
                                {control type="AttributeControl" attribute=$AttributeList[0]}
                            </div>
                        {else}
                            <div class="col-sm-12 col-md-6">&nbsp;</div>
                        {/if}

                        {if $AttributeList|default:array()|count > 1}
                            {for $i=1 to $AttributeList|default:array()|count-1}
                                <div class="col-12 col-sm-6">
                                    {control type="AttributeControl" attribute=$AttributeList[$i]}
                                </div>
                            {/for}
                        {/if}
                        <div class="clearfix"></div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sendAddEmail" checked="checked"
                                {formname key=SEND_AS_EMAIL} />
                            <label class="form-check-label" for="sendAddEmail">{translate key=NotifyUser}</label>
                        </div>
                        {cancel_button}
                        {add_button}
                        {indicator}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="importUsersDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="importUsersModalLabel"
        aria-hidden="true">
        <form id="importUsersForm" class="form" role="form" method="post" enctype="multipart/form-data"
            ajaxAction="{ManageUsersActions::ImportUsers}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importUsersModalLabel">{translate key=Import}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body gap-3">
                        <div id="importUserResults" class="validationSummary alert alert-danger d-none">
                            <ul>
                                {async_validator id="fileExtensionValidator" key=""}
                            </ul>
                        </div>
                        <div id="importErrors" class="alert alert-danger d-none"></div>
                        <div id="importResult" class="alert alert-success d-none">
                            <span>{translate key=RowsImported}</span>

                            <span id="importCount" class="inline fw-bold">0</span>
                            <span>{translate key=RowsSkipped}</span>

                            <span id="importSkipped" class="inline fw-bold">0</span>
                            <a class="alert-link" href="{$smarty.server.SCRIPT_NAME}">{translate key=Done}</a>
                        </div>
                        <div id="importInstructions" class="alert alert-info">
                            <div class="note fst-italic">{translate key=UserImportInstructions}</div>
                            <a href="{$smarty.server.SCRIPT_NAME}?dr=template"
                                download="{$smarty.server.SCRIPT_NAME}?dr=template" class="alert-link"
                                target="_blank">{translate key=GetTemplate}<span class="bi bi-download ms-1"></span></a>
                        </div>
                        <div>
                            <input class="form-control" type="file" {formname key=USER_IMPORT_FILE} accept=".csv"
                                id="userImportFile" />
                            <label for="userImportFile" class="visually-hidden">User Import File</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="updateOnImport"
                                    {formname key=UPDATE_ON_IMPORT} />
                                <label class="form-check-label"
                                    for="updateOnImport">{translate key=UpdateUsersOnImport}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {add_button key=Import}
                        {indicator}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <input type="hidden" id="activeId" />

    <div id="permissionsDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="permissionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionsModalLabel">{translate key=Permissions}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="permissionsForm" method="post" ajaxAction="{ManageUsersActions::Permissions}">
                        <div class="alert alert-warning">{translate key=UserPermissionInfo}</div>
                        <a href="#" class="link-primary" id="checkNoResources">{translate key=None}</a> |
                        <a href="#" class="link-primary" id="checkAllResourcesFull">{translate key=FullAccess}</a> |
                        <a href="#" class="link-primary" id="checkAllResourcesView">{translate key=ViewOnly}</a>
                        {assign var=tableIdFilter value="permissionsFormFilter"}
                        <table class="table table-striped table-hover w-100" id="{$tableIdFilter}">
                            <thead>
                                <tr>
                                    <th>{translate key="Resource"}</th>
                                </tr>
                            </thead>
                            {foreach from=$resources item=resource}
                                <tr>
                                    <td>
                                        {assign var=rid value=$resource->GetResourceId()}
                                        <div class="permissionRow form-group clearfix">
                                            <label for="permission_{$rid}"
                                                class="float-start">{$resource->GetName()}</label>
                                            <select class="float-end form-select form-select-sm resourceId inline-block"
                                                style="width:auto;" {formname key=RESOURCE_ID multi=true}
                                                id="permission_{$rid}">
                                                <option value="{$rid}_none" class="none">{translate key=None}</option>
                                                <option value="{$rid}_0" class="full">{translate key=FullAccess}</option>
                                                <option value="{$rid}_1" class="view">{translate key=ViewOnly}</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            {/foreach}
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    {cancel_button}
                    {update_button submit=true form="permissionsForm"}
                    {indicator}
                </div>
            </div>
        </div>
    </div>

    <div id="passwordDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel"
        aria-hidden="true">
        <form id="passwordForm" method="post" ajaxAction="{ManageUsersActions::Password}" class="was-validated">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="passwordModalLabel">{translate key=Password}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group has-feedback">
                            <label for="password">{translate key=Password}</label>
                            {textbox type="password" name="PASSWORD" class="required" required="required" value=""}
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {update_button}
                        {indicator}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="invitationDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="invitationModalLabel"
        aria-hidden="true">
        <form id="invitationForm" method="post" ajaxAction="{ManageUsersActions::InviteUsers}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="invitationModalLabel">{translate key=InviteUsers}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group has-feedback">
                            <label for="inviteEmails">{translate key=InviteUsersLabel}</label>
                            <textarea id="inviteEmails" class="form-control" rows="5"
                                {formname key=INVITED_EMAILS}></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        <button type="button" class="btn btn-primary save"><span class="bi bi-send-fill"></span>
                            {translate key=InviteUsers}</button>
                        {indicator}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="userDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
        aria-hidden="true">
        <form id="userForm" method="post" ajaxAction="{ManageUsersActions::UpdateUser}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">{translate key=Edit}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div id="update-user-placeholder"></div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {update_button}
                        {indicator}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="deleteDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <form id="deleteUserForm" method="post" ajaxAction="{ManageUsersActions::DeleteUser}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">{translate key=Delete}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <div>{translate key=DeleteWarning}</div>

                            <div>{translate key=DeleteUserWarning}</div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {delete_button}
                        {indicator}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="deleteMultipleDialog" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="deleteMultipleModalLabel" aria-hidden="true">
        <form id="deleteMultipleUserForm" method="post" ajaxAction="{ManageUsersActions::DeleteMultipleUsers}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMultipleModalLabel">{translate key=Delete} (<span
                                id="deleteMultipleCount"></span>)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <div>{translate key=DeleteWarning}</div>

                            <div>{translate key=DeleteMultipleUserWarning}</div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {delete_button}
                        {indicator}
                    </div>
                    <div id="deleteMultiplePlaceHolder" class="d-none"></div>
                </div>
            </div>
        </form>
    </div>

    <div id="groupsDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="groupsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupsModalLabel">{translate key=Groups}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body scrollable-modal-content">

                    <div id="groupList" class="d-none">
                        {foreach from=$Groups item=group}
                            <div class="group-item d-flex align-items-center" groupId="{$group->Id}">
                                <a href="#"><span class="visually-hidden">{$group->Name}</span>&nbsp;</a>
                                <span>{$group->Name}</span>
                            </div>
                        {/foreach}
                    </div>

                    <h6>{translate key=GroupMembership} <span class="badge bg-primary" id="groupCount">0</span></h6>
                    <div id="addedGroups">
                    </div>

                    <h5>{translate key=AvailableGroups}</h5>
                    <div id="removedGroups">
                    </div>

                    <form id="addGroupForm" method="post" ajaxAction="addUser">
                        <input type="hidden" id="addGroupId" {formname key=GROUP_ID} />
                        <input type="hidden" id="addGroupUserId" {formname key=USER_ID} />
                    </form>

                    <form id="removeGroupForm" method="post" ajaxAction="removeUser">
                        <input type="hidden" id="removeGroupId" {formname key=GROUP_ID} />
                        <input type="hidden" id="removeGroupUserId" {formname key=USER_ID} />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="colorDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="colorModalLabel"
        aria-hidden="true">
        <form id="colorForm" method="post" ajaxAction="{ManageUsersActions::ChangeColor}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="colorModalLabel">{translate key=Color}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-paint-bucket"></i></span>
                            <label for="reservationColor" class="visually-hidden">Reservation Color</label>
                            <input type="color" {formname key=RESERVATION_COLOR} id="reservationColor"
                                class="form-control form-control-color">
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {update_button}
                        {indicator}
                    </div>
                </div>
            </div>
        </form>
    </div>

    {csrf_token}
    {include file="javascript-includes.tpl" InlineEdit=true DataTable=true}
    {datatable tableId=$tableId}
    {datatablefilter tableId=$tableIdFilter}
    {jsfile src="ajax-helpers.js"}
    {jsfile src="autocomplete.js"}
    {jsfile src="admin/user.js"}
    {jsfile src="js/jquery.form-3.09.min.js"}

    <script type="text/javascript">
        function setUpEditables() {
            $.fn.editable.defaults.mode = 'popup';
            $.fn.editable.defaults.toggle = 'manual';
            $.fn.editable.defaults.emptyclass = '';
            $.fn.editable.defaults.params = function(params) {
                params.CSRF_TOKEN = $('#csrf_token').val();
                return params;
            };

            var updateUrl = '{$smarty.server.SCRIPT_NAME}?action=';
            $('.inlineAttribute').editable({
                url: updateUrl + '{ManageUsersActions::ChangeAttribute}', emptytext: '-'
            });

            $('.changeCredits').editable({
                url: updateUrl + '{ManageUsersActions::ChangeCredits}', emptytext: '-'
            });
        }

        $(document).ready(function() {
            var actions = {
                activate: '{ManageUsersActions::Activate}', deactivate: '{ManageUsersActions::Deactivate}'
            };

            var userOptions = {
                userAutocompleteUrl: "../ajax/autocomplete.php?type={AutoCompleteType::MyUsers}",
                orgAutoCompleteUrl: "../ajax/autocomplete.php?type={AutoCompleteType::Organization}",
                groupsUrl: '{$smarty.server.SCRIPT_NAME}',
                groupManagementUrl: '{$ManageGroupsUrl}',
                permissionsUrl: '{$smarty.server.SCRIPT_NAME}',
                submitUrl: '{$smarty.server.SCRIPT_NAME}',
                saveRedirect: '{$smarty.server.SCRIPT_NAME}',
                selectUserUrl: '{$smarty.server.SCRIPT_NAME}?uid=',
                filterUrl: '{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::ACCOUNT_STATUS}=',
                actions: actions,
                manageReservationsUrl: '{$ManageReservationsUrl}',
            };

            var userManagement = new UserManagement(userOptions);
            userManagement.init();

            {foreach from=$users item=user}
                var user = {
                    id: {$user->Id},
                    first: '{$user->First|escape:"javascript"}',
                    last: '{$user->Last|escape:"javascript"}',
                    isActive: '{$user->IsActive()}',
                    username: '{$user->Username|escape:"javascript"}',
                    email: '{$user->Email|escape:"javascript"}',
                    timezone: '{$user->Timezone}',
                    phone: '{$user->Phone|escape:"javascript"}',
                    organization: '{$user->Organization|escape:"javascript"}',
                    position: '{$user->Position|escape:"javascript"}',
                    reservationColor: '{$user->ReservationColor|escape:"javascript"}'
                };
                userManagement.addUser(user);
            {/foreach}


            setUpEditables();
        });
    </script>
</div>
{include file='globalfooter.tpl'}
