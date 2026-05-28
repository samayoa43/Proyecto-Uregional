// 1. Función global para iniciar el tour manualmente (por si el alumno presiona un botón de "Ayuda")
function iniciarTour() {
    introJs().setOptions({
        nextLabel: 'Siguiente >',
        prevLabel: '< Atrás',
        doneLabel: 'Entendido',
        showStepNumbers: true,
        showProgress: true
    }).start();
}

// 2. Ejecución automática solo la primera vez que el usuario entra
document.addEventListener("DOMContentLoaded", function() {
    // Verificamos en el almacenamiento del navegador si ya vio el tutorial
    if (!localStorage.getItem('tutorialVisto')) {
        
        introJs().setOptions({
            nextLabel: 'Siguiente',
            prevLabel: 'Atrás',
            doneLabel: '¡Empezar!',
            showProgress: true
        })
        .oncomplete(function() {
            // Guardamos el registro cuando termina el tutorial
            localStorage.setItem('tutorialVisto', 'true');
        })
        .onexit(function() {
            // Guardamos el registro si el usuario lo cierra a la mitad
            localStorage.setItem('tutorialVisto', 'true');
        })
        .start(); // <--- EL START VA HASTA EL FINAL, DESPUÉS DE CONFIGURAR LOS EVENTOS
    }
});