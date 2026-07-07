<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_config.php';
require_once 'session_helper.php';

if (isset($db_connection_error) && $db_connection_error !== null) {
    echo json_encode(["status" => "error", "message" => "Database Connection Error: " . $db_connection_error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT id, booking_date, title, booked_by, status FROM bus_bookings ORDER BY booking_date ASC";
        $result = $conn->query($sql);
        $bookings = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
        }
        echo json_encode(["status" => "success", "bookings" => $bookings]);
        break;

    case 'POST':
        // Enforce authentication for modifying requests (POST, DELETE)
        require_auth_for_write();

        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        $booking_date = isset($_POST['booking_date']) ? trim($_POST['booking_date']) : '';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $booked_by = isset($_POST['booked_by']) ? trim($_POST['booked_by']) : '';

        if (empty($booking_date) || empty($title) || empty($booked_by)) {
            echo json_encode(["status" => "error", "message" => "All fields (Date, Purpose, Booked By) are required."]);
            exit;
        }

        // Validate date format (YYYY-MM-DD)
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $booking_date)) {
            echo json_encode(["status" => "error", "message" => "Invalid date format. Use YYYY-MM-DD."]);
            exit;
        }

        $booking_date = $conn->real_escape_string($booking_date);
        $title = $conn->real_escape_string($title);
        $booked_by = $conn->real_escape_string($booked_by);

        // Check if date is already booked by another record
        if (!empty($id)) {
            $id = intval($id);
            $check_sql = "SELECT id FROM bus_bookings WHERE booking_date = '$booking_date' AND id != $id";
        } else {
            $check_sql = "SELECT id FROM bus_bookings WHERE booking_date = '$booking_date'";
        }

        $check_res = $conn->query($check_sql);
        if ($check_res && $check_res->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "This date is already reserved for another trip. Please select a different date."]);
            exit;
        }

        if (!empty($id)) {
            // Update existing booking
            $sql = "UPDATE bus_bookings SET booking_date = '$booking_date', title = '$title', booked_by = '$booked_by' WHERE id = $id";
            $action_word = "updated";
        } else {
            // Insert new booking
            $sql = "INSERT INTO bus_bookings (booking_date, title, booked_by) VALUES ('$booking_date', '$title', '$booked_by')";
            $action_word = "created";
        }

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Vehicle booking $action_word successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database Error: " . $conn->error]);
        }
        break;

    case 'DELETE':
        // Enforce authentication for modifying requests (POST, DELETE)
        require_auth_for_write();

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid booking ID."]);
            exit;
        }

        $sql = "DELETE FROM bus_bookings WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Vehicle booking deleted successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database Error: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}

if ($conn) $conn->close();
