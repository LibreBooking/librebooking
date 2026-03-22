(function () {
  var stateMap = new WeakMap();
  var popupCache = new Map();
  var inFlightRequests = new Map();

  function getUiTooltips() {
    return window.UiTooltips || null;
  }

  function getTooltipDelay() {
    var tooltips = getUiTooltips();
    if (tooltips && typeof tooltips.getTooltipDelay === 'function') {
      return tooltips.getTooltipDelay();
    }

    if (typeof window.getTooltipDelay === 'function') {
      return window.getTooltipDelay();
    }

    return 500;
  }

  function fetchUserPopupHtml(userId) {
    if (popupCache.has(userId)) {
      return Promise.resolve(popupCache.get(userId));
    }

    if (inFlightRequests.has(userId)) {
      return inFlightRequests.get(userId);
    }

    var query = new URLSearchParams({ uid: userId });
    var request = fetch('ajax/user_details.php?' + query.toString(), {
      method: 'GET',
    })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('Error loading user data!');
        }
        return response.text();
      })
      .then(function (data) {
        popupCache.set(userId, data);
        return data;
      })
      .finally(function () {
        inFlightRequests.delete(userId);
      });

    inFlightRequests.set(userId, request);
    return request;
  }

  function mergeOptions(options) {
    var defaultDelay = getTooltipDelay();

    var defaults = {
      preventClick: true,
      delay: defaultDelay,
      tooltipClass: 'userpopup-tooltip',
    };

    if (!options) {
      return defaults;
    }

    return Object.assign({}, defaults, options);
  }

  function showTooltip(element, content, tooltipClass) {
    var tooltips = getUiTooltips();
    if (!tooltips || typeof tooltips.showManualPopup !== 'function') {
      element.setAttribute('title', content);
      return;
    }

    tooltips.showManualPopup(element, content, tooltipClass);
  }

  function hideTooltip(element) {
    var tooltips = getUiTooltips();
    if (!tooltips || typeof tooltips.hide !== 'function') {
      return;
    }

    tooltips.hide(element);
  }

  function bindPopup(element, userId, options) {
    var existingState = stateMap.get(element);
    if (existingState) {
      element.removeEventListener('mouseenter', existingState.onEnter);
      element.removeEventListener('mouseleave', existingState.onLeave);
      element.removeEventListener('click', existingState.onClick);
      if (existingState.tooltipElement && existingState.onTooltipLeave) {
        existingState.tooltipElement.removeEventListener('mouseleave', existingState.onTooltipLeave);
      }
      if (existingState.hoverTimer) {
        clearTimeout(existingState.hoverTimer);
      }
    }

    var state = {
      hoverTimer: null,
      isHovering: false,
      tooltipElement: null,
      onClick: null,
      onTooltipLeave: null,
      onEnter: null,
      onLeave: null,
    };

    var getTooltipElement = function () {
      var tooltipId = element.getAttribute('aria-describedby');
      if (!tooltipId) {
        return null;
      }

      return document.getElementById(tooltipId);
    };

    if (options.preventClick) {
      state.onClick = function (e) {
        e.preventDefault();
      };
      element.addEventListener('click', state.onClick);
    }

    state.onEnter = function () {
      if (state.hoverTimer) {
        clearTimeout(state.hoverTimer);
        state.hoverTimer = null;
      }

      state.isHovering = true;
      state.hoverTimer = setTimeout(function () {
        var idToLoad = userId || element.getAttribute('data-userid');
        if (!idToLoad) {
          return;
        }

        if (popupCache.has(idToLoad)) {
          showTooltip(element, popupCache.get(idToLoad), options.tooltipClass);
          return;
        }

        fetchUserPopupHtml(idToLoad)
          .then(function (data) {
            if (state.isHovering) {
              showTooltip(element, data, options.tooltipClass);
            }
          })
          .catch(function (error) {
            if (state.isHovering) {
              showTooltip(element, 'Error loading user data!', options.tooltipClass);
            }
          });
      }, options.delay);
    };

    state.onLeave = function (e) {
      state.isHovering = false;
      if (state.hoverTimer) {
        clearTimeout(state.hoverTimer);
        state.hoverTimer = null;
      }

      var nextTarget = e && e.relatedTarget ? e.relatedTarget : null;
      state.tooltipElement = getTooltipElement();
      if (state.tooltipElement && nextTarget && state.tooltipElement.contains(nextTarget)) {
        if (!state.onTooltipLeave) {
          state.onTooltipLeave = function () {
            if (!element.matches(':hover')) {
              hideTooltip(element);
            }
          };
        }

        state.tooltipElement.addEventListener('mouseleave', state.onTooltipLeave, { once: true });
        return;
      }

      hideTooltip(element);
    };

    element.addEventListener('mouseenter', state.onEnter);
    element.addEventListener('mouseleave', state.onLeave);
    element.setAttribute('user-details-bound', '1');

    stateMap.set(element, state);
  }

  window.attachUserDetailsPopup = function (target, userId, options) {
    var opts = mergeOptions(options);
    var tooltips = getUiTooltips();
    var elements = tooltips && typeof tooltips.toElements === 'function' ? tooltips.toElements(target) : [];

    if (!elements.length) {
      return target;
    }

    elements.forEach(function (element) {
      if (stateMap.has(element) || element.getAttribute('user-details-bound') === '1') {
        return;
      }
      bindPopup(element, userId, opts);
    });

    return target;
  };
})();
