<?php 
include('is_logged.php'); // Archivo que verifica si el usuario que intenta acceder a la URL está logueado

/* Validación del lado del servidor */
if (empty($_POST['nombre'])) {
    $errors[] = "Nombre vacío";
} else if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $_POST['nombre'])) {
    $errors[] = "El nombre no puede contener números o símbolos.";
} else if (strlen($_POST['nombre']) > 25) {
    $errors[] = "El nombre no puede exceder los 25 caracteres.";
} else if (!empty($_POST['nombre'])) {
    /* Conexión a la base de datos */
    require_once("../config/db.php"); // Variables de configuración para conectar a la base de datos
    require_once("../config/conexion.php"); // Función que conecta a la base de datos

    // Escapar caracteres peligrosos y quitar posibles códigos HTML/JavaScript
    $nombre = mysqli_real_escape_string($con, (strip_tags($_POST["nombre"], ENT_QUOTES)));
    $descripcion = mysqli_real_escape_string($con, (strip_tags($_POST["descripcion"], ENT_QUOTES)));
    $date_added = date("Y-m-d H:i:s");

    // Verificar si ya existe una categoría con el mismo nombre
    $sql_check = "SELECT * FROM categorias WHERE nombre_categoria = '$nombre'";
    $query_check = mysqli_query($con, $sql_check);
    
    if (mysqli_num_rows($query_check) > 0) {
        // Si ya existe una categoría con el mismo nombre
        $errors[] = "Categoría ya creada";
    } else {
        // Insertar nueva categoría si no existe
        $sql = "INSERT INTO categorias (nombre_categoria, descripcion_categoria, date_added) VALUES ('$nombre', '$descripcion', '$date_added')";
        $query_new_insert = mysqli_query($con, $sql);

        if ($query_new_insert) {
            $messages[] = "Categoría ha sido ingresada satisfactoriamente.";
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
