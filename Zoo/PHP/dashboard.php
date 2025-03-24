<?php

session_start();
/*
if (!isset($_SESSION['user_id'])) {
   // header(header: "login_form.php");
    exit();
}

*/

ini_set("dispaly_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
    <link rel="stylesheet" href="..\Css\dashboard_css.css">
</head>
<body>
    <nav>
        <div class="nav-left">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 2L2 12h3v8h5v-6h4v6h5v-8h3L12 2z"/>
            </svg>
            <span class="company-name">RigetZoo</span>
        </div>
        
        <ul class="navbar">
            <li><a href="form_booking.php">Bookings</a></li>
            <li><a href="..\contact.html">Contact</a></li>
        </ul>

        <div class="nav-right">
            <span> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="/PHP/logout.php">Logout</a>
        </div>    
    </nav>


    <div class="Hero">
        <h1>dashboard<h1>
    </div>
</div>




</body>
</html> 

