<?php
session_start();

// Tiempo de inactividad máximo en segundos (15 minutos = 900 segundos)
define('INACTIVITY_LIMIT', 900);

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_login_status']) || $_SESSION['user_login_status'] != 1) {
    header("location: ../login.php");
    exit;
}

// Verificar el tiempo de inactividad
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > INACTIVITY_LIMIT)) {
    // Si ha pasado el límite de inactividad, destruir la sesión
    session_unset();     // Borrar todas las variables de sesión
    session_destroy();   // Destruir la sesión
    
    // Verificar si la solicitud es AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // Enviar una respuesta JSON indicando expiración de sesión
        echo json_encode(['status' => 'session_expired']);
    } else {
        // Redirigir a login.php para solicitudes normales
        header("location: ../login.php?message=SessionExpired");
    }
    exit;
}

// Actualizar el tiempo de la última actividad
$_SESSION['last_activity'] = time();
?>
