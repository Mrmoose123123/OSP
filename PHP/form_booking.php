<?php
session_start();

require_once 'db_connection.php'; // Ensure path is correct

$conn = get_database_connection();

$success_message = '';
$error_message = '';
$user_id_to_insert = null; // Initialize variable to hold the found user ID

// --- Handle Form Submission (POST request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username, slot, and consultant selection
    if (empty($_POST['username']) || empty($_POST['consult_slot_id']) || empty($_POST['consultant_id'])) {
        $error_message = "Please enter your Username and select both a consultation slot and a consultant.";
    } else {
        // Get username from POST data - Sanitize string input
        // Using trim to remove leading/trailing whitespace
        $username_from_post = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS)); // Basic sanitization

        // Get slot and consultant IDs
        $selected_slot_id = filter_input(INPUT_POST, 'consult_slot_id', FILTER_VALIDATE_INT);
        $selected_consultant_id = filter_input(INPUT_POST, 'consultant_id', FILTER_VALIDATE_INT);

        if ($selected_slot_id === false || $selected_consultant_id === false) {
             $error_message = "Invalid slot or consultant selection provided.";
        } elseif (empty($username_from_post)) { // Check if username is empty after trimming
            $error_message = "Username cannot be empty.";
        } else {
            // --- Lookup User ID based on Username ---
            $sql_lookup = "SELECT id FROM users WHERE username = ? LIMIT 1";
            $stmt_lookup = $conn->prepare($sql_lookup);

            if ($stmt_lookup === false) {
                // error_log("MySQLi Prepare Error (lookup): " . $conn->error);
                $error_message = "Error preparing username verification. Please try again later.";
            } else {
                $stmt_lookup->bind_param("s", $username_from_post); // "s" for string username

                if ($stmt_lookup->execute()) {
                    $result_lookup = $stmt_lookup->get_result();
                    if ($result_lookup->num_rows === 1) {
                        $user_row = $result_lookup->fetch_assoc();
                        $user_id_to_insert = $user_row['id']; // <<< Found the user ID!
                    } else {
                        // Username not found in the database
                        $error_message = "Username '" . htmlspecialchars($username_from_post) . "' not found. Please check your username or register.";
                    }
                } else {
                    // error_log("MySQLi Execute Error (lookup): " . $stmt_lookup->error);
                    $error_message = "Error verifying username. Please try again.";
                }
                $stmt_lookup->close(); // Close the lookup statement
            }
            // --- End Lookup ---

            // --- Proceed with Booking INSERT only if User ID was found ---
            if ($user_id_to_insert !== null && empty($error_message)) {

                // Prepare INSERT statement for Consultation_bookings using MySQLi
                $sql_insert = "INSERT INTO Consultation_bookings
                            (user_id, consult_slot_id, consultant_id, Consultation_date_recorded)
                        VALUES
                            (?, ?, ?, NOW())";

                $stmt_insert = $conn->prepare($sql_insert);

                if ($stmt_insert === false) {
                    // error_log("MySQLi Prepare Error (insert): " . $conn->error);
                    $error_message = "An error occurred preparing the booking. Please try again later.";
                } else {
                    // Bind parameters - Use the LOOKED UP user ID ($user_id_to_insert)
                    $stmt_insert->bind_param("iii", $user_id_to_insert, $selected_slot_id, $selected_consultant_id);

                    if ($stmt_insert->execute()) {
                        // <<< MODIFIED LINE: Include consultant ID in success message >>>
                        $_SESSION['success_message'] = "Consultation booked successfully for user '"
                                                       . htmlspecialchars($username_from_post)
                                                       . "' with Consultant (ID: "
                                                       . htmlspecialchars((string)$selected_consultant_id) // Ensure it's treated as string
                                                       . ")!";
                        // <<< END MODIFICATION >>>

                        $stmt_insert->close();
                        $conn->close();
                        header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
                        exit();
                    } else {
                        if ($conn->errno == 1062) {
                            $error_message = "This slot might already be booked or there was a conflict. Please try a different slot.";
                        } else {
                            // error_log("MySQLi Execute Error (insert): " . $stmt_insert->error);
                            $error_message = "Failed to book consultation. Please try again.";
                        }
                    }
                    // Close insert statement even on error
                    if(isset($stmt_insert)) $stmt_insert->close();
                }
            }
            // If $user_id_to_insert is null, the $error_message from the lookup phase will be shown.
        }
    }
}


// --- Retrieve Success/Error Messages from Session ---
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// --- Fetch Data for Form Dropdowns using MySQLi ---
$available_slots = [];
$consultants = [];

