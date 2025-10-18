<?php
include("./db/conection.php");

// Add missing columns to appointments table
$alter_appointments = "ALTER TABLE appointments ADD COLUMN patient_email VARCHAR(100)";
if (mysqli_query($conn, $alter_appointments)) {
    echo "Added patient_email column to appointments table!<br>";
} else {
    echo "Error adding patient_email column: " . mysqli_error($conn) . "<br>";
}

$alter_appointments2 = "ALTER TABLE appointments ADD COLUMN patient_phone VARCHAR(20)";
if (mysqli_query($conn, $alter_appointments2)) {
    echo "Added patient_phone column to appointments table!<br>";
} else {
    echo "Error adding patient_phone column: " . mysqli_error($conn) . "<br>";
}

// Check current appointments table structure
$check_structure = "DESCRIBE appointments";
$result = mysqli_query($conn, $check_structure);
echo "<h3>Current Appointments Table Structure:</h3>";
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . $row['Default'] . "</td>";
    echo "<td>" . $row['Extra'] . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<br><strong>Appointments table structure updated! Now booking should work.</strong>";
?>
