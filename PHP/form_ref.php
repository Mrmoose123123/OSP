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
    <link rel="stylesheet" href="..\Css\register.css">
</head>
<body>
<nav>
        <div class="nav-left">

            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="100 -50 500 600">
                <defs>
                    <style>
                      .cls-1 {
                        fill: #009444;
                      }
                
                      .cls-2 {
                        fill: #231f20;
                        font-family: Magneto-Bold, Magneto;
                        font-size: 36px;
                        font-weight: 700;
                      }
                
                      .cls-3 {
                        fill: url(#linear-gradient);
                      }
                
                      .cls-3, .cls-4 {
                        stroke: #231f20;
                        stroke-miterlimit: 10;
                      }
                
                      .cls-4 {
                        fill: url(#linear-gradient-2);
                      }
                    </style>
                    <linearGradient id="linear-gradient" x1="181.34" y1="218.41" x2="391.91" y2="218.41" gradientTransform="translate(250.79 -140.16) rotate(46.98)" gradientUnits="userSpaceOnUse">
                      <stop offset="0" stop-color="#fff"/>
                      <stop offset=".64" stop-color="#39b54a"/>
                    </linearGradient>
                    <linearGradient id="linear-gradient-2" x1="240.63" y1="218.41" x2="332.63" y2="218.41" gradientTransform="matrix(1,0,0,1,0,0)" xlink:href="#linear-gradient"/>
                  </defs>
                  <!-- Generator: Adobe Illustrator 28.7.5, SVG Export Plug-In . SVG Version: 1.2.0 Build 176)  -->
                  <g>
                    <g id="Layer_1">
                      <rect class="cls-3" x="210.54" y="145.41" width="152.18" height="146" transform="translate(-68.6 278.99) rotate(-46.98)"/>
                      <rect class="cls-1" x="262.85" y="142.37" width="46" height="152.3" transform="translate(238.24 -138.12) rotate(45)"/>
                      <ellipse class="cls-4" cx="286.63" cy="218.41" rx="46" ry="40"/>
                      <text class="cls-2" transform="translate(270.64 230.48)"><tspan x="0" y="0">R</tspan></text>
                    </g>
                  </g>
                </svg>

            <span class="comapany-name">Rolsa Technologies</span>
        </div>


        <ul class="navbar">
            <li><a href="../main.html">Home</a></li>
            <li><a href="../about.html">About</a></li>
            <li><a href="../Contact.html">Contact</a></li>
            <li><a href="../Carbon.html">Carbon Calculator</a></li>
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
    <footer>
        <div class="copyright">
            <p>© Rolsa Technologies owns all rights to the website, having agreed to the terms and conditions</p>
        </div>
    </footer>


</body>
</html>