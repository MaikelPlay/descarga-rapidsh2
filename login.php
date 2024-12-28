<?php
session_start();
$conexion = new mysqli('localhost', 'root', '', 'rapidsh2');

// Verifica conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Procesa el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre =   $_POST['nombre'];
    $password = $_POST['password'];

    // Validación para evitar caracteres especiales
    if (!ctype_alnum($nombre) || !ctype_alnum($password)) {
        $error = "Ni Nombre ni Password pueden contener caracteres especiales.";
    } else {
        // Busca el usuario en la base de datos
        $sql = "SELECT * FROM usuario WHERE nombre = '$nombre'";
        $result = $conexion->query($sql);

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            if ($password === $usuario['password']) { // Comparación directa de contraseñas
                $_SESSION['usuario'] = $usuario;
                header('Location: descarga.php');
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column; 
            align-items: center;    
            justify-content: center;
            min-height: 100vh;
        }

        .login-container {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
            margin-bottom: 20px; 
        }

        .login-container img {
            width: 200px;
            height: auto;
            margin-bottom: 20px;
        }

        .login-container input {
            display: block;
            width: 93%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }

        .texto {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        .texto h3 {
            margin-bottom: 10px;
        }

        .texto li {
            text-align: left; 
            margin: 10px 0;
        }

        .texto li:last-child {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    
    <div class="login-container">
        <img src="imgrapid.png" alt="Logo">
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Iniciar sesión</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>

    
    <div class="texto">
        <h3>Notas adicionales:</h3>
        <ul>
            <li>Es obligatorio la identificación en nuestro servicio de descarga.</li>
            <li>Ni Nombre ni Password pueden contener caracteres especiales.</li>
            <li>Sólo podrá hacer uso de este servicio de descarga una vez al día.</li>
        </ul>
    </div>
</body>
</html>
