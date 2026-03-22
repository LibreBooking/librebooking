(function () {
  // WeakMap used to store metadata associated with DOM elements.
  const elementData = new WeakMap();

  function getUiTooltips() {
    return window.UiTooltips || null;
  }

  function getTooltipDelay() {
    const tooltips = getUiTooltips();
    if (tooltips && typeof tooltips.getTooltipDelay === 'function') {
      return tooltips.getTooltipDelay();
    }

    if (typeof window.getTooltipDelay === 'function') {
      return window.getTooltipDelay();
    }

    return 500;
  }

  // Helper to read/write element-scoped data.
  function getData(element, key, defaultValue) {
    const data = elementData.get(element) || {};
    return data[key] !== undefined ? data[key] : defaultValue;
  }

  function setData(element, key, value) {
    const data = elementData.get(element) || {};
    data[key] = value;
    elementData.set(element, data);
  }

  // Helper to position the tooltip relative to the target element.
  function positionElement(element, target, position) {
    if (!element || !target) return;

    const targetRect = target.getBoundingClientRect();
    const elementRect = element.getBoundingClientRect();

    let top, left;
    let verticalPosition = position.split(' ')[1] || 'bottom'; // Initial vertical position.

    // Detect available viewport space and choose a vertical side automatically.
    const spaceBelow = window.innerHeight - targetRect.bottom;
    const spaceAbove = targetRect.top;
    const elementHeight = elementRect.height;
    const gapFromViewport = 10; // Minimum distance from the viewport edge.

    // If there is not enough room below but enough above, place it on top.
    if (spaceBelow < elementHeight + gapFromViewport && spaceAbove > elementHeight + gapFromViewport) {
      verticalPosition = 'top';
    } else {
      // Otherwise, keep the requested vertical position.
      verticalPosition = position.split(' ')[1] || 'bottom';
    }

    // Parse position tokens (default: 'left bottom').
    const [my, at] = [position.split(' ')[0] || 'left', verticalPosition];

    // Compute coordinates based on the target rectangle.
    if (at === 'bottom') {
      top = targetRect.bottom + window.scrollY;
    } else if (at === 'top') {
      top = targetRect.top + window.scrollY - elementRect.height;
    } else {
      top = targetRect.top + window.scrollY;
    }

    if (my === 'left') {
      left = targetRect.left + window.scrollX;
    } else if (my === 'right') {
      left = targetRect.right + window.scrollX - elementRect.width;
    } else {
      left = targetRect.left + window.scrollX + targetRect.width / 2 - elementRect.width / 2;
    }

    element.style.position = 'absolute';
    element.style.top = top + 'px';
    element.style.left = left + 'px';
  }

  // Public entry point: bind resource detail popup behavior.
  function bindResourceDetails(target, resourceId, options) {
    const opts = Object.assign({ preventClick: false, position: 'left bottom', rebind: false }, options || {});
    const tooltips = getUiTooltips();
    const elements = tooltips && typeof tooltips.toElements === 'function' ? tooltips.toElements(target) : [];

    if (elements.length === 0) {
      return;
    }

    elements.forEach((element) => {
      const alreadyBound = element.hasAttribute('resource-details-bound');
      if (alreadyBound && !opts.rebind) {
        return;
      }
      if (alreadyBound && opts.rebind) {
        element.removeAttribute('resource-details-bound');
      }
      const showEvent = element.getAttribute('data-show-event') || 'mouseenter';
      setupResourceDetails(element, resourceId, showEvent, opts);
    });
  }

  function getDiv() {
    let div = document.getElementById('resourceDetailsDiv');
    if (!div) {
      div = document.createElement('div');
      div.id = 'resourceDetailsDiv';
      div.style.display = 'none';
      div.style.zIndex = '1000';
      document.body.appendChild(div);
    }
    return div;
  }

  function hideDiv() {
    const tag = getDiv();
    const timeoutId = setTimeout(() => {
      tag.style.display = 'none';
    }, getTooltipDelay());
    setData(tag, 'timeoutId', timeoutId);
  }

  function ensureDivHoverHandlers(tag) {
    if (getData(tag, 'hoverHandlersBound', false)) {
      return;
    }

    tag.addEventListener('mouseenter', () => {
      clearTimeout(getData(tag, 'timeoutId'));
    });

    tag.addEventListener('mouseleave', () => {
      hideDiv();
    });

    setData(tag, 'hoverHandlersBound', true);
  }

  function setupResourceDetails(resourceNameElement, resourceId, showEvent, opts) {
    if (resourceNameElement.getAttribute('resource-details-bound') === '1') {
      return;
    }

    if (opts.preventClick) {
      resourceNameElement.addEventListener('click', (e) => {
        e.preventDefault();
      });
    }

    const tag = getDiv();

    ensureDivHoverHandlers(tag);

    let hoverTimer;

    resourceNameElement.addEventListener(showEvent, () => {
      if (hoverTimer) {
        clearTimeout(hoverTimer);
        hoverTimer = null;
      }

      hoverTimer = setTimeout(() => {
        const tag = getDiv();
        clearTimeout(getData(tag, 'timeoutId'));

        const cachedData = getData(tag, 'resourcePopup' + resourceId);
        if (cachedData != null) {
          showData(cachedData);
        } else {
          // Show a loading state while fetching popup content.
          tag.innerHTML = 'Loading...';
          tag.style.display = 'block';
          positionElement(tag, resourceNameElement, opts.position);

          fetch('ajax/resource_details.php?rid=' + resourceId, {
            method: 'GET',
            cache: 'default',
          })
            .then((response) => {
              if (!response.ok) {
                throw new Error('Network response was not ok');
              }
              return response.text();
            })
            .then((data) => {
              setData(tag, 'resourcePopup' + resourceId, data);
              showData(data);
            })
            .catch((error) => {
              tag.innerHTML = 'Error loading resource data!';
              tag.style.display = 'block';
              console.error('Error loading resource details:', error);
            });
        }

        function showData(data) {
          tag.innerHTML = data;
          tag.style.display = 'block';

          // Bind close buttons
          const closeButtons = tag.querySelectorAll('.hideResourceDetailsPopup');
          closeButtons.forEach((btn) => {
            btn.addEventListener('click', (e) => {
              e.preventDefault();
              hideDiv();
            });
          });

          positionElement(tag, resourceNameElement, opts.position);
        }
      }, getTooltipDelay());
    });

    resourceNameElement.addEventListener('mouseleave', () => {
      if (hoverTimer) {
        clearTimeout(hoverTimer);
        hoverTimer = null;
      }
      hideDiv();
    });

    resourceNameElement.setAttribute('resource-details-bound', '1');
  }

  // Expose the binding function globally.
  window.bindResourceDetails = bindResourceDetails;
})();
