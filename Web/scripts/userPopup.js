(function () {
  var stateMap = new WeakMap();
  var popupCache = new Map();
  var inFlightRequests = new Map();

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

  function toElements(target) {
    if (window.UiTooltips && typeof window.UiTooltips.toElements === 'function') {
      return window.UiTooltips.toElements(target);
    }

    if (!target) {
      return [];
    }

    if (target instanceof Element) {
      return [target];
    }

    if (Array.isArray(target)) {
      return target.filter(function (item) {
        return item instanceof Element;
      });
    }

    if (target.length != null) {
      return Array.prototype.slice.call(target).filter(function (item) {
        return item instanceof Element;
      });
    }

    return [];
  }

  function mergeOptions(options) {
    var defaultDelay = window.getTooltipDelay();

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
    if (!window.UiTooltips || typeof window.UiTooltips.showManualPopup !== 'function') {
      element.setAttribute('title', content);
      return;
    }

    window.UiTooltips.showManualPopup(element, content, tooltipClass);
  }

  function hideTooltip(element) {
    if (!window.UiTooltips || typeof window.UiTooltips.hide !== 'function') {
      return;
    }

    window.UiTooltips.hide(element);
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
    var elements = toElements(target);
    elements.forEach(function (element) {
      if (stateMap.has(element) || element.getAttribute('user-details-bound') === '1') {
        return;
      }
      bindPopup(element, userId, opts);
    });

    return target;
  };
})();
