function GroupManagement(opt) {
  var options = opt;
  var activeId = null;
  var allUserList = null;
  // DataTables pagination physically removes non-visible rows from the DOM,
  // so <select> elements on other pages don't exist when the form is serialized.
  // This object tracks permission state for all resources in memory, ensuring
  // permissions on non-visible pages aren't lost on save.
  // Keys are resource IDs (e.g. "1", "37"). Values use the format
  // "{resourceId}_{permissionType}" matching the <select> option values:
  //   "1_0" = Full Access (for resource ID 1)
  //   "2_1" = View Only (for resource ID 2)
  //   "37_none" = None (for resource ID 37)
  var permissionState = {};

  var elements = {
    groupList: $('#groupList'),

    autocompleteSearch: $('#groupSearch'),
    userSearch: $('#userSearch'),

    groupUserList: $('#groupUserList'),
    membersDialog: $('#membersDialog'),
    allUsersList: $('#allUsersList'),
    permissionsDialog: $('#permissionsDialog'),
    deleteDialog: $('#deleteDialog'),
    editDialog: $('#editDialog'),
    browseUserDialog: $('#allUsers'),
    rolesDialog: $('#rolesDialog'),
    groupAdminDialog: $('#groupAdminDialog'),

    permissionsForm: $('#permissionsForm'),
    addUserForm: $('#addUserForm'),
    removeUserForm: $('#removeUserForm'),
    editGroupForm: $('#editGroupForm'),
    deleteGroupForm: $('#deleteGroupForm'),
    rolesForm: $('#rolesForm'),
    groupAdminForm: $('#groupAdminForm'),
    groupCount: $('#groupCount'),

    addForm: $('#addGroupForm'),
    addDialog: $('#addGroupDialog'),

    checkAllResourcesFull: $('#checkAllResourcesFull'),
    checkAllResourcesView: $('#checkAllResourcesView'),
    checkNoResources: $('#checkNoResources'),

    editGroupName: $('#editGroupName'),
    editGroupIsDefault: $('#editGroupIsDefault'),

    changeAdminGroupsForm: $('#groupAdminGroupsForm'),
    changeAdminResourcesForm: $('#resourceAdminForm'),
    changeAdminSchedulesForm: $('#scheduleAdminForm'),
    resourceAdminDialog: $('#resourceAdminDialog'),
    groupAdminAllDialog: $('#groupAdminAllDialog'),
    scheduleAdminDialog: $('#scheduleAdminDialog'),

    importGroupsDialog: $('#importGroupsDialog'),
    importGroupsForm: $('#importGroupsForm'),
    importGroupsTrigger: $('#import-groups'),
  };

  GroupManagement.prototype.init = function () {
    bindEventListeners();
    configureAutocomplete();
    configureAsyncForms();
  };

  var bindEventListeners = function () {
    const { groupList, browseUserDialog, addDialog } = elements;

    groupList.on('click', 'a.update', (e) => {
      e.preventDefault();
      setActiveId($(e.currentTarget));
    });

    //main interface
    $('.save').click((e) => $(e.currentTarget).closest('form').submit());
    $('.cancel').click((e) => $(e.currentTarget).closest('.modal').modal('hide'));

    $('#add-group').click((e) => {
      e.preventDefault();
      addDialog.modal('show');
      addDialog.find(':text').first().focus();
    });

    groupList.on('click', '.rename', () => editGroup());
    groupList.on('click', '.permissions', () => changePermissions());
    groupList.on('click', '.members', () => changeMembers());
    groupList.on('click', '.groupAdmin', () => changeGroupAdmin());
    groupList.on('click', '.changeAdminGroups', () => changeAdminGroups());
    groupList.on('click', '.changeAdminResources', () => changeAdminResources());
    groupList.on('click', '.changeAdminSchedules', () => changeAdminSchedules());
    groupList.on('click', '.delete', () => deleteGroup());
    groupList.on('click', '.roles', () => changeRoles());

    //user selection for group
    browseUserDialog.on('click', '.add', (e) => {
      e.preventDefault();
      const link = $(e.currentTarget);
      const userId = link.siblings('.id').val();
      addUserToGroup(userId);
      link.find('i').removeClass('bi-plus-square-fill text-success').addClass('bi-check-circle-fill text-info');
    });

    $('#browseUsers').click(() => {
      showAllUsersToAdd();
    });

    elements.groupUserList.on('click', '.delete', (e) => {
      e.preventDefault();
      var userId = $(e.currentTarget).siblings('.id').val();
      removeUserFromGroup(userId);
    });

    //ressource selection
    // Handle click to set all resources to "Full Access"
    elements.checkAllResourcesFull.click((e) => {
      e.preventDefault();
      for (var rid in permissionState) {
        permissionState[rid] = rid + '_0';
      }
      syncSelectsFromState();
    });

    // Handle click to set all resources to "View Only"
    elements.checkAllResourcesView.click((e) => {
      e.preventDefault();
      for (var rid in permissionState) {
        permissionState[rid] = rid + '_1';
      }
      syncSelectsFromState();
    });

    // Handle click to set all resources to "None"
    elements.checkNoResources.click((e) => {
      e.preventDefault();
      for (var rid in permissionState) {
        permissionState[rid] = rid + '_none';
      }
      syncSelectsFromState();
    });

    //group role
    $('.adminDialog').on('click', '.checkbox', (e) => {
      const $checkbox = $(e.currentTarget);
      const modal = $checkbox.closest('.modal-body');
      modal.find('.count').text(modal.find(':checked').length);
    });
  };

  var configureAutocomplete = function () {
    const { autocompleteSearch, userSearch } = elements;
    autocompleteSearch.autocomplete({
      source: (request, response) => {
        $.ajax({
          url: options.groupAutocompleteUrl,
          dataType: 'json',
          data: { term: request.term },
          success: (data) => {
            response(data.map((item) => ({ label: item.Name, value: item.Id })));
          },
        });
      },
      focus: (event, ui) => {
        autocompleteSearch.val(ui.item.label);
        return false;
      },
      select: (event, ui) => {
        autocompleteSearch.val(ui.item.label);
        window.location.href = `${options.selectGroupUrl}${ui.item.value}`;
        return false;
      },
    });

    userSearch.userAutoComplete(options.userAutocompleteUrl, (ui) => {
      addUserToGroup(ui.item.value);
      userSearch.val('');
    });
  };

  var configureAsyncForms = function () {
    var cleanupPermissionsForm = function () {
      elements.permissionsForm.find('select.resourceId').attr('name', 'resourceId[]');
      elements.permissionsForm.find('.injected-permission').remove();
    };

    var hidePermissionsDialog = function () {
      elements.permissionsDialog.modal('hide');
    };

    elements.permissionsDialog.on('hidden.bs.modal', cleanupPermissionsForm);

    elements.permissionsForm.on('change', 'select.resourceId', function () {
      var rid = $(this).attr('id').replace('permission_', '');
      permissionState[rid] = $(this).val();
    });

    $('#permissionsDialogtable').on('draw.dt', function () {
      syncSelectsFromState();
    });

    var error = function (errorText) {
      alert(errorText);
    };

    var importHandler = function (response) {
      if (!response) return;

      $('#importCount').text(response.importCount);
      $('#importSkipped').text(response.skippedRows.length || '0');
      $('#importResult').removeClass('d-none');

      const errors = $('#importErrors');
      errors.empty();
      if (response.messages && response.messages.length > 0) {
        const messages = response.messages.join('</li><li>');
        errors.html(`<div>${messages}</div>`).removeClass('d-none');
      }
    };

    ConfigureAsyncForm(elements.addUserForm, getSubmitCallback(options.actions.addUser), changeMembers, error);
    ConfigureAsyncForm(elements.removeUserForm, getSubmitCallback(options.actions.removeUser), changeMembers, error);
    // Inject hidden inputs for all resources before form serialization.
    // DataTables pagination removes non-visible <select> elements from the DOM,
    // so we must submit permissions via hidden inputs from permissionState instead.
    var permissionsBeforeSerialize = function (jqForm) {
      // Remove any previously injected inputs (e.g. from a failed submission retry)
      jqForm.find('.injected-permission').remove();
      // Add a hidden input for each resource that has a permission (skip "None")
      for (var rid in permissionState) {
        var val = permissionState[rid];
        if (val.indexOf('_none') === -1) {
          jqForm.append(
            $('<input>', { type: 'hidden', class: 'injected-permission', name: 'resourceId[]', value: val })
          );
        }
      }
      // Prevent visible <select> elements from also submitting, which would cause duplicates
      jqForm.find('select.resourceId').attr('name', '');
    };

    ConfigureAsyncForm(
      elements.permissionsForm,
      getSubmitCallback(options.actions.permissions),
      hidePermissionsDialog,
      error,
      { onBeforeSerialize: permissionsBeforeSerialize }
    );
    ConfigureAsyncForm(elements.editGroupForm, getSubmitCallback(options.actions.updateGroup), null, error);
    ConfigureAsyncForm(elements.deleteGroupForm, getSubmitCallback(options.actions.deleteGroup), null, error);
    ConfigureAsyncForm(elements.addForm, getSubmitCallback(options.actions.addGroup), null, error);
    ConfigureAsyncForm(elements.rolesForm, getSubmitCallback(options.actions.roles), null, error);
    ConfigureAsyncForm(elements.groupAdminForm, getSubmitCallback(options.actions.groupAdmin), null, error);
    ConfigureAsyncForm(
      elements.changeAdminGroupsForm,
      getSubmitCallback(options.actions.adminGroups),
      function () {
        elements.groupAdminAllDialog.modal('hide');
      },
      error
    );
    ConfigureAsyncForm(
      elements.changeAdminResourcesForm,
      getSubmitCallback(options.actions.resourceGroups),
      function () {
        elements.resourceAdminDialog.modal('hide');
      },
      error
    );
    ConfigureAsyncForm(
      elements.changeAdminSchedulesForm,
      getSubmitCallback(options.actions.scheduleGroups),
      function () {
        elements.scheduleAdminDialog.modal('hide');
      },
      error
    );
    ConfigureAsyncForm(elements.importGroupsForm, getSubmitCallback(options.actions.importGroups), importHandler);
  };

  var getSubmitCallback = function (action) {
    return () => `${options.submitUrl}?gid=${activeId}&action=${action}`;
  };

  var setActiveId = function (button) {
    activeId = button.parents('tr').data('group-id');
  };

  //change group details
  var editGroup = function () {
    const activeRow = elements.groupList.find(`[data-group-id="${activeId}"]`);
    elements.editGroupName.val(activeRow.find('.dataGroupName').text());
    elements.editGroupIsDefault.prop('checked', activeRow.data('group-default') === '1');
    elements.editDialog.modal('show');
  };

  var deleteGroup = function () {
    elements.deleteDialog.modal('show');
  };

  //change group members
  var changeMembers = function () {
    const groupId = activeId;
    $.getJSON(`${options.groupsUrl}?dr=groupMembers`, { gid: groupId }, (data) => {
      const items = [];
      const userIds = [];

      $('#totalUsers').text(data.Total);
      if (data.Users) {
        data.Users.forEach((item) => {
          items.push(
            `<div><a href="#" class="delete"><i class="bi bi-x-square-fill text-danger"></i></a> ${item.DisplayName}<input type="hidden" class="id" value="${item.Id}"/></div>`
          );
          userIds[item.Id] = item.Id;
        });
      }

      elements.groupUserList.empty();
      elements.groupUserList.data('userIds', userIds);

      $('<div/>', { html: items.join('') }).appendTo(elements.groupUserList);
    });
    elements.membersDialog.modal('show');
  };

  var addUserToGroup = function (userId) {
    elements.addUserForm.find('#addUserId').val(userId);
    elements.addUserForm.submit();
  };

  var removeUserFromGroup = function (userId) {
    elements.removeUserForm.find('#removeUserId').val(userId);
    elements.removeUserForm.submit();
  };

  var showAllUsersToAdd = function () {
    elements.membersDialog.modal('hide');
    elements.allUsersList.empty();

    if (!allUserList) {
      $.ajax({
        url: options.userAutocompleteUrl,
        dataType: 'json',
        async: false,
        success: (data) => {
          allUserList = data;
        },
      });
    }

    const items = [];
    if (allUserList) {
      allUserList.forEach((item) => {
        if (!elements.groupUserList.data('userIds')[item.Id]) {
          items.push(
            `<div><a class="add"><i class="bi bi-plus-square-fill text-success"></i></a> ${item.DisplayName}<input type="hidden" class="id" value="${item.Id}"/></div>`
          );
        } else {
          items.push(
            `<div><i class="bi bi-check-circle-fill me-1 text-info"></i><span>${item.DisplayName}</span></div>`
          );
        }
      });
    }

    $('<div/>', { html: items.join('') }).appendTo(elements.allUsersList);
    elements.browseUserDialog.modal('show');
  };

  // Update visible <select> dropdowns on the current DataTable page to match permissionState.
  // Each select has an id like "permission_37" — the resource ID is extracted by stripping
  // the "permission_" prefix (e.g. "permission_37" → "37"), then used to look up the value
  // in permissionState.
  var syncSelectsFromState = function () {
    var table = $('#permissionsDialogtable').DataTable();
    table.rows({ page: 'current' }).every(function () {
      var select = $(this.node()).find('select.resourceId');
      if (select.length) {
        var rid = select.attr('id').replace('permission_', '');
        if (permissionState[rid] !== undefined) {
          select.val(permissionState[rid]);
        }
      }
    });
  };

  //change group ressource permission
  var changePermissions = function () {
    $.get(options.permissionsUrl, { dr: options.dataRequests.permissions, gid: activeId }, (permissions) => {
      permissionState = {};

      var table = $('#permissionsDialogtable').DataTable();
      table.rows({ search: 'none' }).every(function () {
        var select = $(this.node()).find('select.resourceId');
        if (select.length) {
          var rid = select.attr('id').replace('permission_', '');
          permissionState[rid] = rid + '_none';
        }
      });

      (permissions.full || []).forEach((id) => {
        permissionState[id] = id + '_0';
      });

      (permissions.view || []).forEach((id) => {
        permissionState[id] = id + '_1';
      });

      syncSelectsFromState();
      elements.permissionsDialog.modal('show');
    });
  };

  //change group admin
  var changeGroupAdmin = function () {
    const activeRow = elements.groupList.find(`[data-group-id="${activeId}"]`);
    const currentGroupAdmin = activeRow.find('.groupAdmin').text();
    const currentGroupAdminVal = elements.groupAdminForm
      .find('select')
      .find('option')
      .filter(function () {
        return $(this).html() === currentGroupAdmin;
      })
      .val();

    elements.groupAdminForm.find('select').val(currentGroupAdminVal || '');
    elements.groupAdminDialog.modal('show');
  };

  //change group role
  var changeRoles = function () {
    const groupId = activeId;

    $.get(options.rolesUrl, { dr: options.dataRequests.roles, gid: groupId }, (roleIds) => {
      elements.rolesForm.find(':checkbox').prop('checked', false);
      roleIds.forEach((value) => {
        elements.rolesForm.find(`:checkbox[value="${value}"]`).prop('checked', true);
      });

      elements.rolesDialog.modal('show');
    });
  };

  var changeAdminGroups = function () {
    populateAdminCheckboxes(
      options.dataRequests.adminGroups,
      elements.changeAdminGroupsForm,
      elements.groupAdminAllDialog
    );
  };

  var changeAdminResources = function () {
    populateAdminCheckboxes(
      options.dataRequests.resourceGroups,
      elements.changeAdminResourcesForm,
      elements.resourceAdminDialog
    );
  };

  var changeAdminSchedules = function () {
    populateAdminCheckboxes(
      options.dataRequests.scheduleGroups,
      elements.changeAdminSchedulesForm,
      elements.scheduleAdminDialog
    );
  };

  var populateAdminCheckboxes = function (dr, form, dialog) {
    dialog.find('.count').text(dialog.find(':checked').length);

    $.get(options.submitUrl, { dr, gid: activeId }, (groupIds) => {
      form.find(':checkbox').prop('checked', false);
      groupIds.forEach((value) => {
        form.find(`:checkbox[value="${value}"]`).prop('checked', true);
      });

      dialog.find('.count').text(groupIds.length);
      dialog.modal('show');
    });
  };
}
