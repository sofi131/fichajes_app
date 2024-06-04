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
        $stmt = $this->db->prepare("SELECT * FROM fichajes WHERE fecha = :date");
        $stmt->execute([':date' => $date]);
        $fichajes = $stmt->fetchAll();
        return $fichajes;
    }

    public function createFichaje($id,$tipo)
    {
        $stmt = $this->db->prepare("INSERT INTO `fichajes_app`.`fichajes` (`usuario_id`, `tipo`) VALUES (:iduser, :tipo);
        ");
        $stmt->execute([':iduser' => $id, ':tipo'=>$tipo]);
    }

    public function getFichajesByUser($user)
    {
        $stmt = $this->db->prepare("SELECT * FROM fichajes WHERE usuario_id = :user");
        $stmt->execute([':user' => $user]);
        $fichajes = $stmt->fetchAll();
        return $fichajes;
    }
}
?>
