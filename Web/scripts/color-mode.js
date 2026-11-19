/*
 * Light/dark color mode.
 *
 * Loaded synchronously in <head>, before any CSS, so Bootstrap's
 * data-bs-theme is set before the page renders and it doesn't flash light
 * first.
 *
 * The site default comes from css.color.mode (data-lb-color-mode on <html>).
 * A choice made with the navbar toggle is stored per browser and overrides
 * the site default. "auto" follows prefers-color-scheme and switches live
 * when the browser or OS setting changes.
 */
(function () {
  'use strict';

  const STORAGE_KEY = 'lb-color-mode';
  const MODES = ['auto', 'light', 'dark'];
  const ICONS = {
    auto: 'bi-circle-half',
    light: 'bi-sun-fill',
    dark: 'bi-moon-stars-fill',
  };

  const root = document.documentElement;
  const darkQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

  function isMode(value) {
    return MODES.indexOf(value) !== -1;
  }

  function getStoredMode() {
    try {
      const mode = window.localStorage.getItem(STORAGE_KEY);
      return isMode(mode) ? mode : null;
    } catch {
      return null;
    }
  }

  function storeMode(mode) {
    try {
      window.localStorage.setItem(STORAGE_KEY, mode);
    } catch {
      // Storage unavailable (private mode, blocked cookies): the choice
      // still applies to the current page.
    }
  }

  function getSiteMode() {
    const mode = root.getAttribute('data-lb-color-mode');
    return isMode(mode) ? mode : 'auto';
  }

  let selectedMode = getStoredMode() || getSiteMode();

  function resolveTheme(mode) {
    if (mode === 'auto') {
      return darkQuery && darkQuery.matches ? 'dark' : 'light';
    }
    return mode;
  }

  function updateToggle() {
    document.querySelectorAll('[data-lb-color-mode-value]').forEach(function (item) {
      const active = item.getAttribute('data-lb-color-mode-value') === selectedMode;
      item.classList.toggle('active', active);
      item.setAttribute('aria-pressed', active ? 'true' : 'false');
    });

    const icon = document.getElementById('colorModeIcon');
    if (icon) {
      MODES.forEach(function (mode) {
        icon.classList.toggle(ICONS[mode], mode === selectedMode);
      });
    }
  }

  function apply() {
    const theme = resolveTheme(selectedMode);
    root.setAttribute('data-bs-theme', theme);

    const meta = document.querySelector('meta[name="color-scheme"]');
    if (meta) {
      meta.setAttribute('content', theme);
    }

    updateToggle();
  }

  apply();

  if (darkQuery) {
    if (darkQuery.addEventListener) {
      darkQuery.addEventListener('change', apply);
    } else if (darkQuery.addListener) {
      darkQuery.addListener(apply);
    }
  }

  // Keep other open tabs in sync with a choice made in this one.
  window.addEventListener('storage', function (event) {
    if (event.key === STORAGE_KEY) {
      selectedMode = getStoredMode() || getSiteMode();
      apply();
    }
  });

  document.addEventListener('DOMContentLoaded', updateToggle);

  document.addEventListener('click', function (event) {
    const item = event.target.closest ? event.target.closest('[data-lb-color-mode-value]') : null;
    if (!item) {
      return;
    }

    event.preventDefault();
    const mode = item.getAttribute('data-lb-color-mode-value');
    if (!isMode(mode)) {
      return;
    }

    selectedMode = mode;
    storeMode(mode);
    apply();
  });
})();
