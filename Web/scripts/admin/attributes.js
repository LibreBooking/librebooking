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
        appliesToId: $('.appliesToId'),
        entityChoices: $('#entityChoices'),
        editEntityChoices: $('#editEntityChoices'),

        editName: $('#editName'),
        editUnlimited: $('#chkUnlimitedEdit'),
        editQuantity: $('#editQuantity'),

        addDialog: $('#addAttributeDialog'),
        editDialog: $('#editAttributeDialog'),
        deleteDialog: $('#deleteDialog'),

        addForm: $('#addAttributeForm'),
        form: $('#editAttributeForm'),
        deleteForm: $('#deleteForm'),

        limitScope: $('.limitScope'),
        attributeSecondary: $('.attributeSecondary'),
        secondaryPrompt: $('.secondaryPrompt'),
        secondaryAttributeCategory: $('.secondaryAttributeCategory')
    };

    function RefreshAttributeList() {
        var categoryId = elements.attributeCategory.val();

        $.ajax({
            url: opts.changeCategoryUrl + categoryId, cache: false, beforeSend: function () {
                $('#indicator').removeClass('d-none').insertBefore(elements.attributeList);
                $(elements.attributeList).html('');
            }
        }).done(function (data) {
            $('#indicator').addClass('d-none');
            $(elements.attributeList).html(data);
        });
    }

    var currentAttributeEntities = { entityIds: [], secondaryEntityIds: [] };
    var selectedEntityChoices = $('#entityChoices, #editEntityChoices');
    var activeAppliesTo;
    var updateEntityCallback = function () {
    };

    // Store template options for secondary categories
    var secondaryCategoryOptions = {};

    var updateSecondaryCategories = function() {
        var primaryCategory = elements.attributeCategory.val();

        // Update all secondary category selects
        $('.secondaryAttributeCategory').each(function() {
            var secondarySelect = $(this);

            // Clear and rebuild options based on primary category
            secondarySelect.empty();

            if (primaryCategory == options.categories.reservation) {
                // Add user, resource, and resource_type options
                secondarySelect.append(secondaryCategoryOptions.user);
                secondarySelect.append(secondaryCategoryOptions.resource);
                secondarySelect.append(secondaryCategoryOptions.resourceType);
            } else if (primaryCategory == options.categories.resource) {
                // Add resource and resource_type options
                secondarySelect.append(secondaryCategoryOptions.resource);
                secondarySelect.append(secondaryCategoryOptions.resourceType);
            }
        });
    };

    AttributeManagement.prototype.init = function () {
        // Initialize visibility rules
        initializeVisibilityRules();

        // Initialize secondary category options from template
        var templateSelect = $('#attributeSecondaryCategory');
        if (templateSelect.length > 0) {
            templateSelect.find('option').each(function() {
                var optionValue = $(this).val();
                var optionText = $(this).text();
                if (optionValue == options.categories.user) {
                    secondaryCategoryOptions.user = '<option value="' + optionValue + '">' + optionText + '</option>';
                } else if (optionValue == options.categories.resource) {
                    secondaryCategoryOptions.resource = '<option value="' + optionValue + '">' + optionText + '</option>';
                } else if (optionValue == options.categories.resource_type) {
                    secondaryCategoryOptions.resourceType = '<option value="' + optionValue + '">' + optionText + '</option>';
                }
            });
        }

        $(".save").click(function () {
            $(this).closest('form').submit();
        });

        $(".cancel").click(function () {
            $(this).closest('.dialog').dialog("close");
        });

        RefreshAttributeList();
        updateSecondaryCategories();

        elements.attributeCategory.change(function () {
            RefreshAttributeList();
            updateSecondaryCategories();
            // Reset form state when category changes
            elements.limitScope.prop('checked', false);
            elements.attributeSecondary.addClass('d-none');
            showRelevantCategoryOptions();
        });

        // Bind field visibility events
        elements.attributeCategory.on('change', updateFieldVisibility);
        $('.limitScope').on('change', updateScopeVisibility);

        // Initialize visibility on load
        updateFieldVisibility();
        updateScopeVisibility();

        $(".cancel").click(function () {
            $(this).closest('.dialog').dialog("close");
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
            elements.appliesToId.val('');
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

            showEntities($(this), elements.attributeCategory.val(), currentAttributeEntities.entityIds, 'ATTRIBUTE_ENTITY');

            updateEntityCallback = function (selectedIds) {
                currentAttributeEntities.entityIds = selectedIds;
            };
        });

        elements.secondaryPrompt.click(function (e) {
            e.preventDefault();
            activeAppliesTo = $(this);

            // Determine which dialog we're in and get the appropriate secondary category value
            var dialog = $(this).closest('.modal-content');
            var secondaryCategory = dialog.find('.secondaryAttributeCategory').val();

            showEntities($(this), secondaryCategory, currentAttributeEntities.secondaryEntityIds, 'ATTRIBUTE_SECONDARY_ENTITY_IDS');

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

        // Handle scope limiting checkbox - show/hide secondary options
        elements.limitScope.change(function () {
            if ($(this).is(':checked')) {
                elements.attributeSecondary.removeClass('d-none').show();
                updateSecondaryCategories();
            } else {
                elements.attributeSecondary.addClass('d-none').hide();
                // Reset secondary selections when unchecked
                currentAttributeEntities.secondaryEntityIds = [];
                elements.secondaryPrompt.text(opts.allText);
            }

            // Call template's scope visibility function if it exists
            if (typeof updateScopeVisibility === 'function') {
                updateScopeVisibility();
            }
        });

        elements.secondaryAttributeCategory.change(function (e) {
            currentAttributeEntities.entityIds = [];
            currentAttributeEntities.secondaryEntityIds = [];
            elements.secondaryPrompt.text(opts.allText);
        });

        ConfigureAsyncForm(elements.addForm, defaultSubmitCallback, addAttributeHandler);
        ConfigureAsyncForm(elements.form, defaultSubmitCallback, editAttributeHandler);
        ConfigureAsyncForm(elements.deleteForm, defaultSubmitCallback, deleteAttributeHandler);
    };

    function handleEntitiesSelected(element) {
        element.empty();
        var entities = selectedEntityChoices.find(':checked');
        // element.append(entities);
        if (entities.length > 0) {
            var text = _.map(entities, function (e) {
                return $(e).attr('data-text');
            }).join(', ');

            element.text(text);
            // elements.secondaryPrompt.text(text);

            updateEntityCallback(_.map(entities, function (e) {
                return $(e).val();
            }));
        }
        else {
            element.text(opts.allText);
        }
    }

    var onEntityChoiceClick = function (e) {
        e.preventDefault();
        selectedEntityChoices.hide();
        elements.appliesToId.empty();
        selectedEntityChoices.find('input:checkbox').removeAttr('checked');
        elements.appliesTo.text(opts.allText);
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

        // Reset visibility after add
        resetFormVisibility();
    };

    var editAttributeHandler = function () {
        elements.form.resetForm();
        elements.editDialog.modal('hide');
        RefreshAttributeList();

        // Reset visibility after edit
        resetFormVisibility();
    };

    var deleteAttributeHandler = function () {
        elements.deleteDialog.modal('hide');
        RefreshAttributeList();
    };

    // Visibility rules for different attribute categories
    var visibilityRules = {
        appliesTo: {},
        adminOnly: {},
        isPrivate: {},
        secondaryEntities: {}
    };

    // Initialize visibility rules from options
    var initializeVisibilityRules = function() {
        if (options.visibilityRules) {
            visibilityRules = options.visibilityRules;
        }
    };

    // Update field visibility based on selected category
    var updateFieldVisibility = function() {
        var selectedCategory = elements.attributeCategory.val();

        // Show/hide fields based on rules
        $('.attributeUnique').toggle(visibilityRules.appliesTo[selectedCategory] === true);
        $('.attributeAdminOnly').toggle(visibilityRules.adminOnly[selectedCategory] === true);
        $('.attributeIsPrivate').toggle(visibilityRules.isPrivate[selectedCategory] === true);
        $('.secondaryEntities').toggle(visibilityRules.secondaryEntities[selectedCategory] === true);

        if (!visibilityRules.secondaryEntities[selectedCategory]) {
            $('.limitScope').prop('checked', false);
            $('.attributeSecondary').hide();
        }
    };

    // Update scope visibility based on checkbox states
    var updateScopeVisibility = function() {
        $('.scope-conditional').each(function() {
            var dependsOn = $(this).data('depends-on');
            if (dependsOn) {
                var checkbox = $('#' + dependsOn);
                if (checkbox.is(':checked')) {
                    $(this).removeClass('d-none').show();
                } else {
                    $(this).addClass('d-none').hide();
                }
            }
        });
    };

    // Reset form visibility state after operations
    var resetFormVisibility = function() {
        updateFieldVisibility();
        updateScopeVisibility();

        // Reset secondary selections
        $('.limitScope').prop('checked', false);
        $('.attributeSecondary').addClass('d-none').hide();
        elements.secondaryPrompt.text(options.allText);
    };

    // Make functions available globally for template compatibility
    window.updateFieldVisibility = updateFieldVisibility;
    window.updateScopeVisibility = updateScopeVisibility;

    var showEditDialog = function (selectedAttribute) {
        showRelevantAttributeOptions(selectedAttribute.type, elements.editDialog);
        showRelevantCategoryOptions();
        updateSecondaryCategories(); // Ensure secondary categories are updated for the edit dialog

        $('.editAttributeType', elements.editDialog).hide();
        $('#editType' + selectedAttribute.type).show();

        $('#editAttributeLabel').val(HtmlDecode(selectedAttribute.label));
        $('#editAttributeRequired').prop('checked', selectedAttribute.required);
        $('#editAttributeUnique').prop('checked', selectedAttribute.unique);
        $('#editAttributeAdminOnly').prop('checked', selectedAttribute.adminOnly);

        $('#editAttributeRegex').val(selectedAttribute.regex);
        $('#editAttributePossibleValues').val(selectedAttribute.possibleValues);
        $('#editAttributeSortOrder').val(selectedAttribute.sortOrder);
        $('#editAttributeEntityId').val(selectedAttribute.entityId);

        selectedEntityChoices.empty();
        if (selectedAttribute.entityDescriptions.length == 0) {
            elements.appliesTo.text(options.allText);
            elements.appliesToId.val('');
        }
        else {
            if (elements.attributeCategory.val() == options.categories.reservation) {
                $.each(selectedAttribute.secondaryEntityIds, function (i, id) {
                    if (selectedAttribute.secondaryEntityDescriptions[i] != undefined) {
                        var name = selectedAttribute.secondaryEntityDescriptions[i].replace(/"/g, '&quot;')
                        selectedEntityChoices.append($('<input type="checkbox" name="ATTRIBUTE_SECONDARY_ENTITY_IDS[]" value="' + id + '" checked="checked" data-text="' + name + '"/>'));
                    }
                });
            }
            else {
                $.each(selectedAttribute.entityIds, function (i, id) {
                    if (selectedAttribute.secondaryEntityDescriptions[i] != undefined) {
                        var name = selectedAttribute.entityDescriptions[i].replace(/"/g, '&quot;')
                        selectedEntityChoices.append($('<input type="checkbox" name="ATTRIBUTE_ENTITY[]" value="' + id + '" checked="checked" data-text="' + name + '"/>'));
                    }
                });
            }
            handleEntitiesSelected(elements.editAppliesTo);
            elements.appliesToId.hide();
        }

        var limitScope = $('#editAttributeLimitScope');
        limitScope.prop('checked', false);
        $('.attributeSecondary').addClass('d-none').hide(); // Reset secondary visibility

        $('#editAttributeSecondaryEntityId').val('');
        elements.secondaryPrompt.text(options.allText);
        if (selectedAttribute.secondaryEntityIds.length > 0) {
            limitScope.prop('checked', true);
            limitScope.trigger('change');
            $('#editAttributeSecondaryCategory').val(selectedAttribute.secondaryCategory);
            $('#editAttributeSecondaryEntityId').val(selectedAttribute.secondaryEntityIds.join());
            elements.secondaryPrompt.text(selectedAttribute.secondaryEntityDescriptions.join(', '));
        }

        $('#editAttributePrivate').prop('checked', selectedAttribute.isPrivate);

        setActiveId(selectedAttribute.id);

        // Update field and scope visibility for the edit dialog
        updateFieldVisibility();
        updateScopeVisibility();

        elements.editDialog.modal('show');
    };

    var showDeleteDialog = function (selectedAttributeId) {
        setActiveId(selectedAttributeId);
        elements.deleteDialog.modal('show');
    };

    var defaultSubmitCallback = function (form) {
        return options.submitUrl + "?aid=" + getActiveId() + "&action=" + form.attr('ajaxAction');
    };

    function setActiveId(id) {
        elements.activeId.val(id);
    }

    function getActiveId() {
        return elements.activeId.val();
    }

    // Field visibility is now controlled by PHP/template - just handle secondary entities
    var showRelevantCategoryOptions = function () {
        // Use the local field visibility function
        updateFieldVisibility();
    };

    var showEntities = function (element, categoryId, selectedIds, formName) {
        //var selectedIds = [];
        elements.appliesToId.find('input:checkbox').removeAttr('checked');

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

        var items = ['<li><a href="#" class="all btn btn-sm btn-primary">' + options.allText + '</a> <a href="#" class="ok btn btn-sm btn-primary">OK</a></li>'];
        $.each(data, function (index, item) {
            var checked = '';
            if (selectedIds.indexOf(item.Id) !== -1) {
                checked = ' checked="checked" ';
            }
            items.push('<div class="form-check"><input type="checkbox" class="form-check-input" id="' + item.Id + '" name="' + formName + '[]" value="' + item.Id + '"' + checked + ' data-text="' + item.Name.replace(/"/g, '&quot;') + '"/><label class="form-check-label" for="' + item.Id + '">' + item.Name + '</label></div>');
        });

        selectedEntityChoices.empty();

        $('<div/>', { 'class': '', html: items.join('') }).appendTo(selectedEntityChoices);
    };

    var getResources = function () {
        var items = [];
        $.ajax({
            url: options.resourcesUrl, async: false
        }).done(function (data) {
            items = data;
        });

        return items;
    };

    var getUsers = function () {
        var items = [];
        $.ajax({
            url: options.usersUrl, async: false
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
            url: options.resourceTypesUrl, async: false
        }).done(function (data) {
            items = $.map(data, function (item, index) {
                return { Id: item.Id, Name: item.Name };
            });
        });

        return items;
    }
}
