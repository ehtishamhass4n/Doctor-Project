<?php
include("./db/conection.php");

// Add doctor_id column to doctor_availability table
$alter_availability = "ALTER TABLE doctor_availability ADD COLUMN doctor_id INT";
if (mysqli_query($conn, $alter_availability)) {
    echo "Added doctor_id to doctor_availability table!<br>";
} else {
    echo "Error adding doctor_id column: " . mysqli_error($conn) . "<br>";
}

// Add some sample availability data for doctors
$availability_data = "INSERT INTO doctor_availability (doctor_email, doctor_id, availability_type, availability_value, status) VALUES 
('sarah.johnson@hospital.com', 1, 'day', '2024-01-15', 'Available'),
('sarah.johnson@hospital.com', 1, 'day', '2024-01-16', 'Available'),
('sarah.johnson@hospital.com', 1, 'day', '2024-01-17', 'Available'),
('michael.chen@hospital.com', 2, 'day', '2024-01-15', 'Available'),
('michael.chen@hospital.com', 2, 'day', '2024-01-16', 'Available'),
('emily.rodriguez@hospital.com', 3, 'day', '2024-01-15', 'Available'),
('emily.rodriguez@hospital.com', 3, 'day', '2024-01-17', 'Available'),
('david.thompson@hospital.com', 4, 'day', '2024-01-16', 'Available'),
('david.thompson@hospital.com', 4, 'day', '2024-01-18', 'Available'),
('lisa.wang@hospital.com', 5, 'day', '2024-01-15', 'Available'),
('lisa.wang@hospital.com', 5, 'day', '2024-01-19', 'Available'),
('james.wilson@hospital.com', 6, 'day', '2024-01-16', 'Available'),
('james.wilson@hospital.com', 6, 'day', '2024-01-20', 'Available')";

if (mysqli_query($conn, $availability_data)) {
    echo "Sample availability data inserted!<br>";
} else {
    echo "Error inserting availability data: " . mysqli_error($conn) . "<br>";
}

echo "Database fixes completed! Now doctor profiles should work properly.";
?>
