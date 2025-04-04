<?php
session_start(); // Start session for flash messages

// Include DB connection
require_once 'db_connection.php'; // Adjust path if needed ('db_connection.php' if in the same directory)

// Get DB connection
$conn = get_database_connection();

// Initialize variables
$success_message = '';
$error_message = '';
$user_id_to_insert = null; // Will hold the user ID found via username lookup

// --- Handle Form Submission (POST request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username, installation slot, and engineer selection
    if (empty($_POST['username']) || empty($_POST['installation_slot_id']) || empty($_POST['engineer_id'])) {
        $error_message = "Please enter your Username and select both an installation slot and an engineer.";
    } else {
        // Get username from POST data - Sanitize
        $username_from_post = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));

        // Get slot and engineer IDs
        $selected_slot_id = filter_input(INPUT_POST, 'installation_slot_id', FILTER_VALIDATE_INT);
        $selected_engineer_id = filter_input(INPUT_POST, 'engineer_id', FILTER_VALIDATE_INT);

        // Get optional Consultation ID
        $selected_consult_id_input = filter_input(INPUT_POST, 'consult_id', FILTER_VALIDATE_INT);
        // Prepare the value for binding: NULL if empty/invalid, otherwise the integer
        $selected_consult_id_to_bind = ($selected_consult_id_input !== false && $selected_consult_id_input > 0) ? $selected_consult_id_input : NULL;

        // Further validation
        if ($selected_slot_id === false || $selected_engineer_id === false) {
             $error_message = "Invalid slot or engineer selection provided.";
        } elseif (empty($username_from_post)) {
            $error_message = "Username cannot be empty.";
        } else {
            // --- Lookup User ID based on Username (Same logic as before) ---
            $sql_lookup = "SELECT id FROM users WHERE username = ? LIMIT 1";
            $stmt_lookup = $conn->prepare($sql_lookup);

            if ($stmt_lookup === false) {
                $error_message = "Error preparing username verification.";
            } else {
                $stmt_lookup->bind_param("s", $username_from_post);
                if ($stmt_lookup->execute()) {
                    $result_lookup = $stmt_lookup->get_result();
                    if ($result_lookup->num_rows === 1) {
                        $user_row = $result_lookup->fetch_assoc();
                        $user_id_to_insert = $user_row['id']; // Found the user ID
                    } else {
                        $error_message = "Username '" . htmlspecialchars($username_from_post) . "' not found.";
                    }
                } else {
                    $error_message = "Error verifying username.";
                }
                $stmt_lookup->close();
            }
            // --- End Lookup ---

            // --- Proceed with Installation Booking INSERT only if User ID was found ---
            if ($user_id_to_insert !== null && empty($error_message)) {

                // Prepare INSERT statement for Installation_bookings
                $sql_insert = "INSERT INTO Installation_bookings
                            (user_id, installation_slot_id, Engineer_id, installation_date_recorded, Consult_id)
                        VALUES
                            (?, ?, ?, NOW(), ?)"; // 4 placeholders now

                $stmt_insert = $conn->prepare($sql_insert);

                if ($stmt_insert === false) {
                    // error_log("MySQLi Prepare Error (insert): " . $conn->error);
                    $error_message = "An error occurred preparing the installation booking.";
                } else {
                    // Bind parameters: user_id, slot_id, engineer_id, consult_id (can be NULL)
                    // The types are integer, integer, integer, integer (iiii)
                    // MySQLi should handle binding NULL to the last integer parameter if $selected_consult_id_to_bind is NULL
                    $stmt_insert->bind_param("iiii",
                        $user_id_to_insert,
                        $selected_slot_id,
                        $selected_engineer_id,
                        $selected_consult_id_to_bind // This variable holds the ID or NULL
                    );

                    if ($stmt_insert->execute()) {
                        $_SESSION['success_message'] = "Installation booked successfully for user '" . htmlspecialchars($username_from_post) . "'!";
                        $stmt_insert->close();
                        $conn->close();
                        header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
                        exit();
                    } else {
                        // Check for duplicate booking errors if constraints exist
                        // if ($conn->errno == 1062) { ... }
                        // error_log("MySQLi Execute Error (insert): " . $stmt_insert->error);
                        $error_message = "Failed to book installation. Please try again. Error: " . $stmt_insert->error; // Show error for debugging
                    }
                     // Close insert statement even on error
                    if(isset($stmt_insert)) $stmt_insert->close();
                }
            }
            // If $user_id_to_insert is null, the $error_message from lookup will be shown.
        }
    }
}


// --- Retrieve Success/Error Messages from Session ---
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// --- Fetch Data for Form Dropdowns using MySQLi ---
$available_installation_slots = [];
$engineers = [];

