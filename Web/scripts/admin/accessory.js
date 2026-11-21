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

    editDialog: document.getElementById('editDialog'),
    deleteDialog: document.getElementById('deleteDialog'),
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

    elements.accessoryResourcesDialog.on('click', '.resourceCheckbox', function () {
      handleAccessoryResourceClick(this);
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
    setDialogVisible(elements.editDialog, true);
  };

  function handleAccessoryResourceClick(checkbox) {
    const quantities = checkbox.closest('div[resource-id]').querySelector('.quantities');

    syncCollapse(quantities, () => checkbox.checked);
  }

  var showAccessoryResources = function () {
    var accessory = getActiveAccessory();

    $.get(opts.submitUrl + '?dr=accessoryResources&aid=' + accessory.id, function (data) {
      const dialog = elements.accessoryResourcesDialog[0];

      // Reset the initial state without triggering transitions.
      dialog.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.checked = false;
      });

      dialog.querySelectorAll('.collapse').forEach(function (element) {
        element.classList.remove('show');
      });

      $.each(data, function (idx, resource) {
        var div = elements.accessoryResourcesDialog.find('[resource-id="' + resource.ResourceId + '"]');

        var checkbox = div.find(':checkbox')[0];

        if (checkbox) {
          checkbox.checked = true;
        }

        const collapseElement = div.find('.quantities')[0];

        if (collapseElement) {
          collapseElement.classList.add('show');
        }

        div.find('[data-type="min-quantity"]').val(resource.MinQuantity);
        div.find('[data-type="max-quantity"]').val(resource.MaxQuantity);
      });

      elements.accessoryResourcesDialog
        .find('.resourcesDialogLabel')
        .val(accessory.name + ' (' + accessory.quantity + ')');
      setDialogVisible(elements.accessoryResourcesDialog, true);
    });
  };

  var deleteAccessory = function () {
    setDialogVisible(elements.deleteDialog, true);
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
