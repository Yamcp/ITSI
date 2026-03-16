/**
 * Modo claro / Modo oscuro - ITSI Departamento de Vinculación
 * Persiste la preferencia en localStorage (itsi-theme).
 */
(function () {
  var STORAGE_KEY = 'itsi-theme';
  var THEME_LIGHT = 'light';
  var THEME_DARK = 'dark';

  function getStoredTheme() {
    try {
      return localStorage.getItem(STORAGE_KEY) || THEME_LIGHT;
    } catch (e) {
      return THEME_LIGHT;
    }
  }

  function setStoredTheme(theme) {
    try {
      localStorage.setItem(STORAGE_KEY, theme);
    } catch (e) {}
  }

  function applyTheme(theme) {
    var doc = document.documentElement;
    if (theme === THEME_DARK) {
      doc.setAttribute('data-bs-theme', THEME_DARK);
    } else {
      doc.setAttribute('data-bs-theme', THEME_LIGHT);
    }
    setStoredTheme(theme);
    updateToggleLabels();
  }

  function toggleTheme() {
    var current = getStoredTheme();
    var next = current === THEME_DARK ? THEME_LIGHT : THEME_DARK;
    applyTheme(next);
    return next;
  }

  function updateToggleLabels() {
    var theme = getStoredTheme();
    var label = theme === THEME_DARK ? 'Modo oscuro' : 'Modo claro';
    var badge = theme === THEME_DARK ? 'Oscuro' : 'Claro';
    var iconSun = theme === THEME_DARK ? 'fa-moon' : 'fa-sun';
    var iconMoon = theme === THEME_DARK ? 'fa-sun' : 'fa-moon';
    document.querySelectorAll('.sidebar-theme-toggle').forEach(function (btn) {
      var lbl = btn.querySelector('.theme-label');
      var badgeEl = btn.querySelector('.theme-mode-badge');
      var iconSpan = btn.querySelector('.theme-toggle-icon');
      if (lbl) lbl.textContent = theme === THEME_DARK ? 'Modo oscuro' : 'Modo claro';
      if (badgeEl) badgeEl.textContent = badge;
      if (iconSpan) {
        var i = iconSpan.querySelector('i');
        if (i) i.className = 'fa-solid fa-fw ' + (theme === THEME_DARK ? 'fa-moon' : 'fa-sun');
      }
    });
  }

  function init() {
    var saved = getStoredTheme();
    applyTheme(saved);

    document.querySelectorAll('.sidebar-theme-toggle').forEach(function (btn) {
      btn.removeEventListener('click', onToggleClick);
      btn.addEventListener('click', onToggleClick);
    });
  }

  function onToggleClick(e) {
    e.preventDefault();
    toggleTheme();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  window.itsiTheme = {
    getTheme: getStoredTheme,
    setTheme: applyTheme,
    toggle: toggleTheme
  };
})();
