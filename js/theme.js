/**
 * Sistema de Temas - Informes Moodle
 * Gestión de tema claro/oscuro con persistencia y detección automática
 */

const ThemeManager = (function() {
    'use strict';

    const STORAGE_KEY = 'informes-theme';
    const THEME_ATTR = 'data-theme';
    const THEMES = {
        LIGHT: 'light',
        DARK: 'dark',
        SYSTEM: 'system'
    };

    let currentTheme = THEMES.SYSTEM;
    let mediaQuery = null;

    /**
     * Detecta la preferencia del sistema operativo
     */
    function getSystemPreference() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return THEMES.DARK;
        }
        return THEMES.LIGHT;
    }

    /**
     * Aplica el tema al DOM
     */
    function applyTheme(theme) {
        const effectiveTheme = theme === THEMES.SYSTEM ? getSystemPreference() : theme;
        document.documentElement.setAttribute(THEME_ATTR, effectiveTheme);
        
        // Actualizar icono del toggle
        updateToggleIcon(effectiveTheme);
        
        // Evento personalizado para componentes que necesiten reaccionar
        document.dispatchEvent(new CustomEvent('themechange', {
            detail: { theme: effectiveTheme, preference: theme }
        }));
    }

    /**
     * Actualiza el icono del botón de toggle
     */
    function updateToggleIcon(effectiveTheme) {
        const toggleBtn = document.querySelector('.theme-toggle');
        if (!toggleBtn) return;

        const sunIcon = `<svg class="theme-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>`;

        const moonIcon = `<svg class="theme-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>`;

        toggleBtn.innerHTML = effectiveTheme === THEMES.DARK ? sunIcon : moonIcon;
        toggleBtn.setAttribute('aria-label', effectiveTheme === THEMES.DARK ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro');
    }

    /**
     * Guarda la preferencia del usuario
     */
    function savePreference(theme) {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (e) {
            console.warn('No se pudo guardar la preferencia de tema:', e);
        }
    }

    /**
     * Carga la preferencia guardada
     */
    function loadPreference() {
        try {
            return localStorage.getItem(STORAGE_KEY) || THEMES.SYSTEM;
        } catch (e) {
            return THEMES.SYSTEM;
        }
    }

    /**
     * Alterna entre temas
     */
    function toggle() {
        const effectiveTheme = currentTheme === THEMES.SYSTEM ? getSystemPreference() : currentTheme;
        const newTheme = effectiveTheme === THEMES.DARK ? THEMES.LIGHT : THEMES.DARK;
        setTheme(newTheme);
    }

    /**
     * Establece un tema específico
     */
    function setTheme(theme) {
        if (!Object.values(THEMES).includes(theme)) {
            console.warn(`Tema inválido: ${theme}`);
            return;
        }
        currentTheme = theme;
        savePreference(theme);
        applyTheme(theme);
    }

    /**
     * Obtiene el tema actual
     */
    function getTheme() {
        return currentTheme;
    }

    /**
     * Obtiene el tema efectivo (resuelve 'system')
     */
    function getEffectiveTheme() {
        return currentTheme === THEMES.SYSTEM ? getSystemPreference() : currentTheme;
    }

    /**
     * Inicializa el sistema de temas
     */
    function init() {
        // Cargar preferencia guardada
        currentTheme = loadPreference();
        
        // Aplicar tema inicial
        applyTheme(currentTheme);
        
        // Escuchar cambios en preferencias del sistema
        if (window.matchMedia) {
            mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            mediaQuery.addEventListener('change', () => {
                if (currentTheme === THEMES.SYSTEM) {
                    applyTheme(THEMES.SYSTEM);
                }
            });
        }

        // Configurar toggle button
        document.addEventListener('click', (e) => {
            const toggleBtn = e.target.closest('.theme-toggle');
            if (toggleBtn) {
                toggle();
            }
        });

        console.log('ThemeManager inicializado');
    }

    // API pública
    return {
        init,
        toggle,
        setTheme,
        getTheme,
        getEffectiveTheme,
        THEMES
    };
})();

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ThemeManager.init);
} else {
    ThemeManager.init();
}

// Exponer globalmente
window.ThemeManager = ThemeManager;
