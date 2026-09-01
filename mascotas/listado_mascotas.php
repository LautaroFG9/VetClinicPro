<?php
// =====================================================================
// listado_mascotas.php
// Muestra todas las mascotas cargadas en la base de datos, junto con
// los datos de su propietario (JOIN con las tablas propietarios,
// especies y razas).
// Pensado para que el veterinario vea el listado completo de pacientes.
// =====================================================================

include("../config/conexion.php"); // Conexión a la base de datos (usa $conn)

// Consulta que trae TODAS las mascotas junto a su propietario, especie y raza
$sql = "SELECT
            m.id_mascota,
            m.nombre,
            m.sexo,
            m.peso_kg,
            m.fecha_nacimiento,
            e.nombre_especie,
            r.nombre_raza,
            p.id_propietario,
            p.nombre    AS propietario_nombre,
            p.apellido  AS propietario_apellido,
            p.telefono  AS propietario_telefono
        FROM mascotas m
        LEFT JOIN especies    e ON m.id_especie    = e.id_especie
        LEFT JOIN razas       r ON m.id_raza       = r.id_raza
        LEFT JOIN propietarios p ON m.id_propietario = p.id_propietario
        ORDER BY p.apellido, p.nombre, m.nombre";

$resultado = mysqli_query($conn, $sql); // Ejecutamos la consulta

// ── Helper: emoji según la especie ──────────────────────────────────
function emojiEspecie(?string $esp): string {
    $mapa = [
        'perro'    => '🐕',
        'gato'     => '🐱',
        'conejo'   => '🐇',
        'pájaro'   => '🦜',
        'pajaro'   => '🦜',
        'hámster'  => '🐹',
        'hamster'  => '🐹',
        'tortuga'  => '🐢',
        'pez'      => '🐠',
        'iguana'   => '🦎',
        'caballo'  => '🐴',
    ];
    return $mapa[mb_strtolower($esp ?? '')] ?? '🐾';
}

// Colores de fondo del avatar, rotando por especie/fila
$colores_avatar = ['#e8f1ff', '#fff3d6', '#fde2e4', '#e6f6ea', '#eee5ff'];
$i = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mascotas · VetClinic Pro</title>
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
            background: linear-gradient(180deg, #fff8ec 0%, #f4f7fb 260px);
        }

        .contenedor-listado {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* ── Encabezado con degradé cálido ── */
        .banner-listado {
            background: linear-gradient(135deg, #0b2ef5, #27dbd2);
            border-radius: 20px;
            padding: 28px 32px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 26px;
            box-shadow: 0 10px 25px rgba(219, 39, 119, .18);
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
            opacity: .9;
            font-size: 14px;
        }
        .banner-listado .emoji-banner {
            font-size: 26px;
        }

        /* ── Tarjeta contenedora de la tabla ── */
        .tarjeta-tabla {
            background: #fff;
            border-radius: 18px;
            border: 1px solid var(--borde);
            padding: 8px 20px;
            box-shadow: 0 6px 18px rgba(219, 39, 119, .06);
        }

        table.tabla-mascotas th {
            color: var(--texto-gris);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 2px solid var(--borde);
            padding-top: 16px;
        }
        table.tabla-mascotas td {
            vertical-align: middle;
        }
        table.tabla-mascotas tbody tr {
            transition: background .15s ease;
        }
        table.tabla-mascotas tbody tr:hover {
            background: #fff8ec;
        }

        /* ── Avatar circular con emoji de especie ── */
        .avatar-mascota-fila {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .fila-mascota {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nombre-mascota-fila {
            font-weight: 700;
            color: var(--azul-oscuro);
        }
        .especie-raza-fila {
            font-size: 12px;
            color: var(--texto-gris);
        }

        /* ── Badge de sexo ── */
        .badge-sexo {
            border-radius: 30px;
            padding: 5px 12px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .badge-sexo.macho {
            background: #e0edff;
            color: #1d4ed8;
            border: 1px solid #bcd6ff;
        }
        .badge-sexo.hembra {
            background: #ffe4ef;
            color: #be185d;
            border: 1px solid #ffc2dd;
        }
        .badge-sexo.desconocido {
            background: #f3f4f6;
            color: var(--texto-gris);
            border: 1px solid var(--borde);
        }

        /* ── Chip de propietario ── */
        .propietario-fila {
            font-weight: 600;
            color: var(--texto-oscuro, #111827);
        }
        .telefono-fila {
            font-size: 13px;
            color: var(--texto-gris);
        }

        .btn-ver {
            background: #2751db;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-ver:hover {
            background: #1c26b9;
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
            <h2><span class="emoji-banner">🐾</span> Mascotas</h2>
            <p>Todas las mascotas registradas en VetClinic Pro</p>
        </div>
    </div>

    <div class="tarjeta-tabla">
        <table class="table tabla-mascotas">
            <thead>
                <tr>
                    <th>Mascota</th>
                    <th>Sexo</th>
                    <th>Propietario</th>
                    <th>Teléfono</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Recorremos todos los registros obtenidos:
            // while ($mascota = mysqli_fetch_object($resultado)) { ... }
            if ($resultado && mysqli_num_rows($resultado) > 0) {

                while ($mascota = mysqli_fetch_object($resultado)) {

                    $emoji     = emojiEspecie($mascota->nombre_especie);
                    $colorFondo = $colores_avatar[$i % count($colores_avatar)];
                    $i++;

                    $sexo = mb_strtolower($mascota->sexo ?? '');
                    if ($sexo === 'macho') {
                        $claseSexo = 'macho';
                        $iconoSexo = '♂';
                    } elseif ($sexo === 'hembra') {
                        $claseSexo = 'hembra';
                        $iconoSexo = '♀';
                    } else {
                        $claseSexo = 'desconocido';
                        $iconoSexo = '—';
                    }
            ?>
                <tr>
                    <td>
                        <div class="fila-mascota">
                            <div class="avatar-mascota-fila" style="background: <?php echo $colorFondo; ?>;">
                                <?php echo $emoji; ?>
                            </div>
                            <div>
                                <div class="nombre-mascota-fila"><?php echo htmlspecialchars($mascota->nombre); ?></div>
                                <div class="especie-raza-fila">
                                    <?php
                                    echo htmlspecialchars($mascota->nombre_especie ?? '—');
                                    if ($mascota->nombre_raza) {
                                        echo ' · ' . htmlspecialchars($mascota->nombre_raza);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-sexo <?php echo $claseSexo; ?>">
                            <?php echo $iconoSexo; ?> <?php echo htmlspecialchars($mascota->sexo ?? 'Sin datos'); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($mascota->id_propietario): ?>
                            <div class="propietario-fila">
                                🧑‍🤝‍🧑 <?php echo htmlspecialchars($mascota->propietario_apellido . ', ' . $mascota->propietario_nombre); ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Sin propietario</span>
                        <?php endif; ?>
                    </td>
                    <td class="telefono-fila"><?php echo htmlspecialchars($mascota->propietario_telefono ?? '—'); ?></td>
                    <td class="text-end">
                        <!--
                            Botón "Ver/Editar": manda el id de la mascota por la URL
                            usando $_GET, igual que en editar_mascota.php:
                            <a href="editar_mascota.php?id=<?= $mascota->id_mascota ?>">
                        -->
                        <a href="editar_mascota.php?id=<?php echo $mascota->id_mascota; ?>"
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
                        🐾 No hay mascotas cargadas todavía.
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

</body>
</html>
<?php
mysqli_close($conn); // Cerramos la conexión al final del script
?>
