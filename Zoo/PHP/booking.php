<?php
// Assuming you have a database connection file, adjust the path if necessary
include 'db_connection.php';

// Function to get database connection (adjust according to your db_connection.php)

// Function to fetch data from the 'users' table
function getUsersData($conn) {
    $sql = "SELECT ID, username, password, firstname, surname, email, mobile, date_of_birth, date_recorded FROM users";

    $result = $conn->query($sql);

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

 // Function to fetch data from the 'bookings' table
 function getBookingsData($conn) {
    $sql = "SELECT booking_id, total_price, payment_status, confirmation_number, ID, slot_id, booking_date FROM bookings";

    $result = $conn->query($sql);

    $bookings = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    return $bookings;
}


// Function to fetch data from the 'booking_slots' table
function getBookingSlotsData($conn) {
    $sql = "SELECT slot_id, start_time, end_time, capacity, availability_spots, is_available FROM booking_slots";

    $result = $conn->query($sql);

    $booking_slots = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $booking_slots[] = $row;
        }
    }
    return $booking_slots;
}

 // Function to fetch data from the 'booked_tickets' table
 function getBookedTicketsData($conn) {
    $sql = "SELECT booked_tickets_id, booking_id, ticket_id, quantity, price FROM booked_tickets";

    $result = $conn->query($sql);

    $booked_tickets = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $booked_tickets[] = $row;
        }
    }
    return $booked_tickets;
}


 // Function to fetch data from the 'tickets' table
 function getTicketsData($conn) {
    $sql = "SELECT ticket_id, ticket_name, description, price, availability FROM tickets";

    $result = $conn->query($sql);

    $tickets = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
    }
    return $tickets;
}

$users = [];
$bookings = [];
$booking_slots = [];
$booked_tickets = [];
$tickets = [];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $conn = get_database_connection();

    $users = getUsersData($conn);
    $bookings = getBookingsData($conn);
    $booking_slots = getBookingSlotsData($conn);
    $booked_tickets = getBookedTicketsData($conn);
    $tickets = getTicketsData($conn);

    $conn->close();
}

$all_data = [
    'users' => $users,
    'bookings' => $bookings,
    'booking_slots' => $booking_slots,
    'booked_tickets' => $booked_tickets,
    'tickets' => $tickets,
];

header('Content-Type: application/json');
echo json_encode($all_data);
?>