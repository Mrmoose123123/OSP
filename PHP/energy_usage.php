<!DOCTYPE html>
<html>
<head>
    <title>Electricity Usage Calculator</title>
    <style>
        <?php
        // Output the provided CSS here.  Use include or file_get_contents if the CSS
        // is in a separate file for better organization.
        echo "
body, ul, li{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    background-color:rgb(15, 145, 37) ;
}


body{
    font-family: Arial, sans-serif;
}


nav{
    background-color: rgb(15, 145, 37);
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}


.nav-left {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: bisque;
    font-size: 20px;
    

}

.nav-left svg{
    height: 100%;

}




.company-name{
    color: bisque;
    font-size: 20px;
    font-weight: bold;
}

.navbar{
    display: flex;
    list-style-type: none;
    margin: 0,1;
}

.navbar a{
    text-decoration: none;
    color: bisque;
    font-size: 20px;
    padding: 8px 15px;
    transition: background-color 0.3 ease;
    justify-content: space-evenly;
}

.navbar a:hover {
    background-color: #333;
    border-radius: 5px;
}


.nav-right {
    display: flex;
    align-items: center;
}

.nav-right a {
    color: bisque;
    text-decoration: none;
    margin-left: 15px;
    padding: 8px;
    font-size: 20px;
}

.nav-right a:hover{
    background-color: #333;
    border-radius: 5px;
}


.Hero{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-image: url(../images/Ev_Charging_station.png);
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    text-align: center;
    color: bisque;
    padding: 20px;
}



.Hero h1{
    font-size: 48px;
    margin-bottom: 10px;
}

.Hero p{
    font-size: 24px;
    max-width: 600px;
}

.Hero a{
    text-decoration: none;
    color: rgb(255, 255, 255);
    font-size: 18px;
    padding: 10px 20px;
    transition: background-color 0.3 ease;
    background-color: green;
    border: 10px;
}

.Hero a:hover{
    background-color: rgb(23, 187, 78);
    border: 10px;
}




.copyright {
    display: flex;
    margin-top: 10px;
    background-color: r(15, 145, 37);
    text-align: center;
    justify-content: center;
    padding: 5px;
    font-weight: 700;

}

.copyright a{
    color: bisque;
}



.register_container_row {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 90vw; /* 90% of viewport width */
    margin: 20px auto;
    padding: 20px;
    border-radius: 10px;
    background-color: #f4f4f4;
}

/*...........Form......*/
.form-box label {
    display: block;
    color: #ffffff; /* Dark gray color */
    font-size: 16px;
    font-weight: bold;
    font-family: Arial, sans-serif;
    margin-bottom: 5px;
    margin-left: 10%; /* Aligns label text with the input boxes */
    text-align: left;
}
.form-box {
    width: 100%; /* Full width inside the container */
    display: flex;
    flex-direction: column;
    align-items: center;
}

.form-box input[type=\"text\"],
.form-box input[type=\"email\"],
.form-box input[type=\"password\"],
.form-box input[type=\"tel\"],

.form-box input[type=\"date\"] {
    width: 80%; /* Consistent width */
    padding: 10px;
    margin: 10px 0;
    margin-left: auto;
    margin-right: auto;
    display: block; /* Centers the input boxes */
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px; /* Ensures text is readable and consistent */
}

.form-box button {
    width: 80%; /* Match input field width */
    padding: 10px;
    margin-top: 20px;
    margin-left: 20px; /* Extra margin to separate from the fields */
    border: none;
    border-radius: 5px;
    background-color: rgb(23, 187, 78);
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 16px;
    justify-content: center;
    align-items: center;
    display: flex;
  
}



@media (max-width: 480px){
    .navbar a {
        font-size: 14px;
        margin: 0 5px;
    }

    .box{
        flex: 1 1 100%;
        max-width: 100%;
    }

    .navbar {
        padding: 10px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .navbar a {
        display: block;
        width: 100%;
        text-align: center;
    }


    .Hero h1 {
        font-size: 32px;
    }

    .Hero p {
        font-size: 18px;
    }
}


@media (min-width: 481px){
    .navbar {
        padding: 20px 30px;
    }

    .b-ox{
        flex: 1;
        max-width: 100%;
    }
}
";
        ?>
        #calculatorContainer {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;  /* Or adjust as needed */
            background-image: url('../images/Ev_Charging_station.png'); /* Replace with your actual image URL */
            background-size: cover;
            background-position: center;
            color: bisque; /* Set a default text color for the calculator area */
        }


        #calculator {
            background-color: rgba(0, 0, 0, 0.5);  /* Add semi-transparent background */
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        #calculator label {
            display: block; /* Stack labels on top of inputs */
            margin-bottom: 5px;
        }

        #calculator input[type="number"] {
            width: 200px;  /* Adjust width as needed */
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        #calculator button {
            background-color: #4CAF50; /* Green */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        #calculator button:hover {
            background-color: #3e8e41;
        }

        #result {
            margin-top: 20px;
            font-weight: bold;
        }


    </style>
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
            <span class="company-name">Rolsa Technologies</span>
        </div>
        
        <ul class="navbar">
            <li><a href="form_booking.php">Consultations</a></li>
            <li><a href="energy_usage.php">Energy Usage</a></li>
        </ul>

        <div class="nav-right">

            <a href="/PHP/logout.php">Logout</a>
            <a href="/PHP/dashboard.php">Dashboard</a>
        </div>    
    </nav>
    <div id="calculatorContainer">
        <div id="calculator">
            <h2>Electricity Usage Calculator</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="kwhUsed">Kilowatt Hours Used:</label>
                <input type="number" id="kwhUsed" name="kwhUsed" step="0.01" placeholder="Enter kWh used" value="<?php echo isset($_POST['kwhUsed']) ? htmlspecialchars($_POST['kwhUsed']) : ''; ?>" required><br>

                <label for="pricePerKwh">Price per kWh:</label>
                <input type="number" id="pricePerKwh" name="pricePerKwh" step="0.01" placeholder="Enter price per kWh" value="<?php echo isset($_POST['pricePerKwh']) ? htmlspecialchars($_POST['pricePerKwh']) : ''; ?>" required><br>

                <button type="submit">Calculate Cost</button>
            </form>

            <div id="result">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $kwhUsed = isset($_POST["kwhUsed"]) ? floatval($_POST["kwhUsed"]) : 0;
                    $pricePerKwh = isset($_POST["pricePerKwh"]) ? floatval($_POST["pricePerKwh"]) : 0;

                    if (is_numeric($kwhUsed) && is_numeric($pricePerKwh)) {
                        $totalCost = $kwhUsed * $pricePerKwh;
                        echo "Total Cost: £" . number_format($totalCost, 2);
                    } else {
                        echo "Please enter valid numeric values.";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>