(function () {
  var stateMap = new WeakMap();
  var popupCache = new Map();
  var inFlightRequests = new Map();

  function releaseInFlightRequest(cacheKey) {
    var inFlight = inFlightRequests.get(cacheKey);
    if (!inFlight) {
      return;
    }

    inFlight.consumers -= 1;
    if (inFlight.consumers <= 0) {
      inFlight.controller.abort();
      inFlightRequests.delete(cacheKey);
    }
  }

  function clearActiveRequest(state) {
    if (state.requestRelease) {
      state.requestRelease();
      state.requestRelease = null;
    }
    state.activeRequestKey = null;
  }

  function fetchReservationPopupHtml(popupUrl, reservationId) {
    var cacheKey = popupUrl + '|' + reservationId;
    if (popupCache.has(cacheKey)) {
      return {
        cacheKey: cacheKey,
        promise: Promise.resolve(popupCache.get(cacheKey)),
        release: null,
      };
    }

    if (inFlightRequests.has(cacheKey)) {
      var existingRequest = inFlightRequests.get(cacheKey);
      existingRequest.consumers += 1;

      return {
        cacheKey: cacheKey,
        promise: existingRequest.promise,
        release: function () {
          releaseInFlightRequest(cacheKey);
        },
      };
    }

    var controller = new AbortController();
    var query = new URLSearchParams({ id: reservationId });
    var request = fetch(popupUrl + '?' + query.toString(), { signal: controller.signal })
      .then(function (response) {
        if (!response.ok) {
          throw new Error(response.statusText || 'Request failed');
        }
        return response.text();
      })
      .then(function (html) {
        var trimmedHtml = html ? html.trim() : '';
        if (!trimmedHtml) {
          return '';
        }

        popupCache.set(cacheKey, trimmedHtml);
        return trimmedHtml;
      })
      .finally(function () {
        inFlightRequests.delete(cacheKey);
      });

    inFlightRequests.set(cacheKey, {
      promise: request,
      controller: controller,
      consumers: 1,
    });

    return {
      cacheKey: cacheKey,
      promise: request,
      release: function () {
        releaseInFlightRequest(cacheKey);
      },
    };
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

  function showTooltip(element, content) {
    if (!window.UiTooltips || typeof window.UiTooltips.showManualPopup !== 'function') {
      element.setAttribute('title', content);
      return;
    }

    window.UiTooltips.showManualPopup(element, content, 'respopup-tooltip');
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
      if (existingState.hoverTimer) {
        clearTimeout(existingState.hoverTimer);
      }
    }

    var popupUrl = detailsUrl || 'ajax/respopup.php';
    var state = {
      hoverTimer: null,
      isHovering: false,
      activeRequestKey: null,
      requestRelease: null,
      onEnter: null,
      onLeave: null,
    };

    state.onEnter = function () {
      state.isHovering = true;

      var reservationId = refNum || element.getAttribute('data-refnum') || element.id;
      if (!reservationId) {
        return;
      }

      if (state.hoverTimer) {
        clearTimeout(state.hoverTimer);
        state.hoverTimer = null;
      }

      state.hoverTimer = setTimeout(function () {
        clearActiveRequest(state);
        var requestHandle = fetchReservationPopupHtml(popupUrl, reservationId);
        state.activeRequestKey = requestHandle.cacheKey;
        state.requestRelease = requestHandle.release;

        requestHandle.promise
          .then(function (html) {
            if (!html) {
              return;
            }

            if (state.isHovering) {
              showTooltip(element, html);
            }
          })
          .catch(function (error) {
            if (error && error.name === 'AbortError') {
              return;
            }
            // Don't show error tooltip - just fail silently
            console.warn('Could not load reservation details:', error);
          })
          .finally(function () {
            if (state.activeRequestKey === requestHandle.cacheKey) {
              clearActiveRequest(state);
            }
          });
      }, window.getTooltipDelay());
    };

    state.onLeave = function () {
      state.isHovering = false;
      if (state.hoverTimer) {
        clearTimeout(state.hoverTimer);
        state.hoverTimer = null;
      }
      clearActiveRequest(state);
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
