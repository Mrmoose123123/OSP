<?php
include 'db_connection.php';
session_start();
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

function register_user($postData) {
    $conn = get_database_connection();

    $currentDate = date("Y-m-d"); // Corrected date format

    $username = $conn->real_escape_string($postData["username"]);
    $password = password_hash($postData["password"], PASSWORD_DEFAULT);
    $firstname = $conn->real_escape_string($postData["firstname"]);
    $surname = $conn->real_escape_string($postData["surname"]);
    $email = $conn->real_escape_string($postData["email"]);
    $mobile = $conn->real_escape_string($postData["mobile"]);
    $date_of_birth = $conn->real_escape_string($postData["date_of_birth"]);

    checkName($conn, $username, $password, $firstname, $surname, $email, $mobile, $date_of_birth, $currentDate); // Pass currentDate
}

function checkName($conn, $username, $password, $firstname, $surname, $email, $mobile, $date_of_birth, $currentDate) {
    $checkUser = "SELECT username FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUser);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Username already exists. Please choose another.";
        header("Location: form_ref.php");
        exit;
    }
    insert_data($conn, $username, $password, $firstname, $surname, $email, $mobile, $date_of_birth, $currentDate); // Pass currentDate
}

function insert_data($conn, $username, $password, $firstname, $surname, $email, $mobile, $date_of_birth, $currentDate) {
    $sql = "INSERT INTO users (username, password, firstname, surname, date_of_birth, email, mobile, date_recorded)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Corrected bind_param string
    $stmt->bind_param("ssssssss", $username, $password, $firstname, $surname, $date_of_birth, $email, $mobile, $currentDate);

    if ($stmt->execute()) {
        $userID = $conn->insert_id;
        $_SESSION['user_id'] = $userID;
        $_SESSION['username'] = $username;

        echo "Registration successful! User ID: $userID";
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Error registering user: " . $stmt->error; // Changed to $stmt->error to get more specific error.
        header("Location: form_ref.php");
        exit;
    }
    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    register_user($_POST);
}
?>
