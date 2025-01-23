<?php
include 'db_connection.php';
session_start();
 

function get_user_by_username($username) {
    $conn = get_database_connection();
 
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
 
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
 
    $stmt->bind_param("s", $username);
    $stmt->execute();
 
    $result = $stmt->get_result();
    $user = ($result->num_rows > 0) ? $result->fetch_assoc() : null;
 
    $stmt->close();
    $conn->close();
 
    return $user;
}

function validate_password($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}
 

function handle_login($username, $password) {
    $user = get_user_by_username($username);
 
    if ($user && validate_password($password, $user['password'])) {

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
 

        header("Location: dashboard.php");
        exit();
    }
 
    return "Invalid username or password.";
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];
 

    $login_result = handle_login($username, $password);
 
    if ($login_result) {
        echo $login_result;
    }
} else {
    echo "Invalid request method.";
}
?>
