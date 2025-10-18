<?php
session_start();
include("./db/conection.php");

// Check if user is logged in and is a Patient
if (!isset($_SESSION['email']) || !isset($_SESSION['roll']) || $_SESSION['roll'] != 'Patient') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$role = $_SESSION['roll'];

// Fetch profile from DB
$sql = "SELECT * FROM profiles WHERE user_email=? AND role=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Profile not created - redirect to create profile page
    header("Location: create_profile.php");
    exit();
}

$profile = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Patient Dashboard</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, <?php echo htmlspecialchars($profile['name']); ?></h1>

        <?php if ($profile['status'] == 'pending'): ?>
            <div class="alert alert-warning">
                Your profile is pending approval. Please wait for confirmation.
            </div>
        <?php else: ?>
            <!-- Profile Picture -->
            <?php if (!empty($profile['profile_pic'])): ?>
                <img src="uploads/profile_pics/<?php echo htmlspecialchars($profile['profile_pic']); ?>" 
                     alt="Profile Picture" style="max-width:150px; max-height:150px;">
            <?php endif; ?>

            <h3>Your Profile Details:</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($profile['name']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile['phone']); ?></p>
            <p><strong>Address:</strong> 
                <?php 
                    echo !empty($profile['address']) ? htmlspecialchars($profile['address']) : '<em>Not provided</em>'; 
                ?>
            </p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($profile['role']); ?></p>

            <a href="view_profile.php" class="btn btn-info">View Profile</a>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            <a href="view_appointments.php" class="btn btn-success">View Appointments</a>
            <a href="booking.php" class="btn btn-warning">Book Appointment</a>
        <?php endif; ?>

        <hr>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
