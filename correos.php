<?php
// Esto le dice a tu código que traiga todas las herramientas de la carpeta 'vendor'
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Creamos una nueva "carta" o instancia de correo
$mail = new PHPMailer(true);

try {
    // 1. CONFIGURACIÓN DEL SERVIDOR SMTP (El cartero)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';               // El servidor de Gmail
    $mail->SMTPAuth   = true;                           // Activamos la autenticación
    $mail->Username   = 'Samayoacesarluis31@gmail.com';          // PON AQUÍ TU CORREO DE GMAIL
    $mail->Password   = 'xipb dihj anus dglg';             // PON AQUÍ LA CONTRASEÑA DE APLICACIÓN DE 16 LETRAS (Sin espacios)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encriptación segura
    $mail->Port       = 587;                            // Puerto seguro de Gmail

    // 2. DATOS DEL REMITENTE Y DESTINATARIO
    // Quién lo envía: (Tu correo, Nombre que verá el usuario)
    $mail->setFrom('Samayoacesarluis31@gmail.com', 'Administracion - Universidad Regional');
    
    // A quién se lo envías: (Correo del estudiante, Nombre del estudiante)
    $mail->addAddress('megaminomanga@gmail.com', 'Cesar Estudiante');

    // 3. CONTENIDO DEL CORREO
    $mail->isHTML(true); // Permitimos que el correo tenga formato HTML (negritas, colores)
    $mail->Subject = 'Prueba de envio desde mi Sistema Universitario';
    $mail->Body    = '
        <h2>¡Hola!</h2>
        <p>Si estás leyendo esto, significa que <b>PHPMailer y Composer</b> están configurados correctamente en tu servidor Laragon.</p>
        <p>Saludos desde el departamento de TI.</p>
    ';

    // 4. LA ORDEN DE ENVÍO
    $mail->send();
    echo '<h3 style="color: green;">¡Éxito! El correo ha sido enviado correctamente.</h3>';
    
} catch (Exception $e) {
    echo '<h3 style="color: red;">Error al enviar: </h3>' . $mail->ErrorInfo;
}
?>