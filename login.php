<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db_connection.php'); // Include the DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $user_name = $_POST['username'];
    $user_password = $_POST['password'];

    // Prepare SQL query to fetch the user details
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($user_password, $hashed_password)) {
            session_start();
            $_SESSION['user_id'] = $user_id;
            echo "<script>alert('Login successful!'); window.location.href = 'dashboard.html';</script>";
        } else {
            echo "<script>alert('Wrong Password!'); window.location.href = 'login.html';</script>";
        }
    } else {
        echo "<script>alert('UserName Does Not Exist!'); window.location.href = 'login.html';</script>";
    }
    $stmt->close();
}
$conn->close();
?>
