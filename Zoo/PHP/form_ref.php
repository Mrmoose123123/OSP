<?php
session_start();



ini_set("display_errors",1);
ini_set("display_startup_errors",1);
error_reporting(E_ALL);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="..\Css\reg_stylesheet.css">
</head>
<body>
<nav>
        <div class="nav-left">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 2L2 12h3v8h5v-6h4v6h5v-8h3L12 2z"/>
            </svg>
            <span class="comapany-name">Riget Zoo Adventures</span>
        </div>


        <ul class="navbar">
            <li><a href="../main.html">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="../Contact.html">Contact</a></li>
        </ul>



        <div class="nav-right">
            <a href="..\php\login_form.php">Login</a>
        </div>

    </nav>



    <div class="Hero">
    <div class="register-container_row">
    <div class="form-box">
    <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" placeholder="username" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="PASSWORD" placeholder ="password" id="password" name="password" required>

            <label for="firstname">First Name:</label>
            <input type="text" placeholder="firstname" id="firstname" name="firstname" required>

            <label for="surname">Surname:</label>
            <input type="text" placeholder="surname" id="surname" name="surname" required>

            <label for="email">Email:</label>
            <input type="email" placeholder="Email" id="email" name="email" required>

            <label for="mobile">Mobile:</label>
            <input type="text" placeholder="mobile" id="mobile" name="mobile" required>

            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" placeholder= "Date of Birth" id="date_of_birth" name="date_of_birth" required> 


            <button type="submit">Register</button>



        <?php 
        if (!empty($_SESSION['error message'])): ?>
        <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>

        <?php

        endif;
        ?>
        

        </form>





    </div>
    </div>
    </div>



</body>
</html>