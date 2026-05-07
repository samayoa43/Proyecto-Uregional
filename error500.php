<?php
// Opción A: Forzar el encabezado de respuesta
http_response_code(500);
exit;

// Opción B: Provocar un error fatal de sintaxis
// Esto detiene la ejecución y lanza el 500 si la configuración del servidor es estándar
throw new Exception("Error 500 forzado");
?>
