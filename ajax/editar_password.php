<?php
include('is_logged.php'); // Archivo verifica que el usuario que intenta acceder a la URL está logueado
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    require_once("../libraries/password_compatibility_library.php");
}

if (empty($_POST['user_id_mod'])) {
    $errors[] = "ID vacío";
} elseif (empty($_POST['user_password_new3']) || empty($_POST['user_password_repeat3'])) {
    $errors[] = "Contraseña vacía";
} elseif ($_POST['user_password_new3'] !== $_POST['user_password_repeat3']) {
    $errors[] = "La contraseña y la repetición de la contraseña no son lo mismo";
} elseif (
    !empty($_POST['user_id_mod'])
    && !empty($_POST['user_password_new3'])
    && !empty($_POST['user_password_repeat3'])
    && ($_POST['user_password_new3'] === $_POST['user_password_repeat3'])
) {
    require_once("../config/db.php"); // Contiene las variables de configuración para conectar a la base de datos
    require_once("../config/conexion.php"); // Contiene función que conecta a la base de datos
    
    $user_id = intval($_POST['user_id_mod']);
    $user_password = $_POST['user_password_new3'];

    // Hashear la nueva contraseña
    $user_password_hash = password_hash($user_password, PASSWORD_BCRYPT);

    // Actualizar la contraseña en la base de datos
    $sql = "UPDATE users SET user_password_hash='" . $user_password_hash . "' WHERE user_id='" . $user_id . "'";
    $query = mysqli_query($con, $sql);

    // Si la contraseña ha sido actualizada exitosamente
    if ($query) {
        $messages[] = "La contraseña ha sido modificada con éxito.";
    } else {
        $errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
    }
} else {
    $errors[] = "Un error desconocido ocurrió.";
}

if (isset($errors)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> 
        <?php
        foreach ($errors as $error) {
            echo $error;
        }
        ?>
    </div>
    <?php
}
if (isset($messages)) {
    ?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>¡Bien hecho!</strong>
        <?php
        foreach ($messages as $message) {
            echo $message;
        }
        ?>
    </div>
    <?php
}
?>
