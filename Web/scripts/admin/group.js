function GroupManagement(opts) {
	var options = opts;

	var elements = {
		activeId: $('#activeId'),
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
		importGroupsTrigger: $('#import-groups')
	};

	var allUserList = null;

	GroupManagement.prototype.init = function () {

		elements.groupList.delegate('a.update', 'click', function (e) {
			setActiveId($(this));
			e.preventDefault();
		});

		elements.groupList.delegate('.rename', 'click', function () {
			editGroup();
		});

		elements.groupList.delegate('.permissions', 'click', function () {
			changePermissions();
		});

		elements.groupList.delegate('.members', 'click', function () {
			changeMembers();
			elements.membersDialog.modal('show');
		});

		elements.groupList.delegate('.groupAdmin', 'click', function () {
			changeGroupAdmin();
		});

		elements.groupList.delegate('.changeAdminGroups', 'click', function () {
			changeAdminGroups();
		});
		elements.groupList.delegate('.changeAdminResources', 'click', function () {
			changeAdminResources();
		});
		elements.groupList.delegate('.changeAdminSchedules', 'click', function () {
			changeAdminSchedules();
		});

		elements.groupList.delegate('.delete', 'click', function () {
			deleteGroup();
		});

		elements.groupList.delegate('.roles', 'click', function () {
			changeRoles();
		});

		elements.browseUserDialog.delegate('.add', 'click', function () {
			var link = $(this);
			var userId = link.siblings('.id').val();

			addUserToGroup(userId);

			link.find('i').removeClass('bi-plus-square-fill text-success').addClass('bi-check-circle-fill text-info');
		});

		elements.groupUserList.delegate('.delete', 'click', function () {
			var userId = $(this).siblings('.id').val();
			removeUserFromGroup($(this), userId);
		});

		elements.autocompleteSearch.autocomplete({
			source: function (request, response) {
				$.ajax({
					url: options.groupAutocompleteUrl,
					dataType: "json",
					data: {
						term: request.term
					},
					success: function (data) {
						response($.map(data, function (item) {
							return {
								label: item.Name,
								value: item.Id
							};
						}));
					}
				});
			},
			focus: function (event, ui) {
				elements.autocompleteSearch.val(ui.item.label);
				return false;
			},
			select: function (event, ui) {
				elements.autocompleteSearch.val(ui.item.label);
				window.location.href = options.selectGroupUrl + ui.item.value
				return false;
			}
		});

		elements.userSearch.userAutoComplete(options.userAutocompleteUrl, function (ui) {
			addUserToGroup(ui.item.value);
			elements.userSearch.val('');
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
			$(this).closest('.dialog').modal("hide");
		});

		var hidePermissionsDialog = function () {
			elements.permissionsDialog.modal('hide');
		};

		var error = function (errorText) {
			alert(errorText);
		};

		$("#browseUsers").click(function () {
			showAllUsersToAdd();
		});

		$('.adminDialog').on('click', '.checkbox', function (e) {
			var $checkbox = $(e.target);
			var modal = $checkbox.closest('.modal-body');
			modal.find('.count').text(modal.find(':checked').length);
		});

		$('#add-group').click(e => {
			e.preventDefault();
			elements.addDialog.modal('show');
			elements.addDialog.find(':text').first().focus();
		});

		elements.importGroupsTrigger.click(e => {
			e.preventDefault();
			elements.importGroupsDialog.modal('show');
		});

		const importHandler = function (responseText, form) {
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

		ConfigureAsyncForm(elements.addUserForm, getSubmitCallback(options.actions.addUser), changeMembers, error);
		ConfigureAsyncForm(elements.removeUserForm, getSubmitCallback(options.actions.removeUser), changeMembers, error);
		ConfigureAsyncForm(elements.permissionsForm, getSubmitCallback(options.actions.permissions), hidePermissionsDialog, error);
		ConfigureAsyncForm(elements.editGroupForm, getSubmitCallback(options.actions.updateGroup), null, error);
		ConfigureAsyncForm(elements.deleteGroupForm, getSubmitCallback(options.actions.deleteGroup), null, error);
		ConfigureAsyncForm(elements.addForm, getSubmitCallback(options.actions.addGroup), null, error);
		ConfigureAsyncForm(elements.rolesForm, getSubmitCallback(options.actions.roles), null, error);
		ConfigureAsyncForm(elements.groupAdminForm, getSubmitCallback(options.actions.groupAdmin), null, error);
		ConfigureAsyncForm(elements.changeAdminGroupsForm, getSubmitCallback(options.actions.adminGroups), function () { elements.groupAdminAllDialog.modal('hide'); }, error);
		ConfigureAsyncForm(elements.changeAdminResourcesForm, getSubmitCallback(options.actions.resourceGroups), function () { elements.resourceAdminDialog.modal('hide'); }, error);
		ConfigureAsyncForm(elements.changeAdminSchedulesForm, getSubmitCallback(options.actions.scheduleGroups), function () { elements.scheduleAdminDialog.modal('hide'); }, error);
		ConfigureAsyncForm(elements.importGroupsForm, getSubmitCallback(options.actions.importGroups), importHandler);

	};

	var showAllUsersToAdd = function () {
		elements.membersDialog.modal('hide');
		elements.allUsersList.empty();

		if (allUserList == null) {
			$.ajax({
				url: options.userAutocompleteUrl,
				dataType: 'json',
				async: false,
				success: function (data) {
					allUserList = data;
				}
			});
		}

		var items = [];
		if (allUserList != null) {
			$.map(allUserList, function (item) {
				if (elements.groupUserList.data('userIds')[item.Id] == undefined) {
					items.push('<div><a href="#" class="add"><i class="bi bi-plus-square-fill text-success" alt="Add To Group"></i></a> ' + item.DisplayName + '<input type="hidden" class="id" value="' + item.Id + '"/></div>');
				}
				else {
					items.push('<div><i class="bi bi-check-circle-fill me-1 text-info" alt="Group Member"></i><span>' + item.DisplayName + '</span></div>');
				}
			});
		}

		$('<div/>', { 'class': '', html: items.join('') }).appendTo(elements.allUsersList);
		elements.browseUserDialog.modal('show');
	};

	var getSubmitCallback = function (action) {
		return function () {
			return options.submitUrl + "?gid=" + getActiveId() + "&action=" + action;
		};
	};

	function setActiveId(activeElement) {
		var id = activeElement.closest('tr').attr('data-group-id');
		elements.activeId.val(id);
	}

	function getActiveId() {
		return elements.activeId.val();
	}

	var editGroup = function () {
		var activeRow = elements.groupList.find('[data-group-id="' + getActiveId() + '"]');
		elements.editGroupName.val(activeRow.find('.dataGroupName').text());
		elements.editGroupIsDefault.prop('checked', activeRow.data('group-default') == '1');
		elements.editDialog.modal('show');
	};

	var changeMembers = function () {
		var groupId = getActiveId();
		$.getJSON(opts.groupsUrl + '?dr=groupMembers', { gid: groupId }, function (data) {
			var items = [];
			var userIds = [];

			$('#totalUsers').text(data.Total);
			if (data.Users != null) {
				$.map(data.Users, function (item) {
					items.push('<div><a href="#" class="delete"><i class="bi bi-x-square-fill text-danger"></i></a> ' + item.DisplayName + '<input type="hidden" class="id" value="' + item.Id + '"/></div>');
					userIds[item.Id] = item.Id;
				});
			}

			elements.groupUserList.empty();
			elements.groupUserList.data('userIds', userIds);

			$('<div/>', { 'class': '', html: items.join('') }).appendTo(elements.groupUserList);
		});
	};

	var addUserToGroup = function (userId) {
		$('#addUserId').val(userId);
		elements.addUserForm.submit();
	};

	var removeUserFromGroup = function (element, userId) {
		$('#removeUserId').val(userId);
		elements.removeUserForm.submit();
	};

	var changePermissions = function () {
		var groupId = getActiveId();

		var data = { dr: opts.dataRequests.permissions, gid: groupId };
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

	var deleteGroup = function () {
		elements.deleteDialog.modal('show');
	};

	var changeRoles = function () {
		var groupId = getActiveId();

		var data = { dr: opts.dataRequests.roles, gid: groupId };
		$.get(opts.rolesUrl, data, function (roleIds) {
			elements.rolesForm.find(':checkbox').prop('checked', false);
			$.each(roleIds, function (index, value) {
				elements.rolesForm.find(':checkbox[value="' + value + '"]').prop('checked', true);
			});

			elements.rolesDialog.modal('show');
		});
	};

	var changeGroupAdmin = function () {
		var groupId = getActiveId();
		
		elements.groupAdminForm.find('select').val('');
		elements.groupAdminDialog.modal('show');
	};

	var changeAdminGroups = function () {
		populateAdminCheckboxes(opts.dataRequests.adminGroups, elements.changeAdminGroupsForm, elements.groupAdminAllDialog);
	};

	var changeAdminResources = function () {
		populateAdminCheckboxes(opts.dataRequests.resourceGroups, elements.changeAdminResourcesForm, elements.resourceAdminDialog);
	};

	var changeAdminSchedules = function () {
		populateAdminCheckboxes(opts.dataRequests.scheduleGroups, elements.changeAdminSchedulesForm, elements.scheduleAdminDialog);
	};

	var populateAdminCheckboxes = function (dr, $form, $dialog) {
		var groupId = getActiveId();

		$dialog.find('.count').text($dialog.find(':checked').length);

		var data = { dr: dr, gid: groupId };
		$.get(opts.submitUrl, data, function (groupIds) {
			$form.find(':checkbox').prop('checked', false);
			$.each(groupIds, function (index, value) {
				$form.find(':checkbox[value="' + value + '"]').prop('checked', true);
			});

			$dialog.find('.count').text(groupIds.length);
			$dialog.modal('show');
		});
	};
}
