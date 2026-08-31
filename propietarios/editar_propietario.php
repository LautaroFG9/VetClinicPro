<?php
// =====================================================================
// editar_propietario.php
// Muestra el formulario con los datos de UN propietario (según el id
// que llega por la URL) para poder verlos y/o modificarlos.
//
//  "Ver y actualizar datos de la tabla":
//   1. Conexión a la base
//   2. Variable que guarda el id proveniente del URL ($_GET)
//   3. Consulta que selecciona la información correspondiente a ese id
//   4. mysqli para guardar los datos y mostrarlos
//   5. Formulario con "value" precargado con el dato de cada campo
//   6. El formulario apunta (action) al script que hace el UPDATE
// =====================================================================

include("../config/conexion.php"); // 1. Conexión a la base de datos ($conn)

// 2. Capturamos el id que viene por la URL, ej: editar_propietario.php?id=3
$idp = $_GET['id'];

// 3. Consulta que trae únicamente el propietario cuyo id coincide
$sql = "SELECT * FROM propietarios WHERE id_propietario = '" . $idp . "'";
$resultado = mysqli_query($conn, $sql);

// 4. Guardamos toda la info del propietario en un objeto
$propietario = mysqli_fetch_object($resultado);

// Si no existe ningún propietario con ese id, volvemos al listado
if (!$propietario) {
    header("Location: listado_propietarios.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Propietario · VetClinic Pro</title>
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
        .contenedor-form {
            max-width: 650px;
            margin: 50px auto;
            padding: 0 20px;
        }
        .tarjeta-form {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 35px;
            box-shadow: 0 4px 14px rgba(0,0,0,.04);
        }
        .tarjeta-form h3 {
            color: #1a1f5e;
            font-weight: 800;
            margin-bottom: 25px;
            text-align: center;
        }
        .btn-guardar {
            background: #1a1f5e;
            color: #fff;
            border: none;
        }
        .btn-guardar:hover {
            background: #4992b5;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="contenedor-form">
    <div class="tarjeta-form">
        <h3><i class="bi bi-person-lines-fill"></i> Datos del propietario</h3>

        <!-- 6. El formulario envía los datos (POST) al script que actualiza -->
        <form method="post" action="actualizar_propietario.php">

            <!-- Campo oculto con el id del propietario, se manda junto al resto -->
            <input type="hidden" name="xid" value="<?php echo $propietario->id_propietario; ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="xnombre"
                       value="<?php echo htmlspecialchars($propietario->nombre); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" class="form-control" name="xapellido"
                       value="<?php echo htmlspecialchars($propietario->apellido); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="xemail"
                       value="<?php echo htmlspecialchars($propietario->email); ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="xtelefono"
                       value="<?php echo htmlspecialchars($propietario->telefono); ?>">
            </div>

            <div class="d-flex justify-content-between">
                <a href="listado_propietarios.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al listado
                </a>
                <button type="submit" name="xactualizar" class="btn btn-guardar">
                    <i class="bi bi-save"></i> Guardar cambios
                </button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
<?php
mysqli_close($conn);
?>
