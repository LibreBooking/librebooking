function AttributeManagement(opts) {
  var options = opts;

  var elements = {
    activeId: $('#activeId'),
    attributeList: $('#attributeList'),

    attributeCategory: $('#attributeCategory'),
    addCategory: $('#addCategory'),
    attributeType: $('#attributeType'),
    appliesTo: $('#appliesTo'),
    editAppliesTo: $('#editAppliesTo'),
    entityChoices: $('#entityChoices'),
    editEntityChoices: $('#editEntityChoices'),

    editName: $('#editName'),
    editUnlimited: $('#chkUnlimitedEdit'),
    editQuantity: $('#editQuantity'),

    addDialog: $('#addAttributeDialog'),
    editDialog: $('#editAttributeDialog'),
    deleteDialog: $('#deleteDialog'),
    deleteAttributeLabel: $('#deleteAttributeLabel'),

    addForm: $('#addAttributeForm'),
    form: $('#editAttributeForm'),
    deleteForm: $('#deleteForm'),

    limitScope: $('.limitScope'),
    attributeSecondary: $('.attributeSecondary'),
    secondaryPrompt: $('.secondaryPrompt'),
    secondaryAttributeCategory: $('.secondaryAttributeCategory '),
  };

  function RefreshAttributeList() {
    var categoryId = elements.attributeCategory.val();

    $.ajax({
      url: opts.changeCategoryUrl + categoryId,
      cache: false,
      beforeSend: function () {
        $('#indicator').removeClass('d-none').insertBefore(elements.attributeList);
        $(elements.attributeList).html('');
      },
    }).done(function (data) {
      $('#indicator').addClass('d-none');
      $(elements.attributeList).html(data);
    });
  }

  var currentAttributeEntities = { entityIds: [], secondaryEntityIds: [] };
  var selectedEntityChoices = $('#entityChoices, #editEntityChoices');
  var activeAppliesTo;
  var updateEntityCallback = function () {};

  AttributeManagement.prototype.init = function () {
    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    RefreshAttributeList();

    elements.attributeCategory.change(function () {
      RefreshAttributeList();
    });

    elements.attributeList.on('click', 'a.update', function (e) {
      e.preventDefault();
      e.stopPropagation();
    });

    elements.attributeList.on('click', '.delete', function (e) {
      e.preventDefault();
      var attributeId = $(this).closest('tr').attr('attributeId');

      showDeleteDialog(attributeId);
    });

    $('#addAttributeButton').click(function (e) {
      e.preventDefault();
      selectedEntityChoices = elements.entityChoices;
      currentAttributeEntities.entityIds = [];
      currentAttributeEntities.secondaryEntityIds = [];
      $('span.error', elements.addDialog).remove();
      elements.addDialog.find(':text, :input[type="number"]').val('');
      elements.addCategory.val(elements.attributeCategory.val());
      elements.attributeType.trigger('change');
      elements.limitScope.prop('checked', false);
      showRelevantCategoryOptions();
      elements.appliesTo.text(options.allText);
      elements.secondaryPrompt.text(options.allText);
      renderEntityInputs(elements.addForm);
      elements.addDialog.modal('show');
    });

    elements.attributeType.on('change', function () {
      showRelevantAttributeOptions($(this).val(), elements.addDialog);
    });

    elements.attributeList.on('click', '.edit', function (e) {
      e.preventDefault();
      e.stopPropagation();
      selectedEntityChoices = elements.editEntityChoices;
      var attributeId = $(this).closest('tr').attr('attributeId');
      var dataList = elements.attributeList.data('list');
      var selectedAttribute = dataList[attributeId];

      currentAttributeEntities.entityIds = selectedAttribute.entityIds;
      currentAttributeEntities.secondaryEntityIds = selectedAttribute.secondaryEntityIds;

      showEditDialog(selectedAttribute);
    });

    $('#appliesTo, #editAppliesTo').click(function (e) {
      e.preventDefault();
      activeAppliesTo = $(this);

      showEntities($(this), elements.attributeCategory.val(), currentAttributeEntities.entityIds);

      updateEntityCallback = function (selectedIds) {
        currentAttributeEntities.entityIds = selectedIds;
      };
    });

    elements.secondaryPrompt.click(function (e) {
      e.preventDefault();
      activeAppliesTo = $(this);

      showEntities(
        $(this),
        $(this).closest('.textBoxOptions').find('.secondaryAttributeCategory').val(),
        currentAttributeEntities.secondaryEntityIds
      );

      updateEntityCallback = function (selectedIds) {
        currentAttributeEntities.secondaryEntityIds = selectedIds;
      };
    });

    $(document).mouseup(function (e) {
      var container = selectedEntityChoices;

      if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
      }
    });

    selectedEntityChoices.on('click', 'a.all', function (e) {
      onEntityChoiceClick(e);
    });

    selectedEntityChoices.on('click', 'a.ok', function (e) {
      e.preventDefault();
      selectedEntityChoices.hide();
      handleEntitiesSelected(activeAppliesTo);
    });

    elements.limitScope.change(function () {
      elements.attributeSecondary.toggleClass('d-none', !$(this).is(':checked'));
    });

    elements.secondaryAttributeCategory.change(function () {
      currentAttributeEntities.secondaryEntityIds = [];
      elements.secondaryPrompt.text(opts.allText);
      renderEntityInputs($(this).closest('form'));
    });

    ConfigureAsyncForm(elements.addForm, defaultSubmitCallback, addAttributeHandler);
    ConfigureAsyncForm(elements.form, defaultSubmitCallback, editAttributeHandler);
    ConfigureAsyncForm(elements.deleteForm, defaultSubmitCallback, deleteAttributeHandler);
  };

  function handleEntitiesSelected(element) {
    var entities = selectedEntityChoices.find(':checked');
    var text = _.map(entities, function (e) {
      return $(e).attr('data-text');
    }).join(', ');

    element.text(text || opts.allText);
    updateEntityCallback(
      _.map(entities, function (e) {
        return $(e).val();
      })
    );
    renderEntityInputs(selectedEntityChoices.closest('form'));
  }

  // The picker checkboxes are display only. Both pickers share one container, so the
  // selected ids are submitted from hidden inputs built from currentAttributeEntities.
  function renderEntityInputs(form) {
    form.find('.selectedEntityIds').remove();

    var inputs = $('<div class="selectedEntityIds d-none"></div>');
    $.each(currentAttributeEntities.entityIds, function (i, id) {
      inputs.append($('<input type="hidden" name="ATTRIBUTE_ENTITY[]"/>').val(id));
    });
    $.each(currentAttributeEntities.secondaryEntityIds, function (i, id) {
      inputs.append($('<input type="hidden" name="ATTRIBUTE_SECONDARY_ENTITY_IDS[]"/>').val(id));
    });
    form.append(inputs);
  }

  var onEntityChoiceClick = function (e) {
    e.preventDefault();
    selectedEntityChoices.hide();
    selectedEntityChoices.find('input:checkbox').prop('checked', false);
    handleEntitiesSelected(activeAppliesTo);
  };

  var showRelevantAttributeOptions = function (selectedType, optionsDiv) {
    $('.textBoxOptions', optionsDiv).find('div').not('.attributeUnique, .attributeSecondary').show();

    if (selectedType != opts.selectList) {
      $('.attributePossibleValues').hide();
    }

    if (selectedType == opts.selectList || selectedType == opts.date) {
      $('.attributeValidationExpression').hide();
    }

    if (selectedType == opts.checkbox) {
      $('.attributePossibleValues, .attributeValidationExpression').hide();
    }

    showRelevantCategoryOptions();
  };

  var addAttributeHandler = function () {
    elements.addForm.resetForm();
    elements.addDialog.modal('hide');
    RefreshAttributeList();
  };

  var editAttributeHandler = function () {
    elements.form.resetForm();
    elements.editDialog.modal('hide');
    RefreshAttributeList();
  };

  var deleteAttributeHandler = function () {
    elements.deleteDialog.modal('hide');
    RefreshAttributeList();
  };

  var showEditDialog = function (selectedAttribute) {
    showRelevantAttributeOptions(selectedAttribute.type, elements.editDialog);
    showRelevantCategoryOptions();

    $('.editAttributeType', elements.editDialog).hide();
    $('#editType' + selectedAttribute.type).show();

    $('#editAttributeLabel').val(HtmlDecode(selectedAttribute.label));
    $('#editAttributeRequired').prop('checked', selectedAttribute.required);
    $('#editAttributeUnique').prop('checked', selectedAttribute.unique);
    $('#editAttributeAdminOnly').prop('checked', selectedAttribute.adminOnly);

    $('#editAttributeRegex').val(selectedAttribute.regex);
    $('#editAttributePossibleValues').val(selectedAttribute.possibleValues);
    $('#editAttributeSortOrder').val(selectedAttribute.sortOrder);

    selectedEntityChoices.empty();
    var appliesToText = selectedAttribute.entityDescriptions.filter(Boolean).join(', ');
    elements.editAppliesTo.text(appliesToText || options.allText);

    var limitScope = $('#editAttributeLimitScope');
    limitScope.prop('checked', false).trigger('change');

    elements.secondaryPrompt.text(options.allText);
    if (selectedAttribute.secondaryEntityIds.length > 0) {
      limitScope.prop('checked', true);
      limitScope.trigger('change');
      $('#editAttributeSecondaryCategory').val(selectedAttribute.secondaryCategory);
      elements.secondaryPrompt.text(selectedAttribute.secondaryEntityDescriptions.join(', '));
    }

    $('#editAttributePrivate').prop('checked', selectedAttribute.isPrivate);

    setActiveId(selectedAttribute.id);
    renderEntityInputs(elements.form);

    elements.editDialog.modal('show');
  };

  var showDeleteDialog = function (selectedAttributeId) {
    var dataList = elements.attributeList.data('list');
    var selectedAttribute = dataList[selectedAttributeId];

    elements.deleteAttributeLabel.text(HtmlDecode(selectedAttribute?.label ?? ''));

    setActiveId(selectedAttributeId);
    elements.deleteDialog.modal('show');
  };

  var defaultSubmitCallback = function (form) {
    return options.submitUrl + '?aid=' + getActiveId() + '&action=' + form.attr('ajaxAction');
  };

  function setActiveId(id) {
    elements.activeId.val(id);
  }

  function getActiveId() {
    return elements.activeId.val();
  }

  var showRelevantCategoryOptions = function () {
    var category = elements.attributeCategory.val();
    var isReservation = category == options.categories.reservation;
    var isResource = category == options.categories.resource;

    $('.attributeUnique').toggle(!isReservation);
    $('.attributeIsPrivate').toggle(isReservation);
    $('.secondaryEntities, .attributeSecondary').addClass('d-none');
    $('.secondaryEntities').toggleClass('d-none', !isReservation && !isResource);

    // Resource attributes can only be limited to resource types, specific resources use "Applies To"
    $('.secondaryAttributeCategory option').each(function () {
      var hidden = isResource && $(this).val() != options.categories.resource_type;
      $(this).prop({ hidden: hidden, disabled: hidden });
    });
    if (isResource) {
      elements.secondaryAttributeCategory.val(options.categories.resource_type);
    }
  };

  var showEntities = function (element, categoryId, selectedIds) {
    selectedEntityChoices.empty();
    selectedEntityChoices.css({ left: element.position().left, top: element.position().top + element.height() });
    selectedEntityChoices.show();

    $('<div class="ajax-indicator">&nbsp;</div>').appendTo(selectedEntityChoices).show();

    var data = [];

    if (categoryId == options.categories.resource) {
      data = getResources();
    }

    if (categoryId == options.categories.user) {
      data = getUsers();
    }

    if (categoryId == options.categories.resource_type) {
      data = getResourceTypes();
    }

    var items = [
      '<li><a href="#" class="all btn btn-sm btn-primary">' +
        options.allText +
        '</a> <a href="#" class="ok btn btn-sm btn-primary">OK</a></li>',
    ];
    $.each(data, function (index, item) {
      var checked = '';
      if (selectedIds.map(String).indexOf(String(item.Id)) !== -1) {
        checked = ' checked="checked" ';
      }
      items.push(
        '<div class="form-check"><input type="checkbox" class="form-check-input" id="' +
          item.Id +
          '" value="' +
          item.Id +
          '"' +
          checked +
          ' data-text="' +
          item.Name.replace(/"/g, '&quot;') +
          '"/><label class="form-check-label" for="' +
          item.Id +
          '">' +
          item.Name +
          '</label></div>'
      );
    });

    selectedEntityChoices.empty();

    $('<div/>', { class: '', html: items.join('') }).appendTo(selectedEntityChoices);
  };

  var getResources = function () {
    var items = [];
    $.ajax({
      url: options.resourcesUrl,
      async: false,
    }).done(function (data) {
      items = data;
    });

    return items;
  };

  var getUsers = function () {
    var items = [];
    $.ajax({
      url: options.usersUrl,
      async: false,
    }).done(function (data) {
      items = $.map(data, function (item, index) {
        return { Id: item.UserId, Name: item.FullName };
      });
    });

    return items;
  };

  var getResourceTypes = function () {
    var items = [];
    $.ajax({
      url: options.resourceTypesUrl,
      async: false,
    }).done(function (data) {
      items = $.map(data, function (item, index) {
        return { Id: item.Id, Name: item.Name };
      });
    });

    return items;
  };
}
