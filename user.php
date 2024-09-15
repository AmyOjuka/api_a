<?php
require_once 'includes/dbConnection.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new dbconnection('MySQLi', 'localhost', '3306', 'root', '', 'ics_e');
    }

    public function insertUser($fullname, $email, $username, $password, $genderId, $roleId) {
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert query
        $query = "INSERT INTO users (fullname, email, username, password, genderId, roleId) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("ssssii", $fullname, $email, $username, $passwordHash, $genderId, $roleId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getUsers() {
        $query = "SELECT users.userId, users.fullname, users.email, gender.gender, roles.role 
                  FROM users
                  JOIN gender ON users.genderId = gender.genderId
                  JOIN roles ON users.roleId = roles.roleId";
        $result = $this->db->getConnection()->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
