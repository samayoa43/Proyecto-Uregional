document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // 1. LÓGICA DEL MENÚ HAMBURGUESA
    // ==========================================
    const menuToggle = document.getElementById('menuToggle');
    const sideMenu = document.getElementById('sideMenu');

    if(menuToggle && sideMenu) {
        // Abrir o cerrar cuando se hace clic en el botón de hamburguesa
        menuToggle.addEventListener('click', function() {
            sideMenu.classList.toggle('active');
            // Esta línea le avisa al resto de la página que ocupe el espacio vacío
            document.querySelector('.main-container').classList.toggle('expandido'); 
        });

        // Cerrar el menú al hacer clic en cualquier parte fuera de él
        document.addEventListener('click', function(event) {
            // Verificamos si el menú tiene la clase 'active' (está abierto)
            const isMenuOpen = sideMenu.classList.contains('active');
            // Verificamos si el clic fue AFUERA del menú
            const isClickOutsideMenu = !sideMenu.contains(event.target);
            // Verificamos si el clic fue AFUERA del botón de hamburguesa
            const isClickOutsideButton = !menuToggle.contains(event.target);

            if (isMenuOpen && isClickOutsideMenu && isClickOutsideButton) {
                sideMenu.classList.remove('active'); // Oculta el menú
                document.querySelector('.main-container').classList.remove('expandido'); // Restaura el espacio
            }
        });
    }

    // ==========================================
    // 2. LÓGICA DEL MODO OSCURO
    // ==========================================
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;

    // Revisar si el usuario ya había elegido modo oscuro antes
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        body.classList.add('dark-mode');
        if (themeToggle) themeToggle.textContent = '☀️'; // Cambia el ícono a un sol
    }

    // Acción al hacer clic en el botón de la luna/sol
    if(themeToggle) {
        themeToggle.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            
            // Si el modo oscuro se activa, guardamos la preferencia y ponemos el Sol
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                themeToggle.textContent = '☀️';
            } else {
                // Si se desactiva, guardamos "light" y ponemos la Luna
                localStorage.setItem('theme', 'light');
                themeToggle.textContent = '🌙';
            }
        });
    }
});