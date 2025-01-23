<?php
session_start();



ini_set("dispaly_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="..\Css\reg_stylesheet.css">
</head>
<body>
    <nav>
        <div class="nav-left">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 2L2 12h3v8h5v-6h4v6h5v-8h3L12 2z"/>
            </svg>
            <span class="company-name">GymLand</span>
        </div>
        
        <ul class="navbar">
            <li><a href="../test.html">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
        </ul>

        <div class="nav-right">
            <a href="/PHP/login_form.php">Login</a>
            <a href="/PHP/form_ref.php">Register</a>
        </div>    
    </nav>


    <div class="Hero">
    <div class="register-container_row">
    <div class="form-box">
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" placeholder="username" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="PASSWORD" placeholder ="password" id="password" name="password" required>

            <button type="submit">Login</button>

        </form>
    </div>
    </div>
</div>




</body>
</html> 
