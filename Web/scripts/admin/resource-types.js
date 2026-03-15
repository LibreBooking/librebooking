function ResourceTypeManagement(opts) {
  var options = opts;

  var elements = {
    activeId: $('#activeId'),

    resourceTypes: $('#resourceTypes'),

    editDialog: $('#editDialog'),
    deleteDialog: $('#deleteDialog'),

    editForm: $('#editForm'),
    addForm: $('#addForm'),
    deleteForm: $('#deleteForm'),
    attributeForm: $('.attributesForm'),
  };

  var types = {};

  ResourceTypeManagement.prototype.init = function () {
    $('.changeAttribute').click(function (e) {
      e.stopPropagation();
      $(e.target).closest('.updateCustomAttribute').find('.inlineAttribute').editable('toggle');
    });

    elements.resourceTypes.on('click', 'a.update', function (e) {
      var id = $(this).siblings(':hidden.id').val();
      setActiveId(id);

      e.preventDefault();
      e.stopPropagation();
    });

    elements.resourceTypes.on('click', 'a.edit', function (e) {
      showEdit(e);
    });

    elements.resourceTypes.on('click', 'a.delete', function (e) {
      showDeletePrompt(e);
    });

    elements.resourceTypes.on('click', '.changeAttributes', function (e) {
      var id = $(this).attr('resourceTypeId');
      setActiveId(id);
    });

    elements.resourceTypes.on('click', '.changeAttributes, .customAttributes .cancel', function (e) {
      var id = getActiveId();
      var otherUsers = $(".customAttributes[resourceTypeId!='" + id + "']");
      otherUsers.find('.attribute-readwrite, .validationSummary').hide();
      otherUsers.find('.attribute-readonly').show();
      var container = $(this).closest('.customAttributes');
      container.find('.attribute-readwrite').toggle();
      container.find('.attribute-readonly').toggle();
      container.find('.validationSummary').hide();
    });

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('.cancel').click(function () {
      $(this).closest('.dialog').dialog('close');
    });

    var attributesHandler = function (responseText, form) {
      if (responseText.ErrorIds && responseText.Messages.attributeValidator) {
        var messages = responseText.Messages.attributeValidator.join('</li><li>');
        messages = '<li>' + messages + '</li>';
        var validationSummary = $(form).find('.validationSummary');
        validationSummary.find('ul').empty().append(messages);
        validationSummary.show();
      }
    };

    var errorHandler = function (result) {
      $('#globalError').html(result).show();
    };

    ConfigureAsyncForm(elements.editForm, getSubmitCallback, null, errorHandler);
    ConfigureAsyncForm(elements.deleteForm, getSubmitCallback, null, errorHandler);
    ConfigureAsyncForm(elements.addForm, getSubmitCallback, null, errorHandler);

    $.each(elements.attributeForm, function (i, form) {
      ConfigureAsyncForm($(form), getSubmitCallback, null, attributesHandler, { validationSummary: null });
    });
  };

  ResourceTypeManagement.prototype.add = function (resourceType) {
    types[resourceType.id] = resourceType;
  };

  var getSubmitCallback = function (form) {
    return options.submitUrl + '?rtid=' + getActiveId() + '&action=' + form.attr('ajaxAction');
  };

  var setActiveId = function (id) {
    elements.activeId.val(id);
  };

  var getActiveId = function () {
    return elements.activeId.val();
  };

  var showEdit = function (e) {
    var type = types[getActiveId()];

    $('#editName').val(type.name);
    $('#editDescription').val(type.description);

    elements.editDialog.modal('show');
  };

  var showDeletePrompt = function (e) {
    elements.deleteDialog.modal('show');
  };
}
