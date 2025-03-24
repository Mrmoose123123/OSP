<?php


session_start();


include 'db_connection.php';

include 'date_generator.php';



$conn = get_database_connection();

$dates = generationNextSevenDays();


$defaultDate = $dates[0]['dayOfWeek'];

$conn->close();



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\Css\bookings_css.css">
    <script src="../JavaScript/bookings_scripts.js"></script>
    <title>Gym Data</title>
    <style>
       .Hero {
            display: flex;
            flex-direction: column;
            align-items: center; /* Horizontally center content */
            justify-content: center; /* Vertically center content */
            text-align: center; /* Center text within the Hero section */
            /* Add any other styles for Hero here */
        }
        .booking_container {
            display: flex;
            flex-direction: column;
            align-items: center; /* Horizontally center content */
            justify-content: center; /* Vertically center content */
            min-height: 300px; /* Ensure the container has some height */
            width: 100%; /* Take up the full width */
        }

        .form-group {
            margin-bottom: 15px;
            text-align: center; /* Center the label and input within the group */
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group select,
        .form-group input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px; /* Adjust as needed */
            margin: 0 auto; /* Center the input elements horizontally */
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 2L2 12h3v8h5v-6h4v6h5v-8h3L12 2z"/>
            </svg>
            <span class="company-name">Gym Company</span>
        </div>
        <ul class="navbar">
            <li><a href="#">Home</a></li>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Classes</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
        <div class="nav-right">
            <span>Welcome!</span>
            <a href="#">Logout</a>
        </div>
    </nav>

    <div class="Hero">
        <h1>Book Your Tickets</h1>
        <p>Select a booking slot and specify the number of tickets.</p>
        <label for="bookingSlot">Select Booking Slot:</label>
        <select id="bookingSlot" name="bookingSlot" required>
            <!-- Options will be populated by JavaScript -->
        </select>
    </div>

    <div class="booking_container">
        <form id="bookingForm">
            <div class="form-group">
                <label for="adultTickets">Adult Tickets:</label>
                <input type="number" id="adultTickets" name="adultTickets" value="0" min="0">
            </div>

            <div class="form-group">
                <label for="childTickets">Child Tickets:</label>
                <input type="number" id="childTickets" name="childTickets" value="0" min="0">
            </div>

            <button type="submit">Book Tickets</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingSlotSelect = document.getElementById('bookingSlot');
            const bookingForm = document.getElementById('bookingForm');

            // Function to fetch booking slots from PHP
            async function fetchBookingSlots() {
                try {
                    const response = await fetch('get_booking_slots.php'); // Change to your PHP script URL
                    const data = await response.json();

                    // Populate dropdown with booking slots
                    data.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.slot_id;
                        option.textContent = `Slot ID: ${slot.slot_id}, Time: ${slot.start_time} - ${slot.end_time}, Capacity: ${slot.capacity}`;
                        bookingSlotSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error fetching booking slots:', error);
                }
            }

            fetchBookingSlots();

            // Handle form submission
            bookingForm.addEventListener('submit', async function(event) {
                event.preventDefault();

                const slotId = bookingSlotSelect.value;
                const adultTickets = document.getElementById('adultTickets').value;
                const childTickets = document.getElementById('childTickets').value;

                // Data to send to PHP for processing
                const bookingData = {
                    slotId: slotId,
                    adultTickets: adultTickets,
                    childTickets: childTickets
                };

                try {
                    const response = await fetch('submit_booking.php', { // Change to your PHP script URL
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(bookingData)
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Booking successful!');
                        // Optionally, redirect or update the UI as needed
                    } else {
                        alert(`Booking failed: ${result.message}`);
                    }
                } catch (error) {
                    console.error('Error submitting booking:', error);
                    alert('An error occurred during booking.');
                }
            });
        });
    </script>
</body>
</html>