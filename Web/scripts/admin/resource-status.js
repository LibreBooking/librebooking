function ResourceStatusManagement(opts) {
  var options = opts;

  var elements = {
    addDialog: $('#addDialog'),
    editDialog: $('#editDialog'),
    deleteDialog: $('#deleteDialog'),

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
      showEditPrompt(e);
    });

    statusList.on('click', 'a.delete', function (e) {
      showDeletePrompt(e);
    });

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('.add-link').click(function (e) {
      e.preventDefault();
      $('#add-reason-status').val($(this).attr('add-to'));
      showAddPrompt(e);
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

  var showAddPrompt = function (e) {
    elements.addDialog.modal('show');
  };

  var showEditPrompt = function (e) {
    elements.editDialog.modal('show');
  };

  var showDeletePrompt = function (e) {
    elements.deleteDialog.modal('show');
  };
}
