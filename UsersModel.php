<?php
class UsersModel
{
    private $db;

    public function __construct()
    {
        // Establecer los valores predeterminados
        $dsn = 'mysql:host=localhost;dbname=fichajes_app';
        $username = 'root';
        $password = '1234';
        // Crear una instancia de PDO para conectarse a la base de datos
        $this->db = new PDO($dsn, $username, $password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getUsuarioByDni($dni, $password)
    {
        // Consulta SQL para obtener un usuario por su DNI y contraseña
        $query = "SELECT * FROM usuarios WHERE dni = :dni AND password = :password";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':dni' => $dni, ':password' => $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Utilizar PDO::FETCH_ASSOC para obtener un array asociativo
        return $user;
    }
    public function getUsersByIds($userIds)
    {
        $placeholders = rtrim(str_repeat('?,', count($userIds)), ',');
        $query = "SELECT * FROM usuarios WHERE id IN ($placeholders)";

        $stmt = $this->db->prepare($query);
        $stmt->execute($userIds);

        return $stmt->fetchAll();
    }
}
