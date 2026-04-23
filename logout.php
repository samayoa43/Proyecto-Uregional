<?php
session_start();
session_unset();    // Vacía las variables
session_destroy();  // Destruye la mochila por completo
header("Location: login.php");
exit();
?>
