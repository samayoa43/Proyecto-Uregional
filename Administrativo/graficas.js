/**
 * GRAFICAS.JS - Panel de Inteligencia Estratégica
 */
document.addEventListener('DOMContentLoaded', function() {
    // Detectamos si el modo oscuro está activo para ajustar colores de texto
    const isDark = document.body.classList.contains('dark-mode');
    const colorTexto = isDark ? '#cccccc' : '#666666';
    const colorBorde = isDark ? '#334155' : '#e2e8f0';

// --- 1. GRÁFICO DE FINANZAS (MOROSIDAD VS AL DÍA) ---
    const ctxFinanzas = document.getElementById('chartFinanzas');
    if (ctxFinanzas) {
        new Chart(ctxFinanzas, {
            type: 'bar',
            data: {
                // Nombramos las dos columnas
                labels: ['En Riesgo', 'Al Día'],
                datasets: [{
                    // Pasamos ambos valores
                    data: [window.dataMorosos || 0, window.dataAlDia || 0],
                    // Le damos color rojo a los morosos y verde a los solventes
                    backgroundColor: ['#ef4444', '#10b981'],
                    borderRadius: 5,
                    barThickness: 30 // Un poco más delgadas para que quepan bien las dos
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    // Mostramos las etiquetas (x) para que se entienda qué es cada barra
                    x: { display: true, grid: { display: false }, ticks: { color: colorTexto, font: { size: 10 } } },
                    y: { display: false, beginAtZero: true }
                }
            }
        });
    }
    
// --- 2. GRÁFICO DE ESTUDIANTES (RETENCIÓN) ---
    const ctxRetencion = document.getElementById('chartRetencion');
    if (ctxRetencion) {

        const metaAlumnos = 50; 
        const alumnosActivos = window.dataEstudiantesActivos || 0;
        const cuposLibres = metaAlumnos - alumnosActivos;

        new Chart(ctxRetencion, {
            type: 'doughnut',
            data: {
                labels: ['Activos', 'Cupos Disponibles'],
                datasets: [{
                    // Ahora la dona mostrará la proporción real entre activos y lo que falta
                    data: [alumnosActivos, cuposLibres > 0 ? cuposLibres : 0],
                    backgroundColor: ['#159cb0', colorBorde],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%', 
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // --- 3. GRÁFICO DE CURSOS (SATURACIÓN) ---
    const ctxSaturacion = document.getElementById('chartSaturacion');
    if (ctxSaturacion) {
        new Chart(ctxSaturacion, {
            type: 'bar',
            data: {
                labels: ['Saturación'],
                datasets: [{
                    label: 'Alumnos por clase',
                    data: [window.dataSaturacion || 0],
                    backgroundColor: '#eb622a',
                    borderRadius: 5,
                    barThickness: 40
                }]
            },
            options: {
                indexAxis: 'y', // Barra horizontal
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { 
                        max: 50, // Suponiendo un máximo de 50 alumnos
                        grid: { display: false },
                        ticks: { color: colorTexto, font: { size: 10 } }
                    },
                    y: { display: false }
                }
            }
        });
    }
});