// Try to fetch data only if there isn't already a critical error message from POST handling
// And if the connection is still valid
if (empty($error_message) && $conn && $conn->ping()) {
    $sql_slots = "SELECT Consult_slot_id, day, start_time, end_time
                  FROM consult_slot
                  WHERE available_slot = TRUE
                  ORDER BY FIELD(day, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'), start_time";
    $result_slots = $conn->query($sql_slots);

    if ($result_slots === false) {
        // error_log("MySQLi Query Error (slots): " . $conn->error);
        $error_message = "Error loading available slots. Please try again later.";
    } elseif ($result_slots->num_rows > 0) {
        while ($row = $result_slots->fetch_assoc()) {
            $available_slots[] = $row;
        }
        $result_slots->free();
    }

    // Only fetch consultants if slots were fetched successfully (or if no slots is not an error)
    if (empty($error_message)) {
        $sql_consultants = "SELECT Consultant_id, first_name, Surname FROM Consultant ORDER BY Surname, first_name";
        $result_consultants = $conn->query($sql_consultants);

        if ($result_consultants === false) {
           // error_log("MySQLi Query Error (consultants): " . $conn->error);
           $error_message = "Error loading consultants. Please try again later.";
        } elseif ($result_consultants->num_rows > 0) {
           while ($row = $result_consultants->fetch_assoc()) {
               $consultants[] = $row;
           }
           $result_consultants->free();
       }
    }
} else if (!$conn) {
    // If the initial connection failed (though get_database_connection should ideally handle this)
    $error_message = "Database connection failed. Please try again later.";
}


// --- Close MySQLi connection if it's still open ---
if ($conn && $conn->ping()) {
   $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Consultation</title>
    <link rel="stylesheet" href="../Css/register.css"> <!-- Adjust path if needed -->
    <style>
        /* --- Keep your existing styles --- */
        body { background-color: #f4f7f6; color: #333; font-family: Arial, sans-serif; margin: 0; padding: 0; } /* Added margin/padding reset */
        .booking-container { max-width: 600px; margin: 40px auto; padding: 30px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .booking-container h1 { text-align: center; color: #009444; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        /* Style username input like select */
        .form-group select, .form-group input[type="text"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; background-color: #fff; box-sizing: border-box; }
        .form-group select:focus, .form-group input[type="text"]:focus { outline: none; border-color: #009444; box-shadow: 0 0 5px rgba(0, 148, 68, 0.3); }
        .submit-button { width: 100%; padding: 12px; background-color: #009444; color: white; border: none; border-radius: 5px; font-size: 18px; font-weight: bold; cursor: pointer; transition: background-color 0.3s ease; }
        .submit-button:hover { background-color: rgb(23, 187, 78); }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; font-weight: bold; }
        .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error-message { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        /* --- Keep/Add your Nav styles --- */
        nav{ background-color: rgb(15, 145, 37); padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; }
        .nav-left{ display: flex; align-items: center; }
        .company-name{ color: bisque; font-weight: bold; font-size: 20px; margin-right: 20px; /* Added margin */ }
        ul.navbar { list-style-type: none; padding: 0; margin: 0; display: flex;}
        ul.navbar li { margin: 0 10px; }
        .nav-right a, .navbar a { color: bisque; text-decoration: none; padding: 8px 12px; border-radius: 5px; transition: background-color 0.3s ease; display: inline-block; /* Better alignment */ }
        .nav-right a:hover, .navbar a:hover { background-color: #333; }
         .nav-right { display: flex; align-items: center; } /* Align items in nav-right */
         .nav-right a { margin-left: 10px; } /* Space between nav-right links */
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
             <li><a href="book_installation.php">Book Installation</a></li> <!-- Link back -->
             <!-- Add other relevant links -->
         </ul>

         <div class="nav-right">
             <a href="dashboard.php">Dashboard</a>
             <a href= "logout.php">Logout</a>
         </div>
    </nav>

    <div class="booking-container">
        <h1>Book a Consultation</h1>

        <?php if (!empty($success_message)): ?>
            <p class="message success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <p class="message error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <?php // Show form only if there are slots AND consultants AND no critical error prevented loading them ?>
        <?php if (!empty($available_slots) && !empty($consultants) && empty($error_message)): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <!-- CHANGED User ID Input to Username Input -->
                <div class="form-group">
                    <label for="username">Your Username:</label>
                    <input type="text" name="username" id="username" required placeholder="Enter your Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <small style="color: #777; display: block; margin-top: 5px;">Note: Your username is needed to make a booking.</small>
               </div>
                <!-- End Changed Field -->

                <div class="form-group">
                    <label for="consult_slot_id">Choose a Time Slot:</label>
                    <select name="consult_slot_id" id="consult_slot_id" required>
                        <option value="">-- Select a Slot --</option>
                        <?php foreach ($available_slots as $slot): ?>
                            <?php
                                // Attempt to format time, handle potential errors gracefully
                                $start_formatted = 'N/A';
                                $end_formatted = 'N/A';
                                try {
                                    $start_time_obj = new DateTime($slot['start_time']);
                                    $start_formatted = $start_time_obj->format("h:i A");
                                    $end_time_obj = new DateTime($slot['end_time']);
                                    $end_formatted = $end_time_obj->format("h:i A");
                                } catch (Exception $e) {
                                    // Log error if desired: error_log("Time formatting error: " . $e->getMessage());
                                }
                                $day_formatted = ucfirst(htmlspecialchars($slot['day']));
                            ?>
                            <option value="<?php echo htmlspecialchars($slot['Consult_slot_id']); ?>" <?php echo (isset($_POST['consult_slot_id']) && $_POST['consult_slot_id'] == $slot['Consult_slot_id']) ? 'selected' : ''; ?>>
                                <?php echo $day_formatted . " " . $start_formatted . " - " . $end_formatted; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="consultant_id">Choose a Consultant:</label>
                    <select name="consultant_id" id="consultant_id" required>
                        <option value="">-- Select a Consultant --</option>
                        <?php foreach ($consultants as $consultant): ?>
                            <option value="<?php echo htmlspecialchars($consultant['Consultant_id']); ?>" <?php echo (isset($_POST['consultant_id']) && $_POST['consultant_id'] == $consultant['Consultant_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($consultant['first_name'] . " " . $consultant['Surname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="submit-button">Book Consultation</button>

            </form>
        <?php elseif (empty($error_message)): // Only show this message if there wasn't an error, just no data ?>
             <p style="text-align: center; color: #777;">No consultation slots or consultants are currently available. Please check back later.</p>
        <?php endif; ?>
        <?php // If there was an error message ($error_message is not empty), it's already displayed above ?>

    </div>

</body>
</html>