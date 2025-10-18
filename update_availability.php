<?php
session_start();
include("./db/conection.php");

if (!isset($_SESSION['email']) || $_SESSION['roll'] !== 'Doctor') {
    header("Location: login.php");
    exit();
}

$doctor_email = $_SESSION['email'];
$message = "";

// Get doctor ID from profiles table
$doctor_sql = "SELECT * FROM profiles WHERE user_email = ? AND role = 'Doctor'";
$doctor_stmt = $conn->prepare($doctor_sql);
$doctor_stmt->bind_param("s", $doctor_email);
$doctor_stmt->execute();
$doctor_result = $doctor_stmt->get_result();

if ($doctor_result->num_rows == 0) {
    header("Location: create_profile.php");
    exit();
}

$doctor_profile = $doctor_result->fetch_assoc();

// Get doctor ID from doctors table
$doctors_sql = "SELECT id FROM doctors WHERE email = ?";
$doctors_stmt = $conn->prepare($doctors_sql);
$doctors_stmt->bind_param("s", $doctor_email);
$doctors_stmt->execute();
$doctors_result = $doctors_stmt->get_result();

if ($doctors_result->num_rows == 0) {
    $message = "Doctor profile not found in system. Please contact administrator.";
} else {
    $doctor_data = $doctors_result->fetch_assoc();
    $doctor_id = $doctor_data['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type = $_POST['availability_type'];
        $value = $_POST['availability_value'];
        $status = $_POST['status'];

        // Validate inputs (basic)
        if (!in_array($type, ['day', 'week', 'month']) || empty($value) || !in_array($status, ['Available', 'Not Available'])) {
            $message = "Invalid input.";
        } else {
            // Insert or update availability
            $sql = "INSERT INTO doctor_availability (doctor_email, doctor_id, availability_type, availability_value, status)
                    VALUES (?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE status = VALUES(status), updated_at = CURRENT_TIMESTAMP";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("siiss", $doctor_email, $doctor_id, $type, $value, $status);

            if ($stmt->execute()) {
                $message = "Availability updated successfully.";
            } else {
                $message = "Error updating availability: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Update Availability - Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Update Your Availability</h2>
    
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="availability_type" class="form-label">Availability Type</label>
            <select name="availability_type" id="availability_type" class="form-select" required onchange="updateInputPlaceholder()">
                <option value="" disabled selected>Select type</option>
                <option value="day">Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="availability_value" class="form-label" id="availability_label">Select Date</label>
            <input type="date" name="availability_value" id="availability_value" class="form-control" required />
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="Available">Available</option>
                <option value="Not Available">Not Available</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Availability</button>
    </form>
</div>

<script>
function updateInputPlaceholder() {
    const typeSelect = document.getElementById('availability_type');
    const input = document.getElementById('availability_value');
    const label = document.getElementById('availability_label');

    if (typeSelect.value === 'day') {
        input.type = 'date';
        input.placeholder = '';
        label.textContent = 'Select Date';
        input.value = '';
    } else if (typeSelect.value === 'week') {
        input.type = 'text';
        input.placeholder = 'Format: WW-YYYY (e.g. 42-2025)';
        label.textContent = 'Enter Week and Year';
        input.value = '';
    } else if (typeSelect.value === 'month') {
        input.type = 'text';
        input.placeholder = 'Format: MM-YYYY (e.g. 10-2025)';
        label.textContent = 'Enter Month and Year';
        input.value = '';
    }
}
</script>

</body>
</html>
