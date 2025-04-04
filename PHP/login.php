<?php
include 'db_connection.php';
session_start();

function get_sanitised_user_inputs(mysqli $conn): array
{
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $conn->real_escape_string($_POST["password"]);

    return array('username' => $username, 'password' => $password);
}


function get_user_data(mysqli $conn, string $username): ?array
{
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    } else {
        $user_data = null;
    }


    $stmt->close();

    return $user_data;
}


function verify_password(string $password, string $hashedPassword): bool
{
    return password_verify($password, $hashedPassword);
}


function login_user(array $user_data): void
{
    $_SESSION['user_id'] = $user_data['user_id'];
    $_SESSION['username'] = $user_data['username'];

    header("Location: dashboard.php");
    exit();
}


function handle_login(mysqli $conn): void
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
        $inputs = get_sanitised_user_inputs($conn);
        $username = $inputs['username'];
        $password = $inputs['password'];
        
        // Retrieve user data from database
        $user_data = get_user_data($conn, $username);

        if ($user_data) {
            // Verify Password
            if (verify_password($password, $user_data['password'])) {
                // Login user
                login_user($user_data);
            } else {
                echo "Invalid username or password";
            }
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Invalid request method.";
    }
}


$conn = get_database_connection();


handle_login($conn);

$conn->close();
?>