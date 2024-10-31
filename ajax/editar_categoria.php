<?php
include('is_logged.php'); // Archivo que verifica si el usuario que intenta acceder a la URL está logueado

/* Validación del lado del servidor */
if (empty($_POST['mod_id'])) {
    $errors[] = "ID vacío";
} else if (empty($_POST['mod_nombre'])) {
    $errors[] = "Nombre vacío";
} else if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $_POST['mod_nombre'])) {
    $errors[] = "El nombre no puede contener números o símbolos.";
} else if (strlen($_POST['mod_nombre']) > 25) {
    $errors[] = "El nombre no puede exceder los 25 caracteres.";
} else if (!empty($_POST['mod_id']) && !empty($_POST['mod_nombre'])) {
    /* Conexión a la base de datos */
    require_once("../config/db.php"); // Variables de configuración para conectar a la base de datos
    require_once("../config/conexion.php"); // Función que conecta a la base de datos

    // Escapar caracteres peligrosos y quitar posibles códigos HTML/JavaScript
    $nombre = mysqli_real_escape_string($con, (strip_tags($_POST["mod_nombre"], ENT_QUOTES)));
    $descripcion = mysqli_real_escape_string($con, (strip_tags($_POST["mod_descripcion"], ENT_QUOTES)));
    $id_categoria = intval($_POST['mod_id']);

    // Verificar si ya existe otra categoría con el mismo nombre
    $sql_check = "SELECT * FROM categorias WHERE nombre_categoria = '$nombre' AND id_categoria != '$id_categoria'";
    $query_check = mysqli_query($con, $sql_check);

    if (mysqli_num_rows($query_check) > 0) {
        // Si ya existe otra categoría con el mismo nombre
        $errors[] = "Error! Categoría con ese nombre ya existe.";
    } else {
        // Actualizar la categoría si no hay conflictos de nombre
        $sql = "UPDATE categorias SET nombre_categoria = '$nombre', descripcion_categoria = '$descripcion' WHERE id_categoria = '$id_categoria'";
        $query_update = mysqli_query($con, $sql);

        if ($query_update) {
            $messages[] = "Categoría ha sido actualizada satisfactoriamente.";
        } else {
            $errors[] = "Lo siento, algo ha salido mal. Intenta nuevamente." . mysqli_error($con);
        }
    }
} else {
    $errors[] = "Error desconocido.";
}

// Mostrar mensajes de error o éxito
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
