function AccessoryManagement(opts) {
  var options = opts;

  var elements = {
    activeId: $('#activeId'),
    accessoryList: $('#accessoriesTable'),

    addUnlimited: $('#chkUnlimitedAdd'),
    addQuantity: $('#addQuantity'),

    editName: $('#editName'),
    editUnlimited: $('#chkUnlimitedEdit'),
    editQuantity: $('#editQuantity'),

    editDialog: $('#editDialog'),
    deleteDialog: $('#deleteDialog'),
    accessoryResourcesDialog: $('#accessoryResourcesDialog'),

    addForm: $('#addForm'),
    form: $('#editForm'),
    deleteForm: $('#deleteForm'),
    accessoryResourcesForm: $('#accessoryResourcesForm'),
  };

  var accessories = new Object();

  AccessoryManagement.prototype.init = function () {
    elements.accessoryList.on('click', 'a.update', function (e) {
      setActiveId($(this));
      e.preventDefault();
    });

    elements.accessoryList.on('click', '.edit', function () {
      editAccessory();
    });

    elements.accessoryList.on('click', '.delete', function () {
      deleteAccessory();
    });

    elements.accessoryList.on('click', '.resources', function () {
      showAccessoryResources();
    });

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('.cancel').click(function () {
      $(this).closest('.dialog').dialog('close');
    });

    elements.accessoryResourcesDialog.on('click', '.resourceCheckbox', function () {
      handleAccessoryResourceClick($(this));
    });

    ConfigureAsyncForm(elements.addForm, getSubmitCallback(options.actions.add));
    ConfigureAsyncForm(elements.deleteForm, getSubmitCallback(options.actions.deleteAccessory));
    ConfigureAsyncForm(elements.form, getSubmitCallback(options.actions.edit));
    ConfigureAsyncForm(elements.accessoryResourcesForm, defaultSubmitCallback);

    WireUpUnlimited(elements.addUnlimited, elements.addQuantity);
    WireUpUnlimited(elements.editUnlimited, elements.editQuantity);
  };

  var getSubmitCallback = function (action) {
    return function () {
      return options.submitUrl + '?aid=' + getActiveId() + '&action=' + action;
    };
  };

  var defaultSubmitCallback = function (form) {
    return options.submitUrl + '?aid=' + getActiveId() + '&action=' + form.attr('ajaxAction');
  };

  function setActiveId(activeElement) {
    var id = activeElement.closest('tr').attr('data-accessory-id');
    elements.activeId.val(id);
  }

  function getActiveId() {
    return elements.activeId.val();
  }

  var editAccessory = function () {
    var accessory = getActiveAccessory();
    elements.editName.val(accessory.name);
    elements.editQuantity.val(accessory.quantity);

    if (accessory.quantity == '') {
      elements.editUnlimited.prop('checked', true);
    } else {
      elements.editUnlimited.prop('checked', false);
    }

    elements.editUnlimited.trigger('change');
    elements.editDialog.modal('show');
  };

  function handleAccessoryResourceClick(checkbox) {
    var quantities = checkbox.closest('div[resource-id]').find('.quantities');
		var accMin = checkbox.closest('div[resource-id]').find('#acc_min');
		var accMax = checkbox.closest('div[resource-id]').find('#acc_max');

		accMin.attr('disabled', false);
		accMax.attr('disabled', false);

    if (checkbox.is(':checked')) {
      quantities.removeClass('show');
    } else {
      quantities.addClass('show');
    }
  }

  var showAccessoryResources = function () {
    var accessory = getActiveAccessory();

    $.get(opts.submitUrl + '?dr=accessoryResources&aid=' + accessory.id, function (data) {
      elements.accessoryResourcesDialog.find(':checkbox').prop('checked', false);
      elements.accessoryResourcesDialog.find('.collapse').removeClass('show');

      $.each(data, function (idx, resource) {
        var div = elements.accessoryResourcesDialog.find('[resource-id="' + resource.ResourceId + '"]');
        var checkbox = div.find(':checkbox');
        checkbox.prop('checked', true);

        var collapseTarget = div.find('#quantitiesaccessoryResource' + resource.ResourceId);
        if (checkbox.is(':checked')) {
          collapseTarget.collapse('show');
        } else {
          collapseTarget.collapse('hide');
        }

        handleAccessoryResourceClick(checkbox);

        var minQ = div.find('[data-type="min-quantity"]');
        minQ.val(resource.MinQuantity);
        minQ.removeAttr('disabled');
        var maxQ = div.find('[data-type="max-quantity"]');
        maxQ.val(resource.MaxQuantity);
        maxQ.removeAttr('disabled');
      });

      elements.accessoryResourcesDialog
        .find('.resourcesDialogLabel')
        .val(accessory.name + ' (' + accessory.quantity + ')');
      elements.accessoryResourcesDialog.modal('show');
    });
  };

  var deleteAccessory = function () {
    elements.deleteDialog.modal('show');
  };

  var getActiveAccessory = function () {
    return accessories[getActiveId()];
  };

  var WireUpUnlimited = function (checkbox, quantity) {
    checkbox.change(function () {
      if (checkbox.is(':checked')) {
        quantity.val('');
        quantity.attr('disabled', 'disabled');
      } else {
        quantity.removeAttr('disabled');
      }
    });
  };

  AccessoryManagement.prototype.addAccessory = function (id, name, quantity) {
    accessories[id] = { id: id, name: name, quantity: quantity };
  };
}
