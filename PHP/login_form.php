<?php
session_start();

// Error reporting settings (keep as is)
ini_set("display_errors", 1); // Corrected spelling: display_errors
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rolsa Technologies</title>
    <!-- Link to the SHARED main CSS file, using ../ to go up one directory -->
    <link rel="stylesheet" href="../Css/login.css">
    <!-- REMOVE or comment out the link to login.css if its styles are now in main.css
         or if you want to link it *after* main.css for form-specific overrides.
    <link rel="stylesheet" href="../Css/login.css"> -->
</head>
<body>
    <nav>
        <div class="nav-left">
            <!-- SVG Logo -->
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="100 -50 500 600">
                <!-- SVG defs and content -->
                <defs>
                    <style>
                      .cls-1 { fill: #009444; }
                      .cls-2 { fill: #231f20; font-family: Magneto-Bold, Magneto; font-size: 36px; font-weight: 700; }
                      .cls-3 { fill: url(#linear-gradient); }
                      .cls-3, .cls-4 { stroke: #231f20; stroke-miterlimit: 10; }
                      .cls-4 { fill: url(#linear-gradient-2); }
                    </style>
                    <linearGradient id="linear-gradient" x1="181.34" y1="218.41" x2="391.91" y2="218.41" gradientTransform="translate(250.79 -140.16) rotate(46.98)" gradientUnits="userSpaceOnUse">
                      <stop offset="0" stop-color="#fff"/>
                      <stop offset=".64" stop-color="#39b54a"/>
                    </linearGradient>
                    <linearGradient id="linear-gradient-2" x1="240.63" y1="218.41" x2="332.63" y2="218.41" gradientTransform="matrix(1,0,0,1,0,0)" xlink:href="#linear-gradient"/>
                </defs>
                <g><g id="Layer_1">
                      <rect class="cls-3" x="210.54" y="145.41" width="152.18" height="146" transform="translate(-68.6 278.99) rotate(-46.98)"/>
                      <rect class="cls-1" x="262.85" y="142.37" width="46" height="152.3" transform="translate(238.24 -138.12) rotate(45)"/>
                      <ellipse class="cls-4" cx="286.63" cy="218.41" rx="46" ry="40"/>
                      <text class="cls-2" transform="translate(270.64 230.48)"><tspan x="0" y="0">R</tspan></text>
                </g></g>
            </svg>
            <span class="company-name">Rolsa Technologies</span>
        </div>

        <!-- === HAMBURGER BUTTON ADDED HERE === -->
        <button class="menu-toggle" aria-label="Toggle navigation" aria-expanded="false">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
        <!-- =================================== -->

        <ul class="navbar">
            <!-- Use ../ to link to files one level up -->
            <li><a href="../main.html">Home</a></li>
            <li><a href="../about.html">About</a></li>
            <!-- Corrected Contact link -->
            <li><a href="../contact.html">Contact</a></li>
            <li><a href="../Carbon.html">Carbon Calculator</a></li>
        </ul>

        <div class="nav-right">
            <!-- Links are relative to the root if starting with / -->
            <!-- Or use ../PHP/login_form.php if needed -->
            <a href="/PHP/login_form.php">Login</a>
            <a href="/PHP/form_ref.php">Register</a>
        </div>
    </nav>

    <!-- Use <main> for primary content -->
    <!-- Added class 'login-page' for potential specific styling -->
    <main class="Hero login-page">
        <h1>Account Login</h1> <!-- Added a heading -->
        <div class="register-container_row"> <!-- Keep original structure if styled -->
            <div class="form-box"> <!-- Keep original structure if styled -->
                <form action="login.php" method="post">
                    <label for="username">Username:</label>
                    <input type="text" placeholder="username" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <!-- Corrected input type to "password" (lowercase) -->
                    <input type="password" placeholder="password" id="password" name="password" required>

                    <button type="submit">Login</button>
                </form>
                 <p class="form-link">Don't have an account? <a href="/PHP/form_ref.php">Register here</a></p> <!-- Optional link -->
            </div>
        </div>
    </main> <!-- Closing main tag -->

    <!-- === STANDARD FOOTER ADDED HERE === -->
    <footer>
        <div class="copyright">
            <p>© Rolsa Technologies owns all rights to the website, having agreed to the terms and conditions</p>
        </div>
    </footer>
    <!-- ================================ -->

    <!-- === LINK TO THE SAME SHARED JAVASCRIPT FILE === -->
    <!-- Use ../ to link to the js folder one level up -->
    <script src="../js/script.js"></script>
    <!-- =========================================== -->

</body>
</html>