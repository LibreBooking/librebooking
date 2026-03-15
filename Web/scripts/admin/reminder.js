function ReminderManagement(opts) {
  var options = opts;

  var elements = {
    activeId: $('#activeId'),
    reminderList: $('table.list'),

    addAddress: $('#addAddress'),
    addMessage: $('#addMessage'),
    addSendtime: $('#addSendtime'),

    editAddress: $('#editAddress'),
    editMessage: $('#editMessage'),
    editSendtime: $('#editSendtime'),

    editDialog: $('#editDialog'),
    deleteDialog: $('#deleteDialog'),

    addForm: $('#addForm'),
    profileForm: $('#editForm'),
    deleteForm: $('#deleteForm'),
  };

  var reminders = new Object();

  ReminderManagement.prototype.init = function () {
    ConfigureAdminDialog(elements.editDialog, 450, 200);
    ConfigureAdminDialog(elements.deleteDialog, 500, 200);

    elements.reminderList.on('click', 'a.update', function (e) {
      setActiveId($(this));
      e.preventDefault();
    });

    elements.reminderList.on('click', '.edit', function () {
      editReminder();
    });
    elements.reminderList.on('click', '.delete', function () {
      deleteReminder();
    });

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('.cancel').click(function () {
      $(this).closest('.dialog').dialog('close');
    });

    ConfigureAsyncForm(elements.addForm, getSubmitCallback(options.actions.add));
    ConfigureAsyncForm(elements.deleteForm, getSubmitCallback(options.actions.deleteReminder));
    ConfigureAsyncForm(elements.profileForm, getSubmitCallback(options.actions.edit));
  };

  var getSubmitCallback = function (action) {
    return function () {
      return options.submitUrl + '?aid=' + getActiveId() + '&action=' + action;
    };
  };

  function setActiveId(activeElement) {
    var id = activeElement.parents('td').siblings('td.id').find(':hidden').val();
    elements.activeId.val(id);
  }

  function getActiveId() {
    return elements.activeId.val();
  }

  var editReminder = function () {
    var reminder = getActiveReminder();
    //elements.editAddress.val(reminder.address);
    //elements.editMessage.val(reminder.message);
    //elements.editSendtime.val(reminder.sendtime);
    elements.editDialog.dialog('open');
  };

  var deleteReminder = function () {
    elements.deleteDialog.dialog('open');
  };

  var getActiveReminder = function () {
    return reminders[getActiveId()];
  };

  ReminderManagement.prototype.addReminder = function (id, userid, address, message, sendtime, refnumber) {
    reminders[id] = {
      reminderid: id,
      userid: userid,
      address: address,
      message: message,
      sendtime: sendtime,
      refnumber: refnumber,
    };
  };
}
