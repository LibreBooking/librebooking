{include file='globalheader.tpl' DataTable=true}

<div id="page-manage-groups" class="admin-page">
    <div class="border-bottom mb-3 clearfix">
        <div class="dropdown admin-header-more float-end">
            <div class="btn-group btn-group-sm">
                <a role="menuitem" href="#" class="add-group btn btn-primary" id="add-group"><i
                        class="bi bi-plus-circle-fill me-1 icon add"></i>{translate key="AddGroup"}

                </a>
                <button class="btn btn-primary dropdown-toggle" type="button" id="moreResourceActions"
                    data-bs-toggle="dropdown">
                    <span class="visually-hidden">{translate key='More'}</span>
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="moreResourceActions">
                    <li role="presentation">
                        <a role="menuitem" href="#" class="import-groups dropdown-item" id="import-groups"
                            data-bs-toggle="modal" data-bs-target="#importGroupsDialog">
                            <i class="bi bi-download me-1"></i>{translate key="Import"}
                        </a>
                    </li>
                    <li role="presentation">
                        <a role="menuitem" href="{$smarty.server.SCRIPT_NAME}?dr=export"
                            download="{$smarty.server.SCRIPT_NAME}?dr=export" class="export-groups dropdown-item"
                            id="export-groups" target="_blank"> <i
                                class="bi bi-upload me-1"></i>{translate key="Export"}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <h1>{translate key='ManageGroups'}</h1>
    </div>

    <div class="card shadow mb-2" id="groupSearchPanel">
        <div class="card-body">
            <label for="groupSearch">{translate key='FindGroup'}</label>
            | {html_link href=$smarty.server.SCRIPT_NAME key=AllGroups}
            <input type="text" id="groupSearch" class="form-control" size="40" />
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            {assign var=tableId value=groupList}
            <table class="table table-striped table-hover border-top" id="{$tableId}">
                <thead>
                    <tr>
                        <th>{translate key='GroupName'}</th>
                        <th>{translate key='GroupMembers'}</th>
                        <th>{translate key='Permissions'}</th>
                        {if $CanChangeRoles}
                            <th>{translate key='GroupRoles'}</th>
                        {/if}
                        <th>{translate key='GroupAdmin'}</th>
                        <th>{translate key='GroupAutomaticallyAdd'}</th>
                        <th class="action">{translate key='Actions'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$groups item=group}
                        <tr data-group-id="{$group->Id}" data-group-default="{$group->IsDefault}">
                            <td class="dataGroupName">{$group->Name}</td>
                            <td><a href="#" class="update members link-primary">{translate key='Manage'}</a></td>
                            <td><a href="#" class="update permissions link-primary">{translate key='Change'}</a></td>
                            {if $CanChangeRoles}
                                <td>
                                    {if $group->IsExtendedAdmin()}
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="update roles btn btn-outline-primary">{translate key='Change'}</a>
                                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <span class="visually-hidden">{translate key=More}</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                <li>
                                                    <h6 class="dropdown-header">{translate key=Administration}</h6>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                {if $group->IsGroupAdmin()}
                                                    <li role="presentation">
                                                        <a role="menuitem" href="#"
                                                            class="update changeAdminGroups dropdown-item">{translate key="Groups"}</a>
                                                    </li>
                                                {/if}
                                                {if $group->IsResourceAdmin()}
                                                    <li role="presentation">
                                                        <a role="menuitem" href="#"
                                                            class="update changeAdminResources dropdown-item">{translate key="Resources"}</a>
                                                    </li>
                                                {/if}
                                                {if $group->IsScheduleAdmin()}
                                                    <li role="presentation">
                                                        <a role="menuitem" href="#"
                                                            class="update changeAdminSchedules dropdown-item">{translate key="Schedules"}</a>
                                                    </li>
                                                {/if}
                                            </ul>
                                        </div>
                                    {else}
                                        <a href="#" class="update roles link-primary">{translate key='Add'}</a>
                                    {/if}
                                </td>
                            {/if}
                            <td><a href="#"
                                    class="update groupAdmin link-primary">{$group->AdminGroupName|default:$chooseText}</a>
                            </td>
                            <td>{if $group->IsDefault}
                                    <i class="bi bi-check-circle text-success"></i>
                                {else}
                                    <span class="bi bi-x-circle text-danger"></span>
                                {/if}
                            </td>
                            <td class="action">
                                <a href="#" class="update rename link-primary"><span class="bi bi-pencil-square icon"></a> |
                                <a href="#" class="update delete"><span
                                        class="bi bi-trash3-fill text-danger icon remove"></span></a>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" id="activeId" />

    <div class="modal fade" id="membersDialog" tabindex="-1" role="dialog" aria-labelledby="membersDialogLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="membersDialogLabel">{translate key=GroupMembers}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label class="fw-bold" for="userSearch">{translate key=AddUser}: </label>
                        <a href="#" id="browseUsers" class="link-primary">{translate key=Browse}</a>
                        <input type="text" id="userSearch" class="form-control" size="40" />
                    </div>
                    <h5><span id="totalUsers"></span> {translate key=UsersInGroup}</h5>

                    <div id="groupUserList"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary cancel"
                        data-bs-dismiss="modal">{translate key='Done'}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="allUsers" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="browseUsersDialogLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="browseUsersDialogLabel">{translate key=AllUsers}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div id="allUsersList"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="permissionsDialog" tabindex="-1" role="dialog" aria-labelledby="permissionsDialogLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionsDialogLabel">{translate key=Permissions}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="permissionsForm" method="post">
                        <div class="d-flex align-items-center gap-1">
                            <a href="#" id="checkNoResources" class="link-primary">{translate key=None}</a>
                            <div class="vr"></div>
                            <a href="#" id="checkAllResourcesFull" class="link-primary">{translate key=FullAccess}</a>
                            <div class="vr"></div>
                            <a href="#" id="checkAllResourcesView" class="link-primary">{translate key=ViewOnly}</a>
                        </div>

                        {assign var=tableIdFilter value="permissionsDialogtable"}
                        <table class="table table-striped table-hover overflow-auto w-100" id="{$tableIdFilter}">
                            <thead class="visually-hidden">
                                <tr>
                                    <th>{translate key="Resource"}</th>
                                </tr>
                            </thead>
                            {foreach from=$resources item=resource}
                                {assign var=rid value=$resource->GetResourceId()}
                                <tr>
                                    <td>
                                        <div class="permissionRow form-group clearfix">
                                            <label for="permission_{$rid}"
                                                class="float-start">{$resource->GetName()}</label>
                                            <select class="form-select form-select-sm w-auto resourceId float-end"
                                                {formname key=RESOURCE_ID multi=true}id="permission_{$rid}">
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
                    {update_button form='permissionsForm' submit="true"}
                    {indicator}
                </div>
            </div>
        </div>
    </div>

    <form id="removeUserForm" method="post">
        <input type="hidden" id="removeUserId" {formname key=USER_ID} />
    </form>

    <form id="addUserForm" method="post">
        <input type="hidden" id="addUserId" {formname key=USER_ID} />
    </form>

    <div class="modal fade" id="addGroupDialog" tabindex="-1" role="dialog" aria-labelledby="addDialogLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="addGroupForm" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDialogLabel">{translate key=AddGroup}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div id="addGroupResults" class="error" style="display:none;"></div>
                        <div class="form-group">
                            <label class="fw-bold" for="addGroupName">{translate key=Name}<i
                                    class="bi bi-asterisk text-danger align-top" style="font-size: 0.5rem;"></i></label>
                            <input {formname key=GROUP_NAME} type="text" id="addGroupName" required
                                class="form-control required has-feedback" />
                        </div>
                        <div class="form-group mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="addGroupIsDefault"
                                    {formname key=IS_DEFAULT} />
                                <label class="form-check-label"
                                    for="addGroupIsDefault">{translate key=AutomaticallyAddToGroup}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {add_button}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="deleteDialog" tabindex="-1" role="dialog" aria-labelledby="deleteDialogLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteGroupForm" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteDialogLabel">{translate key=Delete}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <div>{translate key=DeleteWarning}</div>
                            <div>{translate key=DeleteGroupWarning}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {delete_button}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editDialog" tabindex="-1" role="dialog" aria-labelledby="editDialogLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="editGroupForm" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDialogLabel">{translate key=Update}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="fw-bold" for="editGroupName">{translate key=Name}<i
                                    class="bi bi-asterisk text-danger align-top form-control-feedback"
                                    style="font-size: 0.5rem;"></i></label>
                            <input type="text" id="editGroupName" class="form-control required has-feedback" required
                                {formname key=GROUP_NAME} />
                        </div>
                        <div class="form-group mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editGroupIsDefault"
                                    {formname key=IS_DEFAULT} />
                                <label class="form-check-label"
                                    for="editGroupIsDefault">{translate key=AutomaticallyAddToGroup}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {update_button}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>

    {if $CanChangeRoles}
        <div class="modal fade" id="rolesDialog" tabindex="-1" role="dialog" aria-labelledby="rolesDialogLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form id="rolesForm" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rolesDialogLabel">{translate key=WhatRolesApplyToThisGroup}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body">
                            {foreach from=$Roles item=role}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="role{$role->Id}"
                                        {formname key=ROLE_ID multi=true} value="{$role->Id}" />
                                    <label class="form-check-label" for="role{$role->Id}">{$role->Name}</label>
                                </div>
                            {/foreach}
                        </div>
                        <div class="modal-footer">
                            {cancel_button}
                            {update_button}
                            {indicator}
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade adminDialog" id="resourceAdminDialog" tabindex="-1" role="dialog"
            aria-labelledby="resourceAdminDialogLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resourceAdminDialogLabel">{translate key=WhatCanThisGroupManage}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form id="resourceAdminForm" method="post">
                            <h4><span class="count"></span> {translate key=Resources}</h4>

                            {foreach from=$resources item=resource}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="resource{$resource->GetId()}"
                                        {formname key=RESOURCE_ID multi=true} value="{$resource->GetId()}" />
                                    <label class="form-check-label"
                                        for="resource{$resource->GetId()}">{$resource->GetName()}</label>
                                </div>
                            {/foreach}
                        </form>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {update_button form="resourceAdminForm" submit=true}
                        {indicator}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade adminDialog" id="groupAdminAllDialog" tabindex="-1" role="dialog"
            aria-labelledby="groupAdminAllDialogLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="groupAdminGroupsForm" method="post">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="groupAdminAllDialogLabel">{translate key=WhatCanThisGroupManage}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body scrollable-modal-content">
                            <h4><span class="count"></span> {translate key=Groups}</h4>

                            {foreach from=$groups item=group}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="group{$group->Id}"
                                        {formname key=GROUP_ID multi=true}" value="{$group->Id}" />
                            <label class="form-check-label" for="group{$group->Id}">{$group->Name}</label>
                        </div>
                        {/foreach}
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {update_button}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade adminDialog" id="scheduleAdminDialog" tabindex="-1" role="dialog"
        aria-labelledby="scheduleAdminDialogLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleAdminAllDialogLabel">{translate key=WhatCanThisGroupManage}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="scheduleAdminForm" method="post">
                        <h4><span class="count"></span> {translate key=Schedules}</h4>

                        {foreach from=$Schedules item=schedule}
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="schedule{$schedule->GetId()}"
                                {formname key=SCHEDULE_ID multi=true} value="{$schedule->GetId()}" />
                            <label class="form-check-label"
                                for="schedule{$schedule->GetId()}">{$schedule->GetName()}</label>
                        </div>
                        {/foreach}
                    </form>
                </div>
                <div class="modal-footer">
                    {cancel_button}
                    {update_button form="scheduleAdminForm" submit="true"}
                    {indicator}
                </div>
            </div>
        </div>
    </div>
    {/if}

    <div class="modal fade" id="groupAdminDialog" tabindex="-1" role="dialog" aria-labelledby="groupAdminDialogLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="groupAdminForm" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="groupAdminDialogLabel">{translate key=WhoCanManageThisGroup}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group has-feedback">
                            <label for="groupAdmin"
                                class="off-screen fw-bold">{translate key=WhoCanManageThisGroup}</label>
                            <select {formname key=GROUP_ADMIN} class="form-select" id="groupAdmin">
                                <option value="">-- {translate key=None} --</option>
                                {foreach from=$AdminGroups item=adminGroup}
                                <option value="{$adminGroup->Id}">{$adminGroup->Name}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {cancel_button}
                        {update_button}
                        {indicator}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="importGroupsDialog" class="modal" tabindex="-1" role="dialog" aria-labelledby="importGroupsModalLabel"
        aria-hidden="true">
        <form id="importGroupsForm" class="form" role="form" method="post" enctype="multipart/form-data"
            ajaxAction="{ManageGroupsActions::Import}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importGroupsModalLabel">{translate key=Import}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div id="importInstructions" class="alert alert-info">
                            <div class="note fst-italic">{translate key=GroupsImportInstructions}</div>
                            <a href="{$smarty.server.SCRIPT_NAME}?dr=template" class="alert-link"
                                download="{$smarty.server.SCRIPT_NAME}?dr=template"
                                target="_blank">{translate key=GetTemplate} <span class="bi bi-download"></span></a>
                        </div>
                        <div id="importGroupsResults" class="validationSummary alert alert-danger d-none">
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
                        <div class="">
                            <input type="file" class="form-control" {formname key=GROUP_IMPORT_FILE}
                                id="groupsImportFile" />
                            <label for="groupsImportFile" class="visually-hidden">Group Import File</label>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" id="updateOnImport"
                                    {formname key=UPDATE_ON_IMPORT} />
                                <label class="form-check-label"
                                    for="updateOnImport">{translate key=UpdateGroupsOnImport}</label>
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

    {csrf_token}

    {include file="javascript-includes.tpl" DataTable=true}
    {datatable tableId=$tableId}
    {datatablefilter tableId=$tableIdFilter}
    {jsfile src="ajax-helpers.js"}
    {jsfile src="autocomplete.js"}
    {jsfile src="admin/group.js"}
    {jsfile src="js/jquery.form-3.09.min.js"}


    <script type="text/javascript">
        $(document).ready(function() {

            var actions = {
                activate: '{ManageGroupsActions::Activate}',
                deactivate: '{ManageGroupsActions::Deactivate}',
                permissions: '{ManageGroupsActions::Permissions}',
                password: '{ManageGroupsActions::Password}',
                removeUser: '{ManageGroupsActions::RemoveUser}',
                addUser: '{ManageGroupsActions::AddUser}',
                addGroup: '{ManageGroupsActions::AddGroup}',
                updateGroup: '{ManageGroupsActions::UpdateGroup}',
                deleteGroup: '{ManageGroupsActions::DeleteGroup}',
                roles: '{ManageGroupsActions::Roles}',
                groupAdmin: '{ManageGroupsActions::GroupAdmin}',
                adminGroups: '{ManageGroupsActions::AdminGroups}',
                resourceGroups: '{ManageGroupsActions::ResourceGroups}',
                scheduleGroups: '{ManageGroupsActions::ScheduleGroups}',
                importGroups: '{ManageGroupsActions::Import}'
            };

            var dataRequests = {
                permissions: 'permissions',
                roles: 'roles',
                groupMembers: 'groupMembers',
                adminGroups: '{ManageGroupsActions::AdminGroups}',
                resourceGroups: '{ManageGroupsActions::ResourceGroups}',
                scheduleGroups: '{ManageGroupsActions::ScheduleGroups}'
            };

            var groupOptions = {
                userAutocompleteUrl: "../ajax/autocomplete.php?type={AutoCompleteType::User}",
                groupAutocompleteUrl: "../ajax/autocomplete.php?type={AutoCompleteType::Group}",
                groupsUrl: "{$smarty.server.SCRIPT_NAME}",
                        permissionsUrl: '{$smarty.server.SCRIPT_NAME}',
                        rolesUrl: '{$smarty.server.SCRIPT_NAME}',
                        submitUrl: '{$smarty.server.SCRIPT_NAME}',
                        saveRedirect: '{$smarty.server.SCRIPT_NAME}',
                        selectGroupUrl: '{$smarty.server.SCRIPT_NAME}?gid=',
                        actions: actions,
                        dataRequests: dataRequests
                    };

                    var groupManagement = new GroupManagement(groupOptions);
                    groupManagement.init();

                    //$('#add-group-panel').showHidePanel();
                });
            </script>
        </div>
        {include file='globalfooter.tpl'}