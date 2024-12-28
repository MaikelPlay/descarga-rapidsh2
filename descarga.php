<?php
session_start();
$conexion = new mysqli('localhost', 'root', '', 'rapidsh2');

// Verifica conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verifica si está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$id_usuario = $usuario['id'];

// Consulta última descarga
$sql = "SELECT ultima_descarga FROM usuario WHERE id = $id_usuario";
$result = $conexion->query($sql);
$row = $result->fetch_assoc();

$puede_descargar = false;

if ($row['ultima_descarga'] === null) {
    $puede_descargar = true;
} else {
    $ultima_descarga = strtotime($row['ultima_descarga']);
    $ahora = time();

    if (($ahora - $ultima_descarga) >= 86400) { // 24 horas en segundos
        $puede_descargar = true;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Descarga</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .container p {
            font-size: 16px;
            color: #333;
            margin: 15px 0;
        }

        .success {
            color: #28a745;
            font-weight: bold;
        }

        .error {
            color: #dc3545;
            font-weight: bold;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Estado de la Descarga</h1>
        <?php if ($puede_descargar): ?>
            <?php
            // Actualiza última descarga
            $sql = "UPDATE usuario SET ultima_descarga = NOW() WHERE id = $id_usuario";
            $conexion->query($sql);
            ?>
            <p class="success">Recurso descargado. Podrás descargar nuevamente en 24 horas.</p>
            <button onclick="window.location.href='login.php'">Regresar</button>
        <?php else: ?>
            <p class="error">No puedes descargar todavía. Intenta después de 24 horas.</p>
            <button onclick="window.location.href='login.php'">Regresar</button>
        <?php endif; ?>
    </div>
</body>
</html>
