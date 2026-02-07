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
		deleteMultipleCheckboxes: $('.delete-multiple'),
		deleteMultipleSelectAll: $('#delete-all'),
		deleteMultipleCount: $('#deleteMultipleCount'),
		deleteMultiplePlaceHolder: $('#deleteMultiplePlaceHolder')
	};

	var users = {};

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
			window.location.href = options.selectUserUrl + ui.item.value
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

		elements.checkAllResourcesFull.click(function (e) {
			e.preventDefault();
			elements.permissionsDialog.find('.full').prop('selected', true)
		});

		elements.checkAllResourcesView.click(function (e) {
			e.preventDefault();
			elements.permissionsDialog.find('.view').prop('selected', true)
		});

		elements.checkNoResources.click(function (e) {
			e.preventDefault();
			elements.permissionsDialog.find('.none').prop('selected', true)
		});

		$(".save").click(function () {
			$(this).closest('form').submit();
		});

		$(".cancel").click(function () {
			$(this).closest('.dialog').dialog("close");
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
			var isChecked = elements.deleteMultipleSelectAll.is(":checked");
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

		var hidePermissionsDialog = function () {
			hideDialog(elements.permissionsDialog);
		};

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
			}
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

		ConfigureAsyncForm(elements.permissionsForm, defaultSubmitCallback(elements.permissionsForm), hidePermissionsDialog, error);
		ConfigureAsyncForm(elements.passwordForm, defaultSubmitCallback(elements.passwordForm), hidePasswordDialog, error);
		ConfigureAsyncForm(elements.userForm, defaultSubmitCallback(elements.userForm), hideDialogCallback(elements.userDialog));
		ConfigureAsyncForm(elements.deleteUserForm, defaultSubmitCallback(elements.deleteUserForm), hideDialogCallback(elements.deleteDialog), error);
		ConfigureAsyncForm(elements.addUserForm, defaultSubmitCallback(elements.addUserForm), hideDialogCallback(elements.addDialog));
		ConfigureAsyncForm(elements.colorForm, defaultSubmitCallback(elements.colorForm));
		ConfigureAsyncForm(elements.importUsersForm, defaultSubmitCallback(elements.importUsersForm), importHandler);
		ConfigureAsyncForm(elements.addGroupForm, changeGroupUrlCallback(elements.addGroupForm), function () { });
		ConfigureAsyncForm(elements.removeGroupForm, changeGroupUrlCallback(elements.removeGroupForm), function () { });
		ConfigureAsyncForm(elements.invitationForm, defaultSubmitCallback(elements.invitationForm), inviteHandler);
		ConfigureAsyncForm(elements.deleteMultipleUserForm, defaultSubmitCallback(elements.deleteMultipleUserForm));
	};

	UserManagement.prototype.addUser = function (user) {
		users[user.id] = user;
	};

	var getSubmitCallback = function (action) {
		return function () {
			return options.submitUrl + "?uid=" + getActiveUserId() + "&action=" + action;
		};
	};

	var defaultSubmitCallback = function (form) {
		return function () {
			return options.submitUrl + "?action=" + form.attr('ajaxAction') + '&uid=' + getActiveUserId();
		};
	};

	var changeGroupUrlCallback = function (form) {
		return function () {
			return options.groupManagementUrl + "?action=" + form.attr('ajaxAction') + '&uid=' + getActiveUserId();
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
			elements.userList.find('[data-userId="' + user.id + '"]').find('.changeStatus').text(resultStatusText);
		}

		if (user.isActive) {
			PerformAsyncAction(statusButtonElement, getSubmitCallback(options.actions.deactivate), $('#userStatusIndicator'), changeStatusResultCallback);
		}
		else {
			PerformAsyncAction(statusButtonElement, getSubmitCallback(options.actions.activate), $('#userStatusIndicator'), changeStatusResultCallback);
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

	var changeGroup = function (action, groupId) {
		var url = opts.groupManagementUrl + '?action=' + action + '&gid=' + groupId;

		var data = { userId: getActiveUserId() };
		$.post(url, data);
	};

	var changePermissions = function () {
		var user = getActiveUser();
		var data = { dr: 'permissions', uid: user.id };
		$.get(opts.permissionsUrl, data, function (permissions) {
			elements.permissionsForm.find('.none').prop('selected', true);

			$.each(permissions.full, function (index, value) {
				elements.permissionsForm.find('#permission_' + value).val(value + '_0');
			});

			$.each(permissions.view, function (index, value) {
				elements.permissionsForm.find('#permission_' + value).val(value + '_1');
			});

			elements.permissionsDialog.modal('show');
		});
	};

	var changeColor = function () {
		var user = getActiveUser();
		var data = { dr: 'color', uid: user.id };
		$.get(opts.colorUrl, data, function (colorIds) {

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
