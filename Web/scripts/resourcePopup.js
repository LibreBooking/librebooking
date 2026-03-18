(function () {
  // WeakMap para almacenar datos asociados a elementos
  const elementData = new WeakMap();

  // Función helper para obtener/establecer datos en elementos
  function getData(element, key, defaultValue) {
    const data = elementData.get(element) || {};
    return data[key] !== undefined ? data[key] : defaultValue;
  }

  function setData(element, key, value) {
    const data = elementData.get(element) || {};
    data[key] = value;
    elementData.set(element, data);
  }

  // Función helper para posicionar elementos
  function positionElement(element, target, position) {
    if (!element || !target) return;

    const targetRect = target.getBoundingClientRect();
    const elementRect = element.getBoundingClientRect();

    let top, left;
    let verticalPosition = position.split(' ')[1] || 'bottom'; // Posición vertical inicial

    // Detectar espacio disponible y elegir posición automáticamente
    const spaceBelow = window.innerHeight - targetRect.bottom;
    const spaceAbove = targetRect.top;
    const elementHeight = elementRect.height;
    const gapFromViewport = 10; // Margen mínimo desde el borde de la ventana

    // Si no hay espacio abajo pero hay espacio arriba, posicionar arriba
    if (spaceBelow < elementHeight + gapFromViewport && spaceAbove > elementHeight + gapFromViewport) {
      verticalPosition = 'top';
    } else {
      // Por defecto usa la posición especificada
      verticalPosition = position.split(' ')[1] || 'bottom';
    }

    // Parsear posición (por defecto 'left bottom')
    const [my, at] = [position.split(' ')[0] || 'left', verticalPosition];

    // Calcular posición basada en el target
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

  // Función para convertir NodeList/elementos a array
  function toElements(target) {
    if (!target) {
      return [];
    }
    if (target instanceof Element) {
      return [target];
    }
    if (Array.isArray(target)) {
      return target.filter((item) => item instanceof Element);
    }
    if (target.length != null) {
      return Array.prototype.slice.call(target).filter((item) => item instanceof Element);
    }
    return [];
  }

  // Función principal
  function bindResourceDetails(target, resourceId, options) {
    const opts = Object.assign({ preventClick: false, position: 'left bottom' }, options || {});

    const elements = toElements(target);

    elements.forEach((element) => {
      const showEvent = element.getAttribute('data-show-event') || 'mouseenter';
      element.removeAttribute('resource-details-bound');
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
    }, 500);
    setData(tag, 'timeoutId', timeoutId);
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

    tag.addEventListener('mouseenter', () => {
      clearTimeout(getData(tag, 'timeoutId'));
    });

    tag.addEventListener('mouseleave', () => {
      hideDiv();
    });

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
          // Mostrar indicador de carga
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
      }, 500);
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

  // Exportar función global
  window.bindResourceDetails = bindResourceDetails;
})();
