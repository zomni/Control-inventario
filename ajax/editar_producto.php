<?php
    include('is_logged.php');
    include("../funciones.php"); // Incluye funciones.php para usar get_row y otras funciones

    if (empty($_POST['mod_id'])) {
        $errors[] = "ID vacío";
    } else if (empty($_POST['mod_codigo'])) {
        $errors[] = "Código vacío";
    } else if (empty($_POST['mod_nombre'])) {
        $errors[] = "Nombre del producto vacío";
    } else if ($_POST['mod_categoria'] == "") {
        $errors[] = "Selecciona la categoría del producto";
    } else if (empty($_POST['mod_precio'])) {
        $errors[] = "Precio de venta vacío";
    } else if (
        !empty($_POST['mod_id']) &&
        !empty($_POST['mod_codigo']) &&
        !empty($_POST['mod_nombre']) &&
        $_POST['mod_categoria'] != "" &&
        !empty($_POST['mod_precio'])
    ) {
        require_once("../config/db.php");
        require_once("../config/conexion.php");

        $id_producto = $_POST['mod_id'];
        $codigo = mysqli_real_escape_string($con, (strip_tags($_POST["mod_codigo"], ENT_QUOTES)));
        $nombre = mysqli_real_escape_string($con, (strip_tags($_POST["mod_nombre"], ENT_QUOTES)));
        $categoria = intval($_POST['mod_categoria']);
        $stock = intval($_POST['mod_stock']);
        $precio_venta = floatval($_POST['mod_precio']);

        // Obtener los datos actuales para comparar
        $query = mysqli_query($con, "SELECT * FROM products WHERE id_producto='$id_producto'");
        $producto_actual = mysqli_fetch_array($query);

        // Comparar los cambios y almacenar en el array $cambios
        $cambios = [];
        if ($producto_actual['codigo_producto'] != $codigo) {
            $cambios[] = "Código cambiado de {$producto_actual['codigo_producto']} a $codigo";
        }
        if ($producto_actual['nombre_producto'] != $nombre) {
            $cambios[] = "Nombre cambiado de '{$producto_actual['nombre_producto']}' a '$nombre'";
        }
        if ($producto_actual['id_categoria'] != $categoria) {
            // Obtener el nombre de las categorías para hacer la comparación más clara
            $categoria_anterior = get_row("categorias", "nombre_categoria", "id_categoria", $producto_actual['id_categoria']);
            $categoria_nueva = get_row("categorias", "nombre_categoria", "id_categoria", $categoria);
            $cambios[] = "Categoría cambiada de '$categoria_anterior' a '$categoria_nueva'";
        }
        if ($producto_actual['precio_producto'] != $precio_venta) {
            $cambios[] = "Precio cambiado de {$producto_actual['precio_producto']} a $precio_venta";
        }

        // Convertir el array de cambios en un string para el campo cambios en el historial y escaparlo
        $cambios_texto = implode(", ", $cambios);
        $cambios_texto = mysqli_real_escape_string($con, $cambios_texto);

        // Actualizar el producto en la base de datos
        $sql = "UPDATE products SET codigo_producto='$codigo', nombre_producto='$nombre', id_categoria='$categoria', precio_producto='$precio_venta', stock='$stock' WHERE id_producto='$id_producto'";
        $query_update = mysqli_query($con, $sql);

        if ($query_update) {
            $messages[] = "Producto ha sido actualizado satisfactoriamente.";

            // Guardar en el historial si hay cambios
            if (!empty($cambios_texto)) {
                $user_id = $_SESSION['user_id'];
                $fecha = date("Y-m-d H:i:s");
                $reference = $codigo; // Código como referencia del producto
                $quantity = $stock; // Stock actual

                // Llamada a guardar_historial con los detalles del cambio
                guardar_historial($id_producto, $user_id, $fecha, "Edición de producto", $reference, $quantity, $cambios_texto);
            }
        } else {
            $errors[] = "Lo siento, algo ha salido mal. Intenta nuevamente." . mysqli_error($con);
        }
    } else {
        $errors[] = "Error desconocido.";
    }

    if (isset($errors)) {
        echo "<div class='alert alert-danger' role='alert'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                <strong>Error!</strong>";
        foreach ($errors as $error) {
            echo $error;
        }
        echo "</div>";
    }

    if (isset($messages)) {
        echo "<div class='alert alert-success' role='alert'>
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                <strong>¡Bien hecho! </strong>";
        foreach ($messages as $message) {
            echo $message;
        }
        echo "</div>";
    }
?>
