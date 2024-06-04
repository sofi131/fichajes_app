<?php
class FichajesModel
{
    private $db;
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=fichajes_app', 'root', '1234');
    }
    public function getFichajesByDate($date)
    {
        $stmt = $this->db->prepare("SELECT * FROM fichajes WHERE fecha = :date");
        $stmt->execute([':date' => $date]);
        $fichajes = $stmt->fetchAll();
        return $fichajes;
    }
    public function createFichaje($dni, $fecha, $hora)
    {
        $stmt = $this->db->prepare("INSERT INTO fichajes (dni, fecha, hora) VALUES (:dni, :fecha, :hora)");
        $stmt->execute([':dni' => $dni, ':fecha' => $fecha, ':hora' => $hora]);
    }
}
