<?php
class FichajesModel
{
    private $db;

    public function __construct()
    {
        // Establecer los valores predeterminados
        $dsn = 'mysql:host=localhost;dbname=fichajes_app';
        $username = 'root';
        $password = '1234';
        $this->db = new PDO($dsn, $username, $password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getFichajesByDate($date)
    {
        $stmt = $this->db->prepare("SELECT * FROM fichajes WHERE DATE(fecha) = :date");
        $stmt->execute([':date' => $date]);
        $fichajes = $stmt->fetchAll();
        return $fichajes;
    }

    public function createFichaje($id, $tipo)
    {
        $stmt = $this->db->prepare("INSERT INTO `fichajes_app`.`fichajes` (`usuario_id`, `tipo`) VALUES (:iduser, :tipo);");
        $stmt->execute([':iduser' => $id, ':tipo' => $tipo]);
    }

    public function getFichajesByUser($user)
    {
        $stmt = $this->db->prepare("SELECT * FROM fichajes WHERE usuario_id = :user");
        $stmt->execute([':user' => $user]);
        $fichajes = $stmt->fetchAll();
        return $fichajes;
    }

    // Para registrar el fichaje
    public function registrarFichaje($id, $tipo)
    {
        // Obtener la fecha y hora actual
        $fecha_hora_actual = date('Y-m-d H:i:s');

        // Preparar la consulta para insertar el fichaje
        $stmt = $this->db->prepare("INSERT INTO fichajes (usuario_id, fecha, tipo) VALUES (:id, :fecha, :tipo)");

        // Ejecutar la consulta con los valores proporcionados
        $stmt->execute([':id' => $id, ':fecha' => $fecha_hora_actual, ':tipo' => $tipo]);
    }
    //para obtener los útlimos 5 fichajes del día
    public function getLastFichajesOfToday($limit)
    {
        $fecha_actual = date('Y-m-d');
        $stmt = $this->db->prepare("SELECT * FROM fichajes WHERE DATE(fecha) = :fecha ORDER BY fecha DESC LIMIT :limit");
        $stmt->bindValue(':fecha', $fecha_actual, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $fichajes = $stmt->fetchAll();
        return $fichajes;
    }

    public function getFichajeHoy($usuario_id) {
        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');

        // Consultar la base de datos para obtener el fichaje del usuario para la fecha actual
        $query = "SELECT * FROM fichajes WHERE usuario_id = :usuario_id AND DATE(fecha) = :fecha_actual";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':usuario_id', $usuario_id);
        $statement->bindParam(':fecha_actual', $fecha_actual);
        $statement->execute();
        $fichaje = $statement->fetch(PDO::FETCH_ASSOC);

        return $fichaje;
    }
    
}
?>
