<?php 
function get_row($table,$row, $id, $equal){
	global $con;
	$query=mysqli_query($con,"select $row from $table where $id='$equal'");
	$rw=mysqli_fetch_array($query);
	$value=$rw[$row];
	return $value;
}
function guardar_historial($id_producto, $user_id, $fecha, $nota, $reference, $quantity, $cambios = null) {
    global $con;

    // Escapar el texto de $cambios para evitar errores de sintaxis
    $cambios = mysqli_real_escape_string($con, $cambios);

    $sql = "INSERT INTO historial (id_historial, id_producto, user_id, fecha, nota, referencia, cantidad, cambios)
            VALUES (NULL, '$id_producto', '$user_id', '$fecha', '$nota', '$reference', '$quantity', '$cambios')";

    mysqli_query($con, $sql);
    
    if (mysqli_error($con)) {
        echo "Error en la consulta SQL: " . mysqli_error($con);
    }
}
function agregar_stock($id_producto, $quantity, $reference, $user_id) {
    global $con;
    $update = mysqli_query($con, "UPDATE products SET stock = stock + '$quantity' WHERE id_producto = '$id_producto'");
    if ($update) {
        // Registrar en el historial
        $fecha = date("Y-m-d H:i:s");
        $nota = "Agregado $quantity al inventario";
        guardar_historial($id_producto, $user_id, $fecha, $nota, $reference, $quantity, "Stock aumentado en $quantity");
        return 1;
    } else {
        return 0;
    }
}
function eliminar_stock($id_producto, $quantity, $reference, $user_id) {
    global $con;
    
    // Verificar el stock actual
    $query = mysqli_query($con, "SELECT stock FROM products WHERE id_producto = '$id_producto'");
    $row = mysqli_fetch_array($query);
    $stock_actual = $row['stock'];

    if ($quantity > $stock_actual) {
        // Si intenta eliminar más de lo disponible, retornar un error específico
        return -1;
    }

    $update = mysqli_query($con, "UPDATE products SET stock = stock - '$quantity' WHERE id_producto = '$id_producto'");
    if ($update) {
        // Registrar en el historial
        $fecha = date("Y-m-d H:i:s");
        $nota = "Eliminado $quantity del inventario";
        guardar_historial($id_producto, $user_id, $fecha, $nota, $reference, $quantity, "Stock reducido en $quantity");
        return 1;
    } else {
        return 0;
    }
}

?>