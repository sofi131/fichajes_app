<?php session_start();
require 'conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichajes</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>Fichajes</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required><br><br>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" name="submit" value="Validar">
        </form>
        <?php if (isset($_SESSION['user'])) { ?>
            <h2>Bienvenido, <?php echo $_SESSION['user']; ?></h2>
            <button id="logout">Cerrar sesión</button>
        <?php } else { ?>
            <h2>Iniciar sesión</h2>
        <?php } ?>
    </div>
    <script src="script.js"></script>
    <?php if (isset($_POST['submit'])) {
        $dni = $_POST['dni'];
        $password = $_POST['password'];
        if (isset($_SESSION['user'])) { // If the user is already logged in, show a message
            echo "<h2>Ya estás logueado!</h2>";
        } else {
            // Check if the user exists in the database
            $user = UsersModel::getUsuarioByDni($dni, $password);
            if ($user) {
                // If the user exists, log them in and show their name
                $_SESSION['user'] = $user['name'];
                echo "<h2>Bienvenido, " . $_SESSION['user'] . "</h2>";
            } else {
                // If the user doesn't exist, show an error message
                echo "<h2>El dni o contraseña es incorrecto</h2>";
            }
        }
    } ?>
</body>

</html>