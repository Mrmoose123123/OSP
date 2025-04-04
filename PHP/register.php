<?php
include 'db_connection.php';
session_start();
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

function register_user($postData)
{
    $conn = get_database_connection();
    $currentDate = date("Y-m-d");
    $errors = [];

    $username = $conn->real_escape_string($postData["username"]);
    $password = password_hash($postData["password"], PASSWORD_DEFAULT);
    $firstname = $conn->real_escape_string($postData["firstname"]);
    $surname = $conn->real_escape_string($postData["surname"]);
    $email = $conn->real_escape_string($postData["email"]);
    $mobile = $conn->real_escape_string($postData["mobile"]);
    $date_of_birth = $conn->real_escape_string($postData["date_of_birth"]);



    // Error Handling for Empty Values
      if (empty($username)) {
        $errors[] = "Username is required.";
    }
     if (empty($password)) {
        $errors[] = "Password is required.";
    }
    if (empty($firstname)) {
        $errors[] = "First name is required.";
    }
    if (empty($surname)) {
        $errors[] = "Surname is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    if (empty($mobile)) {
        $errors[] = "Mobile number is required.";
    }
    if (empty($date_of_birth)) {
        $errors[] = "Date of birth is required.";
    }
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    // Date of birth Validation
    $date_of_birth_obj = DateTime::createFromFormat('Y-m-d', $date_of_birth);

    if (!$date_of_birth_obj || $date_of_birth_obj->format('Y-m-d') !== $date_of_birth) {
         $errors[] = "Invalid date of birth.";
    }

    if (!empty($errors)) {
        $_SESSION['error_message'] = $errors;
        header("Location: form_ref.php");
        exit;
    }

    // Password Hashing


    // Call Check Name Function
    checkName($conn, $username, $password, $firstname, $surname, $email, $mobile, $date_of_birth, $currentDate);
}

function checkName($conn, $username, $password, $firstname, $surname, $email, $mobile, $date_of_birth, $currentDate)
{
    $checkUser = "SELECT username FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUser);

    if ($stmt === false) {
        handleDatabaseError($conn->error, "preparing statement for username check");
        return; // Exit if error
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = ["Username already exists. Please choose another."];
        $stmt->close();
        header("Location: form_ref.php");
        exit;
    }

    $stmt->close();
    insert_data($conn, $username, $password, $firstname, $surname, $email, $mobile, $date_of_birth, $currentDate);
}

function insert_data($conn, $username, $password, $firstname, $surname, $email, $mobile, $date_of_birth, $currentDate)
{
    $sql = "INSERT INTO users (username, password, firstname, surname, date_of_birth, email, mobile, date_recorded)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        handleDatabaseError($conn->error, "preparing statement for user insertion");
        return; // Exit if error
    }

    $stmt->bind_param("ssssssss", $username, $password, $firstname, $surname, $date_of_birth, $email, $mobile, $currentDate);

    if ($stmt->execute()) {
        $userID = $conn->insert_id;
        $_SESSION['user_id'] = $userID;
        $_SESSION['username'] = $username;

        header("Location: dashboard.php");
       
    } else {
        $_SESSION['error_message'] = ["Error registering user: " . htmlspecialchars($stmt->error)];
        header("Location: form_ref.php");
       
    }
    $stmt->close();
     exit; // Move exit here to ensure that the statement will close first
}

function handleDatabaseError($error, $message)
{
    error_log("Database error: " . $message . " - " . $error);
    $_SESSION['error_message'] = ["A database error occurred. Please try again later."];
     header("Location: form_ref.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    register_user($_POST);
}


?>