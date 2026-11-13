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
      data: decodeNames(groups),
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

  function attachContextMenu(scope, selector, getItems) {
    const menu = document.createElement('ul');
    menu.className = 'dropdown-menu';
    menu.style.position = 'fixed';
    document.body.appendChild(menu);

    const close = () => menu.classList.remove('show');

    scope.addEventListener('contextmenu', (e) => {
      const target = e.target.closest(selector);
      if (!target || !scope.contains(target)) return;
      e.preventDefault();

      menu.replaceChildren();
      getItems(target).forEach((item) => {
        const li = document.createElement('li');
        if (item === 'hr') {
          li.innerHTML = '<hr class="dropdown-divider">';
        } else {
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'dropdown-item' + (item.disabled ? ' disabled' : '');
          if (item.icon) {
            const icon = document.createElement('i');
            icon.className = 'bi ' + item.icon + ' me-2';
            btn.appendChild(icon);
          }
          btn.append(item.label);
          btn.addEventListener('click', () => {
            close();
            item.onClick?.(target);
          });
          li.appendChild(btn);
        }
        menu.appendChild(li);
      });

      menu.classList.add('show');
      menu.style.left = Math.min(e.clientX, window.innerWidth - menu.offsetWidth) + 'px';
      menu.style.top = Math.min(e.clientY, window.innerHeight - menu.offsetHeight) + 'px';
    });

    document.addEventListener('click', close);
    document.addEventListener('keydown', (e) => e.key === 'Escape' && close());
    window.addEventListener('resize', close);
    window.addEventListener('scroll', close, true);
  }

  function wireUpContextMenu() {
    function getNode(trigger) {
      var id = trigger.parentElement.getAttribute('node-id');
      return elements.groupDiv.tree('getNodeById', id);
    }

    attachContextMenu(elements.groupDiv[0], '.ui-droppable', function () {
      return [
        { label: options.renameText, icon: 'bi-pencil', onClick: (el) => showRename(getNode(el)) },
        { label: options.deleteText, icon: 'bi-trash', onClick: (el) => showDelete(getNode(el)) },
        { label: options.addChildText, icon: 'bi-plus-lg', onClick: (el) => showAddChild(getNode(el)) },
        'hr',
        { label: options.exitText, icon: 'bi-x-lg' },
      ];
    });
  }

  function showRename(node) {
    elements.activeId.val(node.id);
    elements.newName.val(node.name);
    elements.renameDialog.modal('show');
  }

  function showDelete(node) {
    elements.activeId.val(node.id);
    elements.deleteDialog.modal('show');
  }

  function showAddChild(node) {
    elements.activeId.val(node.id);
    $('#groupParentId').val(node.id);
    $('#childName').val('');
    elements.addChildDialog.one('shown.bs.modal', function () {
      $('#childName').trigger('focus');
    });
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

    data = decodeNames([JSON.parse(data)])[0];
    data.id = String(data.id);
    if (data.parent_id) {
      parentNode = elements.groupDiv.tree('getNodeById', String(data.parent_id));
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
