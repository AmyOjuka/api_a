<?php
require_once 'includes/dbConnection.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new dbconnection('MySQLi', 'localhost', '', 'root', '', 'ics_e');
    }

    public function getGenders() {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("SELECT genderId, gender FROM gender");
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set
        $genders = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
        return $genders;
    }

    public function getRoles() {
        $conn = $this->db->getConnection();

        $stmt = $conn->prepare("SELECT roleId, role FROM roles");
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set
        $roles = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
        return $roles;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture the form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
    $genderId = $_POST['genderId'];
    $roleId = $_POST['roleId'];

    // Validate inputs 
    if (!empty($fullname) && !empty($email) && !empty($username) && !empty($password) && !empty($genderId) && !empty($roleId)) {
        
        // Initialize the database connection
        $db = new dbconnection('MySQLi', 'localhost', '', 'root', '', 'ics_e');
        $conn = $db->getConnection();

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password, genderId, roleId) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error)); // Debugging 
        }

        // Bind the parameters
        $stmt->bind_param('ssssii', $fullname, $email, $username, $password, $genderId, $roleId);

        // Execute the statement
        if ($stmt->execute()) {
            // Success message, user added
            echo "Sign-up successful!";
        } else {
            // Display any error
            echo "Error: " . htmlspecialchars($stmt->error);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Please fill out all fields.";
    }
} else {
    echo "Invalid request method.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Sign Up</h2>
    <form action="signup.php" method="POST">
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select" id="gender" name="genderId" required>
                <option value="">Select Gender</option>
                <!-- Fetching genders from DB -->
                <?php
                $user = new User();
                $genders = $user->getGenders();
                foreach ($genders as $gender) {
                    echo "<option value=\"{$gender['genderId']}\">{$gender['gender']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="roleId" required>
                <option value="">Select Role</option>
                <!-- Fetching roles from DB -->
                <?php
                $roles = $user->getRoles();
                foreach ($roles as $role) {
                    echo "<option value=\"{$role['roleId']}\">{$role['role']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
</div>
</body>
</html>