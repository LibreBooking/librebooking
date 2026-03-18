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

  function showTooltip(element, content) {
    if (!window.UiTooltips || typeof window.UiTooltips.show !== 'function') {
      element.setAttribute('title', content);
      return;
    }

    window.UiTooltips.show(element, content, {
      customClass: 'respopup-tooltip',
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

  function bindPopup(element, refNum, detailsUrl) {
    var existingState = stateMap.get(element);
    if (existingState) {
      element.removeEventListener('mouseenter', existingState.onEnter);
      element.removeEventListener('mouseleave', existingState.onLeave);
      if (existingState.controller) {
        existingState.controller.abort();
      }
    }

    var popupUrl = detailsUrl || 'ajax/respopup.php';
    var state = {
      isHovering: false,
      controller: null,
      cachedHtml: null,
      onEnter: null,
      onLeave: null,
    };

    state.onEnter = function () {
      state.isHovering = true;

      var reservationId = refNum || element.getAttribute('data-refnum') || element.id;
      if (!reservationId) {
        return;
      }

      if (state.cachedHtml) {
        showTooltip(element, state.cachedHtml);
        return;
      }

      if (state.controller) {
        state.controller.abort();
      }

      state.controller = new AbortController();
      var query = new URLSearchParams({ id: reservationId });

      fetch(popupUrl + '?' + query.toString(), { signal: state.controller.signal })
        .then(function (response) {
          if (!response.ok) {
            throw new Error(response.statusText || 'Request failed');
          }
          return response.text();
        })
        .then(function (html) {
          // Don't show tooltip if response is empty or only whitespace
          var trimmedHtml = html ? html.trim() : '';
          if (!trimmedHtml) {
            return;
          }

          state.cachedHtml = trimmedHtml;
          if (state.isHovering) {
            showTooltip(element, trimmedHtml);
          }
        })
        .catch(function (error) {
          if (error && error.name === 'AbortError') {
            return;
          }
          // Don't show error tooltip - just fail silently
          console.warn('Could not load reservation details:', error);
        });
    };

    state.onLeave = function () {
      state.isHovering = false;
      hideTooltip(element);
    };

    element.addEventListener('mouseenter', state.onEnter);
    element.addEventListener('mouseleave', state.onLeave);
    stateMap.set(element, state);
  }

  window.attachReservationPopup = function (target, refNum, detailsUrl) {
    var elements = toElements(target);
    elements.forEach(function (element) {
      bindPopup(element, refNum, detailsUrl);
    });
    return target;
  };
})();
