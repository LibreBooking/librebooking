function ResourceStatusManagement(opts) {
  var options = opts;

  var elements = {
    addDialog: document.getElementById('addDialog'),
    editDialog: document.getElementById('editDialog'),
    deleteDialog: document.getElementById('deleteDialog'),

    activeId: $('#activeId'),

    editForm: $('#editForm'),
    addForm: $('#addForm'),
    deleteForm: $('#deleteForm'),
    attributeForm: $('.attributesForm'),
  };

  ResourceStatusManagement.prototype.init = function () {
    var statusList = $('.resource-status-list');

    statusList.on('click', 'a.update', function (e) {
      var id = $(this).closest('.reason-item').attr('reasonId');
      setActiveId(id);

      e.preventDefault();
      e.stopPropagation();
    });

    statusList.on('click', 'a.edit', function (e) {
      $('#edit-reason-description').val($(this).closest('.reason-item').find('.reason-description').text());
      setDialogVisible(elements.editDialog, true);
    });

    statusList.on('click', 'a.delete', function (e) {
      setDialogVisible(elements.deleteDialog, true);
    });

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('.add-link').click(function (e) {
      e.preventDefault();
      $('#add-reason-status').val($(this).attr('add-to'));
      setDialogVisible(elements.addDialog, true);
    });

    var errorHandler = function (result) {
      $('#globalError').html(result).show();
    };

    ConfigureAsyncForm(elements.editForm, getSubmitCallback, null, errorHandler);
    ConfigureAsyncForm(elements.deleteForm, getSubmitCallback, null, errorHandler);
    ConfigureAsyncForm(elements.addForm, getSubmitCallback, null, errorHandler);
  };

  var getSubmitCallback = function (form) {
    return options.submitUrl + '?rsrid=' + getActiveId() + '&action=' + form.attr('ajaxAction');
  };

  var setActiveId = function (id) {
    elements.activeId.val(id);
  };

  var getActiveId = function () {
    return elements.activeId.val();
  };
}
