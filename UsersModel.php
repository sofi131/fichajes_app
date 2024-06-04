<?php
class UsersModel extends PDO
{
    private $db;
    public function __construct($dsn, $username, $password)
    {
        $this->db = new PDO($dsn, $username, $password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function getUsuarioByDni($dni, $password)
    {
        $query = "SELECT * FROM users WHERE dni = :dni AND password = :password";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':dni' => $dni, ':password' => password_hash($password, PASSWORD_DEFAULT)]);
        $user = $stmt->fetch();
        return $user;
    }
}
