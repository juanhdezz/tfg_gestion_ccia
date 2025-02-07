// resources/js/dark-mode.js

// Inicializar el modo oscuro
if (localStorage.getItem('darkMode') === 'true' || 
    (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}

// Inicializar el toggle de Flowbite
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el botón de toggle
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Cambiar el ícono basado en el modo actual
    if (localStorage.getItem('darkMode') === 'true' ||
        (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon?.classList.remove('hidden');
        themeToggleDarkIcon?.classList.add('hidden');
    } else {
        themeToggleDarkIcon?.classList.remove('hidden');
        themeToggleLightIcon?.classList.add('hidden');
    }

    // Event listener para el botón de toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    themeToggleBtn?.addEventListener('click', function() {
        // Toggle de las clases
        document.documentElement.classList.toggle('dark');

        // Toggle de los íconos
        themeToggleDarkIcon?.classList.toggle('hidden');
        themeToggleLightIcon?.classList.toggle('hidden');

        // Guardar preferencia
        localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
    });
});