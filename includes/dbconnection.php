<?php
class dbconnection {
    private $connection;
    private $db_type;

    public function __construct($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name) {
        $this->db_type = $db_type;
        $this->connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name);
    }

    public function connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name) {
        switch ($db_type) {
            case 'MySQLi':
                if ($db_port) {
                    $db_host .= ":" . $db_port;
                }
                $this->connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
                if ($this->connection->connect_error) {
                    die("Connection Failed: " . $this->connection->connect_error);
                } else {
                    echo "Connected Successfully";
                }
                break;
            case 'PDO':
                if ($db_port) {
                    $db_host .= ":" . $db_port;
                }
                try {
                    $this->connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
                    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    echo "Connected successfully :-)";
                } catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
                break;
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>
