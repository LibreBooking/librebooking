function Calendar(opts) {
  var _options = opts;
  var _fullCalendar;
  var dateVar = null;

  var dayDialog = $('#dayDialog');

  var elements = {
    loadingIndicator: $('#loadingIndicator'),
    moveReservationForm: $('#moveReservationForm'),
    moveReferenceNumber: $('#moveReferenceNumber'),
    moveStartDate: $('#moveStartDate'),
    moveErrorOk: $('#moveErrorOk'),
    moveErrorDialog: $('#moveErrorDialog'),
    moveErrorsList: $('#moveErrorsList'),
  };

  // Helper functions for FullCalendar v6
  function normalizeLocale(locale) {
    return (locale || document.documentElement.lang || 'en').replace('_', '-');
  }

  function mapCurrentViewToLegacy(viewType) {
    switch (viewType) {
      case 'timeGridWeek':
      case 'listWeek':
        return 'agendaWeek';
      case 'timeGridDay':
      case 'listDay':
        return 'agendaDay';
      case 'listMonth':
      case 'dayGridMonth':
      default:
        return 'month';
    }
  }

  function mapLegacyViewToCurrent(viewName) {
    switch (viewName) {
      case 'agendaWeek':
        return 'timeGridWeek';
      case 'agendaDay':
        return 'timeGridDay';
      case 'month':
      default:
        return 'dayGridMonth';
    }
  }

  function getTimeFormatOptions(format) {
    switch (format) {
      case 'H:i':
        return { hour: '2-digit', minute: '2-digit', hour12: false };
      case 'G:i':
      case 'G.i':
        return { hour: 'numeric', minute: '2-digit', hour12: false };
      case 'h:i':
        return { hour: '2-digit', minute: '2-digit', hour12: true, meridiem: false };
      case 'h:i A':
      case 'h:i a':
        return { hour: '2-digit', minute: '2-digit', hour12: true, meridiem: 'short' };
      case 'g:i A':
      case 'g:i a':
        return { hour: 'numeric', minute: '2-digit', hour12: true, meridiem: 'short' };
      default:
        return { hour: '2-digit', minute: '2-digit', hour12: false };
    }
  }

  Calendar.prototype.init = function () {
    function showLoadingIndicator() {
      elements.loadingIndicator.removeClass('d-none');
    }

    function hideLoadingIndicator() {
      elements.loadingIndicator.addClass('d-none');
    }

    var calendarElement = document.getElementById('calendar');
    var timeFormat = getTimeFormatOptions(_options.timeFormat);

    _fullCalendar = new FullCalendar.Calendar(calendarElement, {
      themeSystem: 'standard',
      headerToolbar: {
        left: 'prev,next,today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
      },
      buttonText: {
        today: _options.todayText,
      },
      allDaySlot: false,
      weekNumbers: _options.showWeekNumbers,
      initialView: mapLegacyViewToCurrent(_options.view),
      initialDate: _options.defaultDate,
      locale: normalizeLocale(_options.locale),
      events: {
        url: _options.eventsUrl,
        extraParams: function () {
          return Object.assign({}, _options.eventsData);
        },
      },
      eventDidMount: function (info) {
        if (!_.isEmpty(info.event.id) && !$(info.el).hasClass('unreservable')) {
          $(info.el).attachReservationPopup(info.event.id);
        }
        var startStr;
        // Use currentStart to maintain a stable URL across all main views
        switch (info.view.type) {
          case 'dayGridMonth':
          case 'timeGridWeek':
          case 'timeGridDay':
          case 'listWeek':
          case 'listDay':
            startStr = dateHelper.formatDate(info.view.currentStart);
            break;
          default:
            startStr = dateHelper.formatDate(info.event.start);
        }
        var redirect =
          _options.returnTo +
          encodeURIComponent('?ct=' + mapCurrentViewToLegacy(info.view.type) + '&start=' + startStr);
        var finalUrl = info.event.url ? info.event.url.replace('[redirect]', redirect) : null;
        if (finalUrl) {
          info.el.setAttribute('href', finalUrl);
        }
      },
      eventClick: function (info) {
        var startStr =
          info.view.type === 'dayGridMonth'
            ? dateHelper.formatDate(info.view.currentStart)
            : dateHelper.formatDate(info.event.start);
        var redirect =
          _options.returnTo +
          encodeURIComponent('?ct=' + mapCurrentViewToLegacy(info.view.type) + '&start=' + startStr);
        var finalUrl = info.event.url ? info.event.url.replace('[redirect]', redirect) : null;
        if (finalUrl) {
          info.jsEvent.preventDefault();
          window.location = finalUrl;
        }
      },
      dateClick: dayClick,
      firstDay: _options.firstDay,
      views: {
        dayGridMonth: { buttonText: _options.monthText },
        timeGridDay: { buttonText: _options.dayText, slotLabelFormat: timeFormat },
        timeGridWeek: { buttonText: _options.weekText, slotLabelFormat: timeFormat },
        listWeek: { buttonText: _options.listText || 'List' },
      },
      slotLabelFormat: timeFormat,
      eventTimeFormat: timeFormat,
      loading: function (isLoading) {
        if (isLoading) {
          showLoadingIndicator();
        } else {
          hideLoadingIndicator();
        }
      },
      eventDrop: function (info) {
        var handleMoveResponse = function (result) {
          hideLoadingIndicator();
          if (result.errors.length > 0) {
            info.revert();

            var messages = result.errors.join('</li><li>');
            messages = '<li>' + messages + '</li>';
            elements.moveErrorsList.empty().append(messages);
            elements.moveErrorDialog.modal('show');
          }
        };

        elements.moveReferenceNumber.val(info.event.id);
        elements.moveStartDate.val(dateHelper.formatDate(info.event.start, true));
        ajaxPost(elements.moveReservationForm, _options.moveReservationUrl, showLoadingIndicator, handleMoveResponse);
      },
    });

    _fullCalendar.render();

    $('.reservation').each(function (index, value) {
      var refNum = $(this).attr('refNum');
      if (!$(value).hasClass('unreservable')) {
        value.attachReservationPopup(refNum);
      }
    });

    $('#calendarFilter').on('change', function () {
      var sid = '';
      var rid = '';
      var gid =
        typeof _options.eventsData.gid !== 'undefined'
          ? _options.eventsData.gid
          : typeof getQueryStringValue === 'function'
            ? getQueryStringValue('gid')
            : '';

      if ($(this).find(':selected').hasClass('schedule')) {
        sid = $(this).val().replace('s', '');
      } else {
        sid = $(this).find(':selected').prevAll('.schedule').val().replace('s', '');
        rid = $(this).val().replace('r', '');
      }

      _options.eventsData.sid = sid;
      _options.eventsData.rid = rid;
      _options.eventsData.gid = gid;
      _options.dayClickUrl = _options.dayClickUrlTemplate
        .replace('[sid]', sid)
        .replace('[rid]', rid)
        .replace('[gid]', gid);
      _options.reservationUrl = _options.reservationUrlTemplate
        .replace('[sid]', sid)
        .replace('[rid]', rid)
        .replace('[gid]', gid);
      _fullCalendar.refetchEvents();

      rebindSubscriptionData(rid, sid, gid);
    });

    $('#subscriptionContainer').on('click', '#turnOffSubscription', function (e) {
      e.preventDefault();
      PerformAsyncAction(
        $(this),
        function () {
          return opts.subscriptionDisableUrl;
        },
        null,
        function () {
          return rebindSubscriptionData('', '', '');
        }
      );
    });

    $('#subscriptionContainer').on('click', '#turnOnSubscription', function (e) {
      e.preventDefault();
      PerformAsyncAction(
        $(this),
        function () {
          return opts.subscriptionEnableUrl;
        },
        null,
        function () {
          return rebindSubscriptionData('', '', '');
        }
      );
    });

    dayDialog.find('a').click(function (e) {
      e.preventDefault();
    });

    $('#dayDialogCancel').click(function (e) {
      dayDialog.addClass('d-none');
    });

    $('#dayDialogView').click(function (e) {
      drillDownClick();
    });

    $('#dayDialogCreate').click(function (e) {
      openNewReservation();
    });

    $('#showResourceGroups').click(function (e) {
      e.preventDefault();

      var resourceGroupsContainer = $('#resourceGroupsContainer');

      if (resourceGroupsContainer.is(':visible')) {
        resourceGroupsContainer.hide();
      } else {
        if (!resourceGroupsContainer.data('positionSet')) {
          resourceGroupsContainer.position({
            my: 'left top',
            at: 'right bottom',
            of: '#showResourceGroups',
          });
        }
        resourceGroupsContainer.data('positionSet', true);
        resourceGroupsContainer.show();
      }
    });

    elements.moveErrorOk.click(function (e) {
      e.preventDefault();
      elements.moveErrorDialog.modal('hide');
    });

    function selectOwner(ui, textbox) {
      textbox.val(ui.item.label);
      _options.eventsData.uid = ui.item.value;
      _fullCalendar.refetchEvents();
    }

    function selectParticipant(ui, textbox) {
      textbox.val(ui.item.label);
      _options.eventsData.pid = ui.item.value;
      _fullCalendar.refetchEvents();
    }

    const ownerFilter = $('#ownerFilter');
    const participantFilter = $('#participantFilter');

    if (ownerFilter.length !== 0) {
      ownerFilter.userAutoComplete(opts.autocompleteUrl, selectOwner);
    }

    if (participantFilter.length !== 0) {
      participantFilter.userAutoComplete(opts.autocompleteUrl, selectParticipant);
    }

    $('#clearUserFilter').on('click', function (e) {
      _options.eventsData.uid = null;
      _options.eventsData.pid = null;
      ownerFilter.val('');
      participantFilter.val('');
      _fullCalendar.refetchEvents();
    });
  };

  Calendar.prototype.bindResourceGroups = function (resourceGroups, selectedNode) {
    if (resourceGroups.length == 0) {
      $('#showResourceGroups').hide();
      return;
    }

    // this is copied out of schedule.js, so this needs to be fixed

    function ChangeGroup(groupId) {
      RedirectToSelf('gid', /gid=\d+/i, 'gid=' + groupId, RemoveResourceId);
    }

    function ChangeResource(resourceId) {
      RedirectToSelf('rid', /rid=\d+/i, 'rid=' + resourceId, RemoveGroupId);
    }

    function RemoveResourceId(url) {
      if (!url) {
        url = window.location.href;
      }
      return url.replace(/&*rid=\d+/i, '');
    }

    function RemoveGroupId(url) {
      return url.replace(/&*gid=\d+/i, '');
    }

    function RedirectToSelf(queryStringParam, regexMatch, substitution, preProcess) {
      var url = window.location.href;
      var newUrl = window.location.href;

      if (preProcess) {
        newUrl = preProcess(url);
        newUrl = newUrl.replace(/&{2,}/i, '');
      }

      if (newUrl.indexOf(queryStringParam + '=') != -1) {
        newUrl = newUrl.replace(regexMatch, substitution);
      } else if (newUrl.indexOf('?') != -1) {
        newUrl = newUrl + '&' + substitution;
      } else {
        newUrl = newUrl + '?' + substitution;
      }

      newUrl = newUrl.replace('#', '');

      window.location = newUrl;
    }

    var groupDiv = $('#resourceGroups');
    groupDiv.tree({
      data: resourceGroups,
      saveState: 'resourceCalendar',

      onCreateLi: function (node, $li) {
        if (node.type == 'resource') {
          $li.addClass('group-resource');
        }
      },
    });

    groupDiv.bind('tree.select', function (event) {
      if (event.node) {
        var node = event.node;
        if (node.type == 'resource') {
          ChangeResource(node.resource_id);
        } else {
          ChangeGroup(node.id);
        }
      }
    });

    if (selectedNode) {
      groupDiv.tree('openNode', groupDiv.tree('getNodeById', selectedNode));
    }
  };

  var dayClick = function (info) {
    dateVar = new Date(info.date);

    if (!opts.reservable) {
      drillDownClick();
      return;
    }

    if (info.view.type.indexOf('timeGrid') === 0) {
      handleTimeClick();
    } else {
      dayDialog.removeClass('d-none');
      // Hide the create button if the filter is schedule
      var calendarFilterVal = $('#calendarFilter').val();
      if (calendarFilterVal && calendarFilterVal.startsWith('s')) {
        $('#dayDialogCreate').hide();
      } else {
        $('#dayDialogCreate').show();
      }
      dayDialog.position({
        my: 'left bottom',
        at: 'left top',
        of: info.jsEvent,
      });
    }
  };

  var handleTimeClick = function () {
    openNewReservation();
  };

  var rebindSubscriptionData = function (rid, sid, gid) {
    var url = _options.getSubscriptionUrl + '&rid=' + rid + '&sid=' + sid + '&gid=' + gid;
    ajaxGet(
      url,
      function () {},
      function (response) {
        $('#calendarSubscription').html(response);
      }
    );
  };

  var drillDownClick = function () {
    if (_fullCalendar && dateVar) {
      _fullCalendar.changeView('timeGridDay', dateVar);
      dayDialog.addClass('d-none');
    }
  };

  var openNewReservation = function () {
    var view = _fullCalendar.view;
    var end = new Date(dateVar.getTime() + 30 * 60000);
    var start = dateHelper.formatDate(dateVar);

    var url =
      _options.reservationUrl +
      '&sd=' +
      getUrlFormattedDate(dateVar) +
      '&ed=' +
      getUrlFormattedDate(end) +
      '&redirect=' +
      _options.returnTo +
      encodeURIComponent('?ct=' + mapCurrentViewToLegacy(view.type) + '&start=' + start);
    window.location = url;
  };

  var getUrlFormattedDate = function (d) {
    return encodeURIComponent(dateHelper.formatDate(d, true));
  };
}
