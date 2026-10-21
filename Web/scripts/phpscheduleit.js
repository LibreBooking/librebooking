/* exported eraseCookie, getQueryStringValue, init, validateEmail, cookies */
// Cookie functions from http://www.quirksmode.org/js/cookies.html //

function startsWith(haystack, needle) {
  return haystack.slice(0, needle.length) == needle;
}

function createCookie(name, value, days, path) {
  var getLocation = function (href) {
    var l = document.createElement('a');
    l.href = href;
    return l;
  };

  if (!path) {
    path = '/';
  } else {
    var location = getLocation(path);
    path = location.pathname;
    if (!startsWith(path, '/')) {
      path = '/' + path;
    }
  }
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    var expires = '; expires=' + date.toGMTString();
  } else {
    var expires = '';
  }
  document.cookie = name + '=' + value + expires + '; path=' + path;
}

function readCookie(name) {
  var nameEQ = name + '=';
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1, c.length);
    }
    if (c.indexOf(nameEQ) == 0) {
      return c.substring(nameEQ.length, c.length);
    }
  }
  return null;
}

function eraseCookie(name, path) {
  createCookie(name, '', -30, path);
}

function getQueryStringValue(name) {
  name = name.replace(/[\[]/, '\\\[').replace(/[\]]/, '\\\]');
  var regexS = '[\\?&]' + name + '=([^&#]*)';
  var regex = new RegExp(regexS);
  var results = regex.exec(window.location.href);
  if (results == null) {
    return '';
  } else {
    return decodeURIComponent(results[1].replace(/\+/g, ' '));
  }
}

function init() {}

function validateEmail(email) {
  var re =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

var cookies = {
  // cookieName: 'dismissed',

  isDismissed: function (id) {
    var dismissed = readCookie('dismissed');

    if (!dismissed) {
      return false;
    }

    var idsDismissed = dismissed.split(',');

    return idsDismissed.indexOf(id) !== -1;
  },

  dismiss: function (id, path) {
    var dismissed = readCookie('dismissed');

    if (!dismissed) {
      dismissed = [];
    } else {
      dismissed = dismissed.split(',');
    }
    if (dismissed.indexOf(id) === -1) {
      dismissed.push(id);
    }
    createCookie('dismissed', dismissed, 30, path);
  },
};

document.addEventListener('DOMContentLoaded', function () {
  var buttonUp = document.getElementById('button-up');

  buttonUp.addEventListener('click', scrollUp);

  function scrollUp() {
    var currentScroll = document.documentElement.scrollTop;
    if (currentScroll > 0) {
      window.scrollTo(0, currentScroll - currentScroll / 1);
    }
  }

  window.onscroll = function () {
    var scroll = document.documentElement.scrollTop;
    if (scroll > 500) {
      buttonUp.style.transform = 'scale(1)';
    } else if (scroll < 500) {
      buttonUp.style.transform = 'scale(0)';
    }
  };
});

function initializeAccordions() {
  document.querySelectorAll('.accordion-item').forEach((item) => {
    const id = item.id;
    const collapse = item.querySelector('.accordion-collapse');
    const button = item.querySelector('.accordion-button');
    if (!id || !collapse) return;

    // Restore saved state
    const state = localStorage.getItem(id);
    const expanded = state !== 'collapsed';
    collapse.classList.toggle('show', expanded);
    if (button) {
      button.classList.toggle('collapsed', !expanded);
      button.setAttribute('aria-expanded', expanded);
    }

    // Save the state when it finishes opening or closing
    collapse.addEventListener('shown.bs.collapse', (e) => {
      if (e.target === collapse) localStorage.setItem(id, 'expanded');
    });
    collapse.addEventListener('hidden.bs.collapse', (e) => {
      if (e.target === collapse) localStorage.setItem(id, 'collapsed');
    });
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeAccordions);
} else {
  initializeAccordions();
}

// Exposed on window because it's invoked from inline onclick="" handlers in
// Smarty templates, which ESLint has no visibility into for usage checks.
window.showToastById = function (id) {
  var toastEl = document.getElementById(id);
  if (toastEl && window.bootstrap && window.bootstrap.Toast) {
    toastEl.classList.remove('d-none');
    window.bootstrap.Toast.getOrCreateInstance(toastEl).show();
  }
};

window.copyUrlToClipboard = function (url) {
  if (!url || url === '#') {
    return;
  }
  var showToast = function () {
    window.showToastById('clipboardCopyToast');
  };
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard
      .writeText(url)
      .then(showToast)
      .catch(function () {
        if (fallbackCopyToClipboard(url)) {
          showToast();
        }
      });
  } else {
    if (fallbackCopyToClipboard(url)) {
      showToast();
    }
  }
};

function fallbackCopyToClipboard(url) {
  var textarea = document.createElement('textarea');
  textarea.value = url;
  textarea.style.position = 'fixed';
  textarea.style.opacity = '0';
  document.body.appendChild(textarea);
  textarea.select();
  var succeeded = false;
  try {
    succeeded = document.execCommand('copy');
  } catch {
    succeeded = false;
  } finally {
    document.body.removeChild(textarea);
  }
  return succeeded;
}

// Names are stored entity-encoded (e.g. &#039;); jqTree escapes again, so decode once to plain text.
// eslint-disable-next-line no-unused-vars -- Used by page scripts loaded separately.
function decodeNames(nodes) {
  return _.map(nodes, function (n) {
    const copy = _.extend({}, n);
    _.each(['name', 'label'], function (k) {
      if (typeof copy[k] === 'string') copy[k] = HtmlDecode(copy[k]);
    });
    if (n.children) copy.children = decodeNames(n.children);
    return copy;
  });
}

/**
 * Gets an existing Bootstrap Collapse instance or creates a new one.
 *
 * @param {HTMLElement} element The Collapse element.
 * @returns {bootstrap.Collapse|null} The Collapse instance or null.
 */
window.getCollapseInstance = (element) => {
  if (!element) {
    return null;
  }

  return bootstrap.Collapse.getOrCreateInstance(element, {
    toggle: false,
  });
};

const collapseSyncGetters = new WeakMap();

/**
 * Synchronizes a Bootstrap Collapse element with its desired state.
 *
 * @param {HTMLElement} element The Collapse element.
 * @param {Function} getShouldShow Returns whether the element should be shown.
 */
window.syncCollapse = (element, getShouldShow) => {
  if (!element || typeof getShouldShow !== 'function') {
    return;
  }

  const collapse = getCollapseInstance(element);

  if (!collapse) {
    return;
  }

  collapseSyncGetters.set(element, getShouldShow);

  const sync = () => {
    if (element.classList.contains('collapsing')) {
      return;
    }

    const shouldShow = collapseSyncGetters.get(element)();
    const isShown = element.classList.contains('show');

    if (shouldShow && !isShown) {
      collapse.show();
    } else if (!shouldShow && isShown) {
      collapse.hide();
    }
  };

  if (!element.dataset.collapseSyncInitialized) {
    element.addEventListener('shown.bs.collapse', sync);
    element.addEventListener('hidden.bs.collapse', sync);
    element.dataset.collapseSyncInitialized = 'true';
  }

  sync();
};
