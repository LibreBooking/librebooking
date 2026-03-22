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

  function showTooltip(element, content) {
    var tooltips = getUiTooltips();
    if (!tooltips || typeof tooltips.showManualPopup !== 'function') {
      element.setAttribute('title', content);
      return;
    }

    tooltips.showManualPopup(element, content, 'respopup-tooltip');
  }

  function hideTooltip(element) {
    var tooltips = getUiTooltips();
    if (!tooltips || typeof tooltips.hide !== 'function') {
      return;
    }

    tooltips.hide(element);
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
      }, getTooltipDelay());
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
    var tooltips = getUiTooltips();
    var elements = tooltips && typeof tooltips.toElements === 'function' ? tooltips.toElements(target) : [];

    if (!elements.length) {
      return target;
    }

    elements.forEach(function (element) {
      bindPopup(element, refNum, detailsUrl);
    });
    return target;
  };
})();
