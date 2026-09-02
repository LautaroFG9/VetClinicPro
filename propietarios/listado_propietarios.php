<?php
// =====================================================================
// listado_propietarios.php
// Muestra todos los propietarios cargados en la base de datos, junto
// con la cantidad de mascotas que tiene registradas cada uno.
// Cada fila tiene un botón "Ver/Editar" que envía el id del propietario
// por la URL (variable $_GET)
// "Ver y actualizar datos de la tabla".
// =====================================================================

include("../config/conexion.php"); // Conexión a la base de datos (usa $conn)

// Consulta que trae TODOS los propietarios + cantidad de mascotas de cada uno
$sql = "SELECT
            p.*,
            COUNT(m.id_mascota) AS cantidad_mascotas
        FROM propietarios p
        LEFT JOIN mascotas m ON m.id_propietario = p.id_propietario
        GROUP BY p.id_propietario
        ORDER BY p.apellido, p.nombre";

$resultado = mysqli_query($conn, $sql); // Ejecutamos la consulta

// Colores para los avatares (van rotando según la fila)
$colores_avatar = ['#1a1f5e', '#4992b5', '#f59e0b', '#16a34a', '#db2777', '#7c3aed'];
$i = 0;
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
        :root {
            --azul-oscuro: #1a1f5e;
            --azul-medio: #4992b5;
            --azul-claro: #7cb3fc;
            --azul-claro-bg: #e8f1ff;
            --texto-gris: #6b7280;
            --borde: #e5e7eb;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(180deg, #eef4ff 0%, #f4f7fb 260px);
        }

        .contenedor-listado {
            max-width: 1150px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* ── Encabezado con degradé ── */
        .banner-listado {
            background: linear-gradient(135deg, var(--azul-oscuro), var(--azul-medio));
            border-radius: 20px;
            padding: 28px 32px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 26px;
            box-shadow: 0 10px 25px rgba(26, 31, 94, .18);
        }
        .banner-listado h2 {
            font-weight: 800;
            margin: 0 0 4px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .banner-listado p {
            margin: 0;
            opacity: .85;
            font-size: 14px;
        }
        .banner-listado .emoji-banner {
            font-size: 26px;
        }

        .btn-nuevo {
            background: #fff;
            color: var(--azul-oscuro);
            border: none;
            font-weight: 700;
            padding: 10px 18px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .btn-nuevo:hover {
            color: var(--azul-oscuro);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0,0,0,.15);
        }

        /* ── Tarjeta contenedora de la tabla ── */
        .tarjeta-tabla {
            background: #fff;
            border-radius: 18px;
            border: 1px solid var(--borde);
            padding: 8px 20px;
            box-shadow: 0 6px 18px rgba(26, 31, 94, .06);
        }

        table.tabla-propietarios th {
            color: var(--texto-gris);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 2px solid var(--borde);
            padding-top: 16px;
        }
        table.tabla-propietarios td {
            vertical-align: middle;
        }
        table.tabla-propietarios tbody tr {
            transition: background .15s ease;
        }
        table.tabla-propietarios tbody tr:hover {
            background: var(--azul-claro-bg);
        }

        /* ── Avatar con inicial ── */
        .avatar-propietario {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            flex-shrink: 0;
        }
        .fila-propietario {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nombre-propietario {
            font-weight: 700;
            color: var(--azul-oscuro);
        }

        /* ── Badge de cantidad de mascotas ── */
        .badge-mascotas {
            background: #fff3d6;
            color: #92600a;
            border: 1px solid #f5d68a;
            border-radius: 30px;
            padding: 5px 12px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .badge-mascotas.sin-mascotas {
            background: #f3f4f6;
            color: var(--texto-gris);
            border-color: var(--borde);
        }

        .btn-ver {
            background: var(--azul-oscuro);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-ver:hover {
            background: var(--azul-medio);
            color: #fff;
        }

        .btn-volver {
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="contenedor-listado">

    <div class="banner-listado">
        <div>
            <h2><span class="emoji-banner">🧑‍🤝‍🧑</span> Propietarios</h2>
            <p>Listado completo de dueños registrados en VetClinic Pro</p>
        </div>
        <a href="alta_propietario.php" class="btn-nuevo">
            <i class="bi bi-person-plus-fill"></i> Nuevo propietario
        </a>
    </div>

    <div class="tarjeta-tabla">
        <table class="table tabla-propietarios">
            <thead>
                <tr>
                    <th>Propietario</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Mascotas</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($resultado && mysqli_num_rows($resultado) > 0) {

                while ($propietario = mysqli_fetch_object($resultado)) {

                    $color = $colores_avatar[$i % count($colores_avatar)];
                    $inicial = strtoupper(mb_substr($propietario->nombre, 0, 1));
                    $cantidad = (int) $propietario->cantidad_mascotas;
                    $i++;
            ?>
                <tr>
                    <td>
                        <div class="fila-propietario">
                            <div class="avatar-propietario" style="background: <?php echo $color; ?>;">
                                <?php echo htmlspecialchars($inicial); ?>
                            </div>
                            <div class="nombre-propietario">
                                <?php echo htmlspecialchars($propietario->apellido . ', ' . $propietario->nombre); ?>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($propietario->email); ?></td>
                    <td><?php echo htmlspecialchars($propietario->telefono); ?></td>
                    <td>
                        <?php if ($cantidad > 0): ?>
                            <span class="badge-mascotas">
                                🐾 <?php echo $cantidad; ?> mascota<?php echo $cantidad !== 1 ? 's' : ''; ?>
                            </span>
                        <?php else: ?>
                            <span class="badge-mascotas sin-mascotas">
                                🐾 Sin mascotas
                            </span>
                        <?php endif; ?>
                    </td>
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
                    <td colspan="5" class="text-center text-muted py-4">
                        🐾 No hay propietarios cargados todavía.
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <a href="../veterinarios/inicio_veterinario.php" class="btn btn-outline-secondary btn-volver">
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
