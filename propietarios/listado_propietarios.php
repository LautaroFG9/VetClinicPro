<?php
// =====================================================================
// listado_propietarios.php
// Muestra todos los propietarios cargados en la base de datos.
// Cada fila tiene un botón "Ver/Editar" que envía el id del propietario
// por la URL (variable $_GET)
// "Ver y actualizar datos de la tabla".
// =====================================================================

include("../config/conexion.php"); // Conexión a la base de datos (usa $conn)

// Consulta que trae TODOS los propietarios de la tabla
$sql = "SELECT * FROM propietarios ORDER BY apellido, nombre";
$resultado = mysqli_query($conn, $sql); // Ejecutamos la consulta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Propietarios · VetClinic Pro</title>
    <link rel="icon" type="image/png" href="../img/VetClinic Pro.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../estilos/abm.css" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fb;
        }
        .contenedor-listado {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .encabezado-listado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .encabezado-listado h2 {
            color: #1a1f5e;
            font-weight: 800;
            margin: 0;
        }
        .tarjeta-tabla {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 10px 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,.04);
        }
        table.tabla-propietarios th {
            color: #6b7280;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }
        table.tabla-propietarios td {
            vertical-align: middle;
        }
        .btn-ver {
            background: #1a1f5e;
            color: #fff;
            border: none;
        }
        .btn-ver:hover {
            background: #4992b5;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="contenedor-listado">

    <div class="encabezado-listado">
        <h2><i class="bi bi-people-fill"></i> Propietarios</h2>
        <a href="alta_propietario.php" class="btn btn-ver">
            <i class="bi bi-person-plus"></i> Nuevo propietario
        </a>
    </div>

    <div class="tarjeta-tabla">
        <table class="table table-hover tabla-propietarios">
            <thead>
                <tr>
                    <th>Propietario</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Recorremos todos los registros obtenidos:
            // while ($resultado = mysqli_fetch_object($rsmenu)) { ... }
            if ($resultado && mysqli_num_rows($resultado) > 0) {

                while ($propietario = mysqli_fetch_object($resultado)) {
            ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($propietario->apellido . ', ' . $propietario->nombre); ?>
                    </td>
                    <td><?php echo htmlspecialchars($propietario->email); ?></td>
                    <td><?php echo htmlspecialchars($propietario->telefono); ?></td>
                    <td class="text-end">
                        <!--
                            Botón "Ver/Editar": manda el id del propietario por la URL
                            usando $_GET, exactamente como en el PDF:
                            <a href="editar_propietario.php?id=<?= $propietario->id_propietario ?>">
                        -->
                        <a href="editar_propietario.php?id=<?php echo $propietario->id_propietario; ?>"
                           class="btn btn-sm btn-ver">
                            <i class="bi bi-eye"></i> Ver / Editar
                        </a>
                    </td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        No hay propietarios cargados todavía.
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <a href="inicio_propietarios.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_GET["actualizado"]) && $_GET["actualizado"] == "ok") { ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Datos actualizados!',
    text: 'Los datos del propietario se guardaron correctamente.',
    confirmButtonColor: '#1a1f5e'
});
</script>
<?php } ?>

</body>
</html>
<?php
mysqli_close($conn); // Cerramos la conexión al final del script
?>
