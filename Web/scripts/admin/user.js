function UserManagement(opts) {
  var options = opts;

  var elements = {
    activeId: $('#activeId'),
    userList: $('#userList_wrapper'),

    userAutocomplete: $('#userSearch'),
    filterStatusId: $('#filterStatusId'),

    permissionsDialog: $('#permissionsDialog'),
    passwordDialog: $('#passwordDialog'),

    attributeForm: $('.attributesForm'),

    permissionsForm: $('#permissionsForm'),
    passwordForm: $('#passwordForm'),

    userDialog: $('#userDialog'),
    userForm: $('#userForm'),

    groupsDialog: $('#groupsDialog'),
    addedGroups: $('#addedGroups'),
    removedGroups: $('#removedGroups'),
    groupList: $('#groupList'),
    addGroupForm: $('#addGroupForm'),
    removeGroupForm: $('#removeGroupForm'),
    groupCount: $('#groupCount'),

    colorDialog: $('#colorDialog'),
    colorValue: $('#reservationColor'),
    colorForm: $('#colorForm'),

    addUserForm: $('#addUserForm'),

    importUsersForm: $('#importUsersForm'),
    importUsersDialog: $('#importUsersDialog'),

    deleteDialog: $('#deleteDialog'),
    deleteUserForm: $('#deleteUserForm'),

    addDialog: $('#addUserDialog'),

    invitationDialog: $('#invitationDialog'),
    invitationForm: $('#invitationForm'),
    inviteEmails: $('#inviteEmails'),

    checkAllResourcesFull: $('#checkAllResourcesFull'),
    checkAllResourcesView: $('#checkAllResourcesView'),
    checkNoResources: $('#checkNoResources'),

    deleteMultiplePrompt: $('#delete-selected'),
    deleteMultipleDialog: $('#deleteMultipleDialog'),
    deleteMultipleUserForm: $('#deleteMultipleUserForm'),
    deleteMultipleSelectAll: $('#delete-all'),
    deleteMultipleCount: $('#deleteMultipleCount'),
    deleteMultiplePlaceHolder: $('#deleteMultiplePlaceHolder'),
  };

  var users = {};
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

  UserManagement.prototype.init = function () {
    elements.userList.on('click', '.update', function (e) {
      setActiveUserElement($(this));
      e.preventDefault();
    });

    elements.userList.on('click', '.changeStatus', function (e) {
      changeStatus($(this));
    });

    elements.userList.on('click', '.changeGroups', function (e) {
      changeGroups();
    });

    elements.userList.on('click', '.changePermissions', function (e) {
      changePermissions();
    });

    elements.userList.on('click', '.resetPassword', function (e) {
      elements.passwordDialog.find(':password').val('');
      elements.passwordDialog.modal('show');
    });

    elements.userList.on('click', '.changeColor', 'click', function (e) {
      var user = getActiveUser();
      elements.colorValue.val(user.reservationColor);
      elements.colorDialog.modal('show');
    });

    elements.userList.on('click', '.edit', function () {
      changeUserInfo();
    });

    elements.userList.on('click', '.delete', function (e) {
      deleteUser();
    });

    elements.userList.on('click', '.viewReservations', function (e) {
      var user = getActiveUser();
      var name = encodeURI(user.first + ' ' + user.last);
      var url = options.manageReservationsUrl + '?uid=' + user.id + '&un=' + name;
      window.location.href = url;
    });

    elements.userList.on('click', '.changeAttribute', function (e) {
      e.stopPropagation();
      $(e.target).closest('.updateCustomAttribute').find('.inlineAttribute').editable('toggle');
    });

    elements.userList.find('.changeCredits').click(function (e) {
      e.stopPropagation();
      $(this).editable('toggle');
    });

    elements.userAutocomplete.userAutoComplete(options.userAutocompleteUrl, function (ui) {
      elements.userAutocomplete.val(ui.item.label);
      window.location.href = options.selectUserUrl + ui.item.value;
    });

    elements.filterStatusId.change(function () {
      var statusid = $(this).val();
      window.location.href = options.filterUrl + statusid;
    });

    elements.addedGroups.on('click', 'div', function (e) {
      e.preventDefault();
      $('#removeGroupId').val($(this).attr('groupId'));
      $('#removeGroupUserId').val(getActiveUserId());
      elements.removeGroupForm.submit();

      var count = elements.groupCount.text();
      elements.groupCount.text(--count);

      $(this).appendTo(elements.removedGroups);
    });

    elements.removedGroups.on('click', 'div', function (e) {
      e.preventDefault();
      $('#addGroupId').val($(this).attr('groupId'));
      $('#addGroupUserId').val(getActiveUserId());
      elements.addGroupForm.submit();

      var count = elements.groupCount.text();
      elements.groupCount.text(++count);

      $(this).appendTo(elements.addedGroups);
    });

    // Handle click to set all resources to "Full Access"
    elements.checkAllResourcesFull.click(function (e) {
      e.preventDefault();
      for (var rid in permissionState) {
        permissionState[rid] = rid + '_0';
      }
      syncSelectsFromState();
    });

    // Handle click to set all resources to "View Only"
    elements.checkAllResourcesView.click(function (e) {
      e.preventDefault();
      for (var rid in permissionState) {
        permissionState[rid] = rid + '_1';
      }
      syncSelectsFromState();
    });

    // Handle click to set all resources to "None"
    elements.checkNoResources.click(function (e) {
      e.preventDefault();
      for (var rid in permissionState) {
        permissionState[rid] = rid + '_none';
      }
      syncSelectsFromState();
    });

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('.cancel').click(function () {
      $(this).closest('.dialog').dialog('close');
    });

    $('.clearform').click(function () {
      $(this).closest('form')[0].reset();
    });

    $('#add-user').click(function (e) {
      e.preventDefault();
      elements.addDialog.modal('show');
    });

    $('#invite-users').click(function (e) {
      e.preventDefault();
      elements.invitationDialog.modal('show');
    });

    $('#import-users').click(function (e) {
      e.preventDefault();
      $('#importErrors').empty().addClass('d-none');
      $('#importResults').addClass('d-none');
      elements.importUsersDialog.modal('show');
    });

    elements.deleteMultiplePrompt.click(function (e) {
      e.preventDefault();
      var checked = elements.userList.find('.delete-multiple:checked');
      elements.deleteMultipleCount.text(checked.length);
      elements.deleteMultiplePlaceHolder.empty();
      elements.deleteMultiplePlaceHolder.append(checked.clone());
      elements.deleteMultipleDialog.modal('show');
    });

    elements.deleteMultipleSelectAll.click(function (e) {
      e.stopPropagation();
      var isChecked = elements.deleteMultipleSelectAll.is(':checked');
      elements.userList.find('.delete-multiple').prop('checked', isChecked);
      elements.deleteMultiplePrompt.toggleClass('d-none', !isChecked);
    });

    elements.userList.on('click', '.delete-multiple', function (e) {
      e.stopPropagation();
      var numberChecked = elements.userList.find('.delete-multiple:checked').length;
      var allSelected = numberChecked == elements.userList.find('.delete-multiple').length;
      elements.deleteMultipleSelectAll.prop('checked', allSelected);
      elements.deleteMultiplePrompt.toggleClass('d-none', numberChecked == 0);
    });

    var cleanupPermissionsForm = function () {
      elements.permissionsForm.find('select.resourceId').attr('name', 'resourceId[]');
      elements.permissionsForm.find('.injected-permission').remove();
    };

    var hidePermissionsDialog = function () {
      hideDialog(elements.permissionsDialog);
    };

    elements.permissionsDialog.on('hidden.bs.modal', cleanupPermissionsForm);

    elements.permissionsForm.on('change', 'select.resourceId', function () {
      var rid = $(this).attr('id').replace('permission_', '');
      permissionState[rid] = $(this).val();
    });

    $('#permissionsFormFilter').on('draw.dt', function () {
      syncSelectsFromState();
    });

    var hidePasswordDialog = function () {
      hideDialog(elements.passwordDialog);
    };

    var hideDialog = function (dialogElement) {
      dialogElement.modal('hide');
    };

    var hideDialogCallback = function (dialogElement) {
      return function () {
        hideDialog(dialogElement);
        window.location.reload();
      };
    };

    var error = function (errorText) {
      alert(errorText);
    };

    var importHandler = function (responseText, form) {
      if (!responseText) {
        return;
      }

      $('#importCount').text(responseText.importCount);
      $('#importSkipped').text(responseText.skippedRows.length > 0 ? responseText.skippedRows.join(',') : '0');
      $('#importResult').removeClass('d-none');

      var errors = $('#importErrors');
      errors.empty();
      if (responseText.messages && responseText.messages.length > 0) {
        var messages = responseText.messages.join('</li><li>');
        errors.html('<div>' + messages + '</div>').removeClass('d-none');
      }
    };

    var inviteHandler = function (responseText, form) {
      elements.inviteEmails.val('');
      elements.invitationDialog.modal('hide');
    };

    $('#addOrganization').orgAutoComplete(options.orgAutoCompleteUrl);
    $('#organization').orgAutoComplete(options.orgAutoCompleteUrl);

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
      defaultSubmitCallback(elements.permissionsForm),
      hidePermissionsDialog,
      error,
      { onBeforeSerialize: permissionsBeforeSerialize }
    );
    ConfigureAsyncForm(elements.passwordForm, defaultSubmitCallback(elements.passwordForm), hidePasswordDialog, error);
    ConfigureAsyncForm(
      elements.userForm,
      defaultSubmitCallback(elements.userForm),
      hideDialogCallback(elements.userDialog)
    );
    ConfigureAsyncForm(
      elements.deleteUserForm,
      defaultSubmitCallback(elements.deleteUserForm),
      hideDialogCallback(elements.deleteDialog),
      error
    );
    ConfigureAsyncForm(
      elements.addUserForm,
      defaultSubmitCallback(elements.addUserForm),
      hideDialogCallback(elements.addDialog)
    );
    ConfigureAsyncForm(elements.colorForm, defaultSubmitCallback(elements.colorForm));
    ConfigureAsyncForm(elements.importUsersForm, defaultSubmitCallback(elements.importUsersForm), importHandler);
    ConfigureAsyncForm(elements.addGroupForm, changeGroupUrlCallback(elements.addGroupForm), function () {});
    ConfigureAsyncForm(elements.removeGroupForm, changeGroupUrlCallback(elements.removeGroupForm), function () {});
    ConfigureAsyncForm(elements.invitationForm, defaultSubmitCallback(elements.invitationForm), inviteHandler);
    ConfigureAsyncForm(elements.deleteMultipleUserForm, defaultSubmitCallback(elements.deleteMultipleUserForm));
  };

  UserManagement.prototype.addUser = function (user) {
    users[user.id] = user;
  };

  var getSubmitCallback = function (action) {
    return function () {
      return options.submitUrl + '?uid=' + getActiveUserId() + '&action=' + action;
    };
  };

  var defaultSubmitCallback = function (form) {
    return function () {
      return options.submitUrl + '?action=' + form.attr('ajaxAction') + '&uid=' + getActiveUserId();
    };
  };

  var changeGroupUrlCallback = function (form) {
    return function () {
      return options.groupManagementUrl + '?action=' + form.attr('ajaxAction') + '&uid=' + getActiveUserId();
    };
  };

  function setActiveUserElement(activeElement) {
    var id = activeElement.closest('tr').attr('data-userId');
    setActiveUserId(id);
  }

  function setActiveUserId(id) {
    elements.activeId.val(id);
  }

  function getActiveUserId() {
    return elements.activeId.val();
  }

  function getActiveUser() {
    return users[getActiveUserId()];
  }

  var changeStatus = function (statusButtonElement) {
    var user = getActiveUser();

    function changeStatusResultCallback(resultStatusText) {
      user.isActive = !user.isActive;
      elements.userList
        .find('[data-userId="' + user.id + '"]')
        .find('.changeStatus')
        .text(resultStatusText);
    }

    if (user.isActive) {
      PerformAsyncAction(
        statusButtonElement,
        getSubmitCallback(options.actions.deactivate),
        $('#userStatusIndicator'),
        changeStatusResultCallback
      );
    } else {
      PerformAsyncAction(
        statusButtonElement,
        getSubmitCallback(options.actions.activate),
        $('#userStatusIndicator'),
        changeStatusResultCallback
      );
    }
  };

  var changeGroups = function () {
    elements.addedGroups.find('.group-item').remove();
    elements.removedGroups.find('.group-item').remove();

    var user = getActiveUser();
    var data = { dr: 'groups', uid: user.id };
    $.get(opts.groupsUrl, data, function (groupIds) {
      elements.groupList.find('.group-item').clone().appendTo(elements.removedGroups);

      var count = 0;

      $.each(groupIds, function (index, value) {
        var groupLine = elements.removedGroups.find('div[groupId=' + value + ']');
        groupLine.appendTo(elements.addedGroups);
        count++;
      });

      elements.groupCount.text(count);
    });

    elements.groupsDialog.modal('show');
  };

  // Update visible <select> dropdowns on the current DataTable page to match permissionState.
  // Each select has an id like "permission_37" — the resource ID is extracted by stripping
  // the "permission_" prefix (e.g. "permission_37" → "37"), then used to look up the value
  // in permissionState.
  var syncSelectsFromState = function () {
    var table = $('#permissionsFormFilter').DataTable();
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

  var changePermissions = function () {
    var user = getActiveUser();
    var data = { dr: 'permissions', uid: user.id };
    $.get(opts.permissionsUrl, data, function (permissions) {
      permissionState = {};

      var table = $('#permissionsFormFilter').DataTable();
      table.rows({ search: 'none' }).every(function () {
        var select = $(this.node()).find('select.resourceId');
        if (select.length) {
          var rid = select.attr('id').replace('permission_', '');
          permissionState[rid] = rid + '_none';
        }
      });

      (permissions.full || []).forEach(function (value) {
        permissionState[value] = value + '_0';
      });

      (permissions.view || []).forEach(function (value) {
        permissionState[value] = value + '_1';
      });

      syncSelectsFromState();
      elements.permissionsDialog.modal('show');
    });
  };

  var changeUserInfo = function () {
    var user = getActiveUser();
    var data = { dr: 'update', uid: user.id };
    $.get(opts.submitUrl, data, (response) => {
      $('#update-user-placeholder').html(response);
    });
    // var user = getActiveUser();

    // ClearAsyncErrors(elements.userDialog);
    //
    // $('#username').val(user.username);
    // $('#fname').val(user.first);
    // $('#lname').val(user.last);
    // $('#email').val(user.email);
    // $('#timezone').val(user.timezone);
    //
    // $('#phone').val(user.phone);
    // $('#organization').val(user.organization);
    // $('#position').val(user.position);

    elements.userDialog.modal('show');
  };

  var deleteUser = function () {
    elements.deleteDialog.modal('show');
  };
}
