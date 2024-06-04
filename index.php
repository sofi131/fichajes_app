<?php
session_start();
require 'conexion.php';
require_once 'LoginController.php';
require_once 'UsersModel.php';
require_once 'FichajesModel.php';

if (isset($_POST['submit'])) {
    $dni = $_POST['dni'];
    $password = $_POST['password'];

    // Crear una instancia de UsersModel
    $usersModel = new UsersModel(); // Pasar solo la conexión
    
    // Llamar al método getUsuarioByDni() en la instancia creada
    $user = $usersModel->getUsuarioByDni($dni, $password);

    if ($user) {
        // Si el usuario existe, iniciar sesión y mostrar su nombre
        $_SESSION['user'] = $user['nombre'];
        session_regenerate_id(); // Regenerar ID de sesión al iniciar sesión
    } else {
        // Si el usuario no existe, mostrar un mensaje de error
        $login_error = "El DNI o la contraseña es incorrecto";
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichajes</title>
    <!-- Enlace al archivo CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Fichajes</h1>
        <div class="row">
            <div class="col-md-6">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="form-group">
                        <label for="dni">DNI:</label>
                        <input type="text" class="form-control" id="dni" name="dni" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Validar</button>
                </form>
                <?php if (isset($login_error)) { ?>
                    <p class="text-danger"><?php echo $login_error; ?></p>
                <?php } ?>
            </div>
        </div>
        <?php if (isset($_SESSION['user'])) { ?>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>
                    <button id="logout" class="btn btn-danger"><a href="logout">Cerrar sesión</a></button>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Hora de fichaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fichajesModel = new FichajesModel(); // Solo pasamos la conexión
                            $fichajes = $fichajesModel->getFichajesByUser($_SESSION['user']);
                            foreach ($fichajes as $fichaje) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fichaje['name']); ?></td>
                                    <td><?php echo date('H:i', $fichaje['fichada_at']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Enlace al archivo JS de Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
