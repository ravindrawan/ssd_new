<?php
require_once 'db_config.php';

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

echo "Database connection successful!\n";

// 1. Verify bus_bookings table exists and check schema
$result = $conn->query("SHOW TABLES LIKE 'bus_bookings'");
if ($result->num_rows === 0) {
    die("Table bus_bookings does not exist!\n");
}
echo "Table 'bus_bookings' exists.\n";

// 2. Fetch seeded bookings
$result = $conn->query("SELECT * FROM bus_bookings");
echo "Seeded Bookings Count: " . $result->num_rows . "\n";
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']}, Date: {$row['booking_date']}, Title: {$row['title']}, Booked By: {$row['booked_by']}\n";
}

// 3. Test insert collision checking
$booking_date = '2026-07-15';
$title = 'Colombo Meeting with Director';
$booked_by = 'Administration Section';

// Insert first test record
$sql = "INSERT INTO bus_bookings (booking_date, title, booked_by) VALUES ('$booking_date', '$title', '$booked_by')";
if ($conn->query($sql) === TRUE) {
    $inserted_id = $conn->insert_id;
    echo "Inserted test booking successfully! ID: $inserted_id\n";
} else {
    echo "Failed to insert test booking: " . $conn->error . "\n";
}

// Attempt duplicate insert (should fail due to UNIQUE constraint)
$sql_dup = "INSERT INTO bus_bookings (booking_date, title, booked_by) VALUES ('$booking_date', 'Duplicate Trip', 'Welfare Dept')";
if ($conn->query($sql_dup) === TRUE) {
    echo "WARNING: Duplicate insert succeeded! Unique constraint might be missing.\n";
} else {
    echo "Success: Duplicate insert failed as expected: " . $conn->error . "\n";
}

// Clean up test booking
if (isset($inserted_id)) {
    $sql_del = "DELETE FROM bus_bookings WHERE id = $inserted_id";
    if ($conn->query($sql_del) === TRUE) {
        echo "Deleted test booking successfully. Cleanup done.\n";
    } else {
        echo "Failed to clean up test booking: " . $conn->error . "\n";
    }
}

$conn->close();
