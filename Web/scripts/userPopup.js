(function () {
  var stateMap = new WeakMap();

  function toElements(target) {
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
    var defaults = {
      preventClick: true,
      delay: 500,
      tooltipClass: 'userpopup-tooltip',
    };

    if (!options) {
      return defaults;
    }

    return Object.assign({}, defaults, options);
  }

  function showTooltip(element, content, tooltipClass) {
    if (!window.UiTooltips || typeof window.UiTooltips.show !== 'function') {
      element.setAttribute('title', content);
      return;
    }

    window.UiTooltips.show(element, content, {
      customClass: tooltipClass,
      html: true,
      trigger: 'manual',
    });
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
      if (existingState.controller) {
        existingState.controller.abort();
      }
    }

    var state = {
      hoverTimer: null,
      isHovering: false,
      controller: null,
      cache: {},
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

        var cacheKey = 'userPopup' + idToLoad;
        if (state.cache[cacheKey] != null) {
          showTooltip(element, state.cache[cacheKey], options.tooltipClass);
          return;
        }

        if (state.controller) {
          state.controller.abort();
        }

        state.controller = new AbortController();
        var query = new URLSearchParams({ uid: idToLoad });

        fetch('ajax/user_details.php?' + query.toString(), {
          method: 'GET',
          signal: state.controller.signal,
        })
          .then(function (response) {
            if (!response.ok) {
              throw new Error('Error loading user data!');
            }
            return response.text();
          })
          .then(function (data) {
            state.cache[cacheKey] = data;
            if (state.isHovering) {
              showTooltip(element, data, options.tooltipClass);
            }
          })
          .catch(function (error) {
            if (error && error.name === 'AbortError') {
              return;
            }
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
