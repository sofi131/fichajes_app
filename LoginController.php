<?php
class LoginController
{
    private $model;
    public function __construct()
    {
        $this->model = new Usuario();
    }
    public function login()
    {
        if (isset($_POST['dni']) && isset($_POST['password'])) {
            $dni = $_POST['dni'];
            $password = $_POST['password'];
            $usuario = $this->model->getUsuarioByDni($dni);
            if ($usuario && password_verify($password, $usuario->getPassword())) {
                $_SESSION['logueado'] = true;
                $_SESSION['nombre'] = $usuario->getNombre();
                echo "<script>alert('Ficha realizado con éxito');</script>";
            } else {
                echo "<script>alert('Error al loguearse');</script>";
            }
        }
    }
    public function logout()
    {
        session_destroy();
    }
    public function getFichajes()
    {
        $fichajes = $this->model->getFichajesByDate(date("Y-m-d"));
        return $fichajes;
    }
}
class Usuario
{
    private $db;
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=fichajes_app', 'root', '1234');
    }
    public function getUsuarioByDni($dni)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE dni = :dni");
        $stmt->execute([':dni' => $dni]);
        $usuario = $stmt->fetch();
        return $usuario;
    }
    public function getPassword($dni)
    {
        $stmt = $this->db->prepare("SELECT password FROM usuarios WHERE dni = :dni");
        $stmt->execute([':dni' => $dni]);
        $password = $stmt->fetch()['password'];
        return $password;
    }
    public function getNombre($dni)
    {
        $stmt = $this->db->prepare("SELECT nombre FROM usuarios WHERE dni = :dni");
        $stmt->execute([':dni' => $dni]);
        $nombre = $stmt->fetch()['nombre'];
        return $nombre;
    }
    public function getFichajesByDate($date)
    {
        $stmt = $this->db->prepare("SELECT * FROM fichajes WHERE fecha = :date");
        $stmt->execute([':date' => $date]);
        $fichajes = $stmt->fetchAll();
        return $fichajes;
    }
}