// Fetch available installation slots
$sql_slots = "SELECT Installation_slot_id, day, start_time, end_time
              FROM installation_slot -- Changed table name
              WHERE available_slot = TRUE
              ORDER BY FIELD(day, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'), start_time";

$result_slots = $conn->query($sql_slots);

if ($result_slots === false) {
    $error_message = "Error loading available installation slots.";
} elseif ($result_slots->num_rows > 0) {
    while ($row = $result_slots->fetch_assoc()) {
        $available_installation_slots[] = $row;
    }
    $result_slots->free();
}

// Fetch engineers (only if slots loaded without critical error)
if (empty($error_message)) {
    $sql_engineers = "SELECT Engineer_id, first_name, Surname FROM Engineer ORDER BY Surname, first_name"; // Changed table name
    $result_engineers = $conn->query($sql_engineers);

    if ($result_engineers === false) {
       $error_message = "Error loading engineers list.";
    } elseif ($result_engineers->num_rows > 0) {
       while ($row = $result_engineers->fetch_assoc()) {
           $engineers[] = $row;
       }
       $result_engineers->free();
   }
}


// --- Close MySQLi connection ---
if ($conn && $conn->ping()) {
   $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Installation</title> <!-- Changed Title -->
    <link rel="stylesheet" href="../Css/register.css"> <!-- Adjust path if needed -->
    <style>
        /* --- Use the same styles as book_consultation.php --- */
        body { background-color: #f4f7f6; color: #333; font-family: Arial, sans-serif; }
        .booking-container { max-width: 600px; margin: 40px auto; padding: 30px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .booking-container h1 { text-align: center; color: #009444; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        /* Style text and number inputs like select */
        .form-group select, .form-group input[type="text"], .form-group input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; background-color: #fff; box-sizing: border-box; }
        .form-group select:focus, .form-group input[type="text"]:focus, .form-group input[type="number"]:focus { outline: none; border-color: #009444; box-shadow: 0 0 5px rgba(0, 148, 68, 0.3); }
        .submit-button { width: 100%; padding: 12px; background-color: #009444; color: white; border: none; border-radius: 5px; font-size: 18px; font-weight: bold; cursor: pointer; transition: background-color 0.3s ease; }
        .submit-button:hover { background-color: rgb(23, 187, 78); }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; font-weight: bold; }
        .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error-message { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        /* --- Nav styles --- */
        nav{ background-color: rgb(15, 145, 37); padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; }
        .nav-left{ display: flex; align-items: center; }
        .company-name{ color: bisque; font-weight: bold; font-size: 20px; }
        ul.navbar { list-style-type: none; padding: 0; margin: 0; display: flex;}
        ul.navbar li { margin: 0 10px; }
        .nav-right a, .navbar a { color: bisque; text-decoration: none; padding: 8px 12px; border-radius: 5px; transition: background-color 0.3s ease; }
        .nav-right a:hover, .navbar a:hover { background-color: #333; }
    </style>
</head>
<body>

    <!-- Navigation -->
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
             <li><a href="../main.html">Home</a></li>
             <li><a href="form_booking.php">Book Consultation</a></li> <!-- Link back -->
             <!-- Add other relevant links -->
         </ul>
         <div class="nav-right">
             <a href="dashboard.php">Dashboard</a>
             <a href="logout.php">Logout</a>
         </div>
    </nav>

    <div class="booking-container">
        <h1>Book an Installation</h1> <!-- Changed Heading -->

        <?php if (!empty($success_message)): ?>
            <p class="message success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <p class="message error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <?php if (!empty($available_installation_slots) && !empty($engineers)): // Check correct variables ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <!-- Username Input (Same as before) -->
                <div class="form-group">
                    <label for="username">Your Username:</label>
                    <input type="text" name="username" id="username" required placeholder="Enter your Username">
                     <small style="color: #777; display: block; margin-top: 5px;">Note: Your username is needed to make a booking.</small>
               </div>

                <!-- Installation Slot Dropdown -->
                <div class="form-group">
                    <label for="installation_slot_id">Choose an Installation Time Slot:</label> <!-- Changed Label -->
                    <select name="installation_slot_id" id="installation_slot_id" required> <!-- Changed name/id -->
                        <option value="">-- Select an Installation Slot --</option>
                        <?php foreach ($available_installation_slots as $slot): // Changed loop variable ?>
                            <?php
                                $start_formatted = date("h:i A", strtotime($slot['start_time']));
                                $end_formatted = date("h:i A", strtotime($slot['end_time']));
                                $day_formatted = ucfirst($slot['day']);
                            ?>
                            <option value="<?php echo htmlspecialchars($slot['Installation_slot_id']); ?>"> <!-- Changed value column -->
                                <?php echo htmlspecialchars($day_formatted . " " . $start_formatted . " - " . $end_formatted); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Engineer Dropdown -->
                <div class="form-group">
                    <label for="engineer_id">Choose an Engineer:</label> <!-- Changed Label -->
                    <select name="engineer_id" id="engineer_id" required> <!-- Changed name/id -->
                        <option value="">-- Select an Engineer --</option>
                        <?php foreach ($engineers as $engineer): // Changed loop variable ?>
                            <option value="<?php echo htmlspecialchars($engineer['Engineer_id']); ?>"> <!-- Changed value column -->
                                <?php echo htmlspecialchars($engineer['first_name'] . " " . $engineer['Surname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Optional Consultation ID Input -->
                <div class="form-group">
                    <label for="consult_id">Consultation Booking ID (Optional):</label>
                    <input type="number" name="consult_id" id="consult_id" placeholder="Enter ID if installation follows a consultation">
                    <small style="color: #777; display: block; margin-top: 5px;">Leave blank if this installation was not preceded by a booked consultation.</small>
                </div>

                <button type="submit" class="submit-button">Book Installation</button> <!-- Changed Button Text -->

            </form>
        <?php elseif (empty($error_message)): ?>
             <p style="text-align: center; color: #777;">Loading installation options or no options currently available.</p>
        <?php endif; ?>

    </div>

</body>
</html>