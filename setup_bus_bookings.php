<?php
require_once 'db_config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

// 1. Create table bus_bookings
$sql = "CREATE TABLE IF NOT EXISTS bus_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_date DATE UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL, -- Purpose/Destination
    booked_by VARCHAR(100) NOT NULL, -- Booked division/officer
    status ENUM('approved', 'pending') DEFAULT 'approved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql) === TRUE) {
    echo "Table 'bus_bookings' created successfully.\n";
} else {
    die("Error creating table: " . $conn->error . "\n");
}

// 2. Seed mock records
$bookings = [
    ['2026-06-08', 'Elders Home Field Visit', 'Elderly Care Division'],
    ['2026-06-18', 'Vocational Trainees Transport', 'Rehabilitation Division'],
    ['2026-06-25', 'Provincial Welfare Inspection Tour', "Director's Office"]
];

foreach ($bookings as $b) {
    $date = $conn->real_escape_string($b[0]);
    $title = $conn->real_escape_string($b[1]);
    $by = $conn->real_escape_string($b[2]);

    $insert_sql = "INSERT INTO bus_bookings (booking_date, title, booked_by) VALUES ('$date', '$title', '$by') 
                   ON DUPLICATE KEY UPDATE title='$title', booked_by='$by'";
    if ($conn->query($insert_sql) === TRUE) {
        echo "Seeded booking for $date successfully.\n";
    } else {
        echo "Error seeding booking for $date: " . $conn->error . "\n";
    }
}

$conn->close();
