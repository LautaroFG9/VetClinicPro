<?php
// =====================================================================
// actualizar_propietario.php
// Recibe por POST los datos del formulario de editar_propietario.php
// y actualiza el registro correspondiente en la tabla "propietarios".
//
// Misma sintaxis  (parecida al INSERT pero usando UPDATE):
//   UPDATE tabla SET campo='valor', ... WHERE id = 'valor'
// =====================================================================

include("../config/conexion.php"); // Conectamos a la base de datos ($conn)

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["xactualizar"])) {

    // Armamos la consulta de actualización con los datos que llegan del formulario
    $sql = "UPDATE propietarios SET
                nombre    = '" . $_POST['xnombre']   . "',
                apellido  = '" . $_POST['xapellido'] . "',
                email     = '" . $_POST['xemail']    . "',
                telefono  = '" . $_POST['xtelefono'] . "'
            WHERE id_propietario = '" . $_POST['xid'] . "'";

    mysqli_query($conn, $sql) or die(mysqli_error($conn)); // Ejecutamos la consulta

    mysqli_close($conn);

    // Volvemos al listado una vez actualizado el propietario
    header("Location: listado_propietarios.php?actualizado=ok");
    exit();

} else {
    // Si alguien entra directo a este archivo sin enviar el formulario, lo mandamos al listado
    header("Location: listado_propietarios.php");
    exit();
}
?>
