document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // 1. LÓGICA DEL MENÚ HAMBURGUESA
    // ==========================================
    const menuToggle = document.getElementById('menuToggle');
    const sideMenu = document.getElementById('sideMenu');

    if(menuToggle && sideMenu) {

        menuToggle.addEventListener('click', function() {
            sideMenu.classList.toggle('active');
            
            // Agregamos esta línea para empujar el contenido principal
            document.querySelector('.main-container').classList.toggle('menu-abierto');
        });

        // Cerrar el menú al hacer clic en cualquier parte fuera de él
// Cerrar el menú al hacer clic en cualquier parte fuera de él
        document.addEventListener('click', function(event) {
            const isMenuOpen = sideMenu.classList.contains('active');
            const isClickOutsideMenu = !sideMenu.contains(event.target);
            const isClickOutsideButton = !menuToggle.contains(event.target);

            if (isMenuOpen && isClickOutsideMenu && isClickOutsideButton) {
                // 1. Oculta el menú lateral
                sideMenu.classList.remove('active'); 
                
                // 2. Regresa la pantalla principal a tamaño completo
                const mainContainer = document.querySelector('.main-container');
                if(mainContainer) {
                    mainContainer.classList.remove('menu-abierto');
                }
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