<?php
session_start();
require 'conexion.php';
require_once 'LoginController.php';
require_once 'UsersModel.php';
require_once 'FichajesModel.php';

// Iniciar sesión y verificar si se envió el formulario de inicio de sesión
if (isset($_POST['submit'])) {
    $dni = $_POST['dni'];
    $password = $_POST['password'];

    $usersModel = new UsersModel(); // Crear instancia de UsersModel
    $user = $usersModel->getUsuarioByDni($dni, $password); // Obtener usuario por DNI

    if ($user) {
        $_SESSION['usuario_id'] = $user['id']; // Guardar ID de usuario en sesión
        $_SESSION['user'] = $user['nombre']; // Guardar nombre de usuario en sesión
        session_regenerate_id(); // Regenerar ID de sesión
    } else {
        $login_error = "El DNI o la contraseña es incorrecto"; // Mostrar mensaje de error si el usuario no existe
    }
}

// Manejar acciones si el usuario está autenticado
if (isset($_SESSION['usuario_id']) && isset($_GET['accion'])) {
    $fichajesModel = new FichajesModel();

    // Verificar si el usuario ya tiene un registro para el día actual
    $registroHoy = $fichajesModel->getFichajeHoy($_SESSION['usuario_id']);

    if ($registroHoy) {
        $registro_error = "¡Ya te has registrado hoy!";
    } else {
        // Si no hay registro para hoy, registrar la entrada o salida según la acción
        $fichajesModel->registrarFichaje($_SESSION['usuario_id'], $_GET['accion']);
        header('Location: index.php'); // Redirigir para evitar múltiples envíos
        exit;
    }
}

// Obtener los últimos 5 fichajes del día actual
$fichajesModel = new FichajesModel();
$fichajes = $fichajesModel->getLastFichajesOfToday(5);

// Obtener los nombres de usuario correspondientes a los IDs de los últimos fichajes
$userIds = array_column($fichajes, 'usuario_id');
$usersModel = new UsersModel();
$usuarios = $usersModel->getUsersByIds($userIds);
$userIdToName = array_column($usuarios, 'nombre', 'id');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichajes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }

        .welcome-message {
            margin-bottom: 20px;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .table-container {
            margin-top: 20px;
        }

        /* Estilos para el encabezado fijo */
        .fixed-header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #fff;
            /* Color de fondo blanco */
            padding: 10px 0;
            /* Espaciado interno */
            z-index: 1000;
            /* Colocarlo por encima de otros elementos */
            border-bottom: 1px solid #dee2e6;
            /* Borde inferior */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Sombra */
            margin-bottom: 20px;
            /* Margen inferior añadido */
        }

        /* Estilos para el contenedor principal */
        .main-container {
            margin-top: 90px;
            /* Ajuste de margen para evitar que el encabezado fijo oculte contenido */
            border: 1px solid #ced4da;
            /* Borde alrededor del contenedor principal */
            border-radius: 10px;
            /* Radio de borde para esquinas redondeadas */
            padding: 20px;
            /* Espaciado interno */
            background-color: #f8f9fa;
            /* Color de fondo */
        }
    </style>
</head>

<body>
    <!-- Encabezado fijo -->
    <div class="fixed-header text-center">
        <h1 class="mb-0">App Fichajes</h1>
    </div>

    <!-- Contenedor principal -->
    <div class="container mt-8 main-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if (isset($_SESSION['user'])) { ?>
                    <div class="welcome-message text-center mt-4">
                        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>
                        <?php if (!isset($_GET['accion']) || ($_GET['accion'] != 'entrada' && $_GET['accion'] != 'salida')) { ?>
                            <div class="mt-3 text-center">
                                <a href="?accion=entrada" class="btn btn-primary mr-2">Entrada</a>
                                <a href="?accion=salida" class="btn btn-danger mr-2">Salida</a>
                                <a href="logout.php" class="btn btn-secondary">Cerrar sesión</a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 form-container mb-4">
                <?php if (isset($registro_error)) { ?>
                    <p class="text-danger mt-3 text-center"><?php echo $registro_error; ?></p>
                <?php } else { ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <div class="form-group mt-3">
                            <label for="dni">DNI:</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary btn-block">Validar</button>
                    </form>
                    <?php if (isset($login_error)) { ?>
                        <p class="text-danger mt-3 text-center"><?php echo $login_error; ?></p>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="row justify-content-center table-container">
            <div class="col-md-12 ">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fichajes as $fichaje) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($userIdToName[$fichaje['usuario_id']]); ?></td>
                                <td><?php echo htmlspecialchars($fichaje['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($fichaje['tipo']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (isset($_SESSION['user']) && isset($_GET['accion']) && ($registro_error || !$registroHoy)) { ?>
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <a href="logout.php" class="btn btn-secondary">Cerrar sesión</a>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

