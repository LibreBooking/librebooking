function ResourceGroupManagement(opts) {
  var options = opts;

  var elements = {
    groupDiv: $('#group-tree'),
    deleteResource: $('.remove-resource'),

    addGroupButton: $('#btnAddGroup'),
    addGroupForm: $('#addGroupForm'),

    activeId: $('#activeId'),

    renameForm: $('#renameForm'),
    renameDialog: $('#renameDialog'),
    newName: $('#editName'),

    deleteForm: $('#deleteForm'),
    deleteDialog: $('#deleteDialog'),

    addChildForm: $('#addChildForm'),
    addChildDialog: $('#addChildDialog'),
  };

  ResourceGroupManagement.prototype.init = function (groups) {
    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('.cancel').click(function () {
      $(this).closest('.modal').modal('hide');
    });

    wireUpTree(groups);
    wireUpContextMenu();

    ConfigureAsyncForm(elements.addGroupForm, defaultSubmitCallback(elements.addGroupForm), onGroupAdded, null, {
      onBeforeSubmit: onBeforeAddGroup,
    });
    ConfigureAsyncForm(elements.renameForm, defaultSubmitCallback(elements.renameForm), hideDialogs, null, {
      onBeforeSubmit: onBeforeRename,
    });
    ConfigureAsyncForm(elements.deleteForm, defaultSubmitCallback(elements.deleteForm), hideDialogs, null, {
      onBeforeSubmit: onBeforeDelete,
    });
    ConfigureAsyncForm(elements.addChildForm, defaultSubmitCallback(elements.addChildForm), onChildGroupAdded, null, {
      onBeforeSubmit: onBeforeAddGroup,
    });
  };

  function wireUpTree(groups) {
    elements.groupDiv.tree({
      data: groups,
      saveState: false,
      dragAndDrop: true,
      selectable: false,
      autoOpen: true,

      onCreateLi: function (node, $li) {
        $li.attr('node-id', node.id);

        if (node.type == 'resource') {
          $li.addClass('group-resource');
          $li
            .find('.jqtree-title')
            .after(
              '&nbsp;<span class="remove-resource bi bi-x-lg text-danger icon remove" node-id="' + node.id + '"></span>'
            );
        } else {
          $li.find('.jqtree-element').droppable({
            drop: function (event, ui) {
              $(this).removeClass('drop-resource');
              addResource(node, ui.draggable);
            },
            over: function (event, ui) {
              $(this).addClass('drop-resource');
            },
            out: function (event, ui) {
              $(this).removeClass('drop-resource');
            },
          });
        }
      },

      onCanMove: function (node) {
        return !node.resource_id;
      },

      onCanMoveTo: function (moved_node, target_node, position) {
        if (moved_node.resource_id) {
          return canAddResource(target_node, moved_node.resource_id);
        }

        return true;
      },
    });

    elements.groupDiv.bind('tree.move', function (event) {
      moveNode(
        event.move_info.moved_node,
        event.move_info.target_node,
        event.move_info.previous_parent,
        event.move_info.position
      );
    });

    elements.groupDiv.on('click', '.remove-resource', function (e) {
      var nodeId = $(this).attr('node-id');
      var resourceNode = elements.groupDiv.tree('getNodeById', nodeId);
      removeResource(resourceNode);
    });

    $('.resource-draggable').draggable({ revert: true });

    elements.addGroupButton.click(function (e) {
      e.preventDefault();
      elements.addGroupForm.submit();
    });
  }

  function wireUpContextMenu() {
    function getNode(options) {
      var id = options.$trigger.parent().attr('node-id');
      return elements.groupDiv.tree('getNodeById', id);
    }

    elements.groupDiv.contextMenu({
      selector: '.ui-droppable',
      callback: function (key, options) {},
      items: {
        rename: {
          name: options.renameText,
          icon: 'edit',
          callback: function (key, options) {
            var node = getNode(options);
            showRename(node);
          },
        },
        delete: {
          name: options.deleteText,
          icon: 'delete',
          callback: function (key, options) {
            var node = getNode(options);
            showDelete(node);
          },
        },
        addChild: {
          name: options.addChildText,
          icon: 'add',
          callback: function (key, options) {
            var node = getNode(options);
            showAddChild(node);
          },
        },
        sep1: '---------',
        quit: { name: options.exitText, icon: 'quit' },
      },
    });
  }

  function showRename(node) {
    elements.activeId.val(node.id);
    elements.renameDialog.modal('show');

    elements.newName.val(node.name);
  }

  function showDelete(node) {
    elements.activeId.val(node.id);
    elements.deleteDialog.modal('show');
  }

  function showAddChild(node) {
    elements.activeId.val(node.id);
    $('#groupParentId').val(node.id);
    $('#childName').val('');
    $('#childName').focus();
    elements.addChildDialog.modal('show');
  }

  function onBeforeRename(arr, $form, options) {
    var id = elements.activeId.val();
    var node = elements.groupDiv.tree('getNodeById', id);
    var newName = elements.newName.val();

    elements.groupDiv.tree('updateNode', node, newName);
  }

  function onBeforeDelete(arr, $form, options) {
    var id = elements.activeId.val();
    var node = elements.groupDiv.tree('getNodeById', id);

    elements.groupDiv.tree('removeNode', node);
  }

  function addResource(targetNode, resourceElement) {
    var resourceId = resourceElement.attr('resource-id');
    if (canAddResource(targetNode, resourceId)) {
      PerformAsyncPost(getResourceActionUrl(targetNode.id, resourceId, opts.actions.addResource), {
        done: function (data) {},
      });

      elements.groupDiv.tree(
        'appendNode',
        {
          label: resourceElement.attr('resource-name'),
          id: resourceId,
          resource_id: resourceId,
          type: 'resource',
        },
        targetNode
      );
    }
  }

  function removeResource(resourceNode) {
    var resourceId = resourceNode.resource_id;
    PerformAsyncPost(getResourceActionUrl(resourceNode.group_id, resourceId, opts.actions.removeResource), {
      done: function (data) {},
    });

    elements.groupDiv.tree('removeNode', resourceNode);
  }

  function canAddResource(targetNode, resourceId) {
    var canAdd = true;
    $.each(targetNode.children, function (index, value) {
      if (value.resource_id && value.resource_id == resourceId) {
        canAdd = false;
      }
    });
    return canAdd;
  }

  function onGroupAdded(data) {
    var parentNode = null;

    data = JSON.parse(data);
    if (data.parent_id && data.parent_id != null && data.parent_id != '') {
      parentNode = elements.groupDiv.tree('getNodeById', data.parent_id);
    }
    elements.groupDiv.tree('appendNode', data, parentNode);
  }

  function onChildGroupAdded(data) {
    onGroupAdded(data);

    hideDialogs(data);
  }

  function onBeforeAddGroup(arr, $form, options) {
    var newGroup = $form.find('.new-group').first();
    var groupName = $.trim(newGroup.val());

    if (groupName != '') {
      $form.find('.new-group').val('');
      return true;
    }

    return false;
  }

  function hideDialogs(data) {
    $.each($('div.modal'), function (index, element) {
      if ($(element).is(':visible')) {
        $(element).modal('hide');
      }
    });
  }

  function getMoveNodeUrl(targetNodeId, movedNodeId, previousId, type, action) {
    return (
      options.submitUrl +
      '?gid=' +
      targetNodeId +
      '&nid=' +
      movedNodeId +
      '&type=' +
      type +
      '&pid=' +
      previousId +
      '&action=' +
      action
    );
  }

  function moveNode(movedNode, targetNode, previousNode, newPosition) {
    var movedId = movedNode.id;
    var targetNodeId = targetNode.id;
    if (movedNode.type == 'resource') {
      movedId = movedNode.resource_id;
    }
    if (newPosition == 'before') {
      targetNodeId = null;
    }
    if (newPosition == 'after') {
      targetNodeId = targetNode.parent.id;
    }
    var previousId = previousNode.id;
    PerformAsyncPost(getMoveNodeUrl(targetNodeId, movedId, previousId, movedNode.type, opts.actions.moveNode), {
      done: function (data) {},
    });
  }

  var getResourceActionUrl = function (targetGroupId, resourceId, action) {
    return options.submitUrl + '?gid=' + targetGroupId + '&rid=' + resourceId + '&action=' + action;
  };

  var defaultSubmitCallback = function (form) {
    return function () {
      return options.submitUrl + '?action=' + form.attr('ajaxAction') + '&nid=' + elements.activeId.val();
    };
  };
}
