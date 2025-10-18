<?php
session_start();
include("./db/conection.php");

if (!isset($_SESSION['email']) || !isset($_SESSION['roll']) || $_SESSION['roll'] != 'Doctor') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$role = $_SESSION['roll'];

$sql = "SELECT * FROM profiles WHERE user_email='$email' AND role='$role'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: create_profile.php");
    exit();
}

$profile = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Doctor Dashboard</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, Dr. <?php echo htmlspecialchars($profile['name']); ?></h1>

        <?php if ($profile['status'] == 'pending'): ?>
            <div class="alert alert-warning">
                Your profile is pending approval. Please wait for confirmation.
            </div>
        <?php else: ?>
            <?php if (!empty($profile['profile_pic'])): ?>
                <img src="uploads/profile_pics/<?php echo htmlspecialchars($profile['profile_pic']); ?>" 
                     alt="Profile Picture" style="max-width:150px; max-height:150px;">
            <?php endif; ?>

            <h3>Your Profile Details:</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($profile['name']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile['phone']); ?></p>
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($profile['specialization']); ?></p>

            <a href="view_profile.php" class="btn btn-info">View Profile</a>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            <a href="view_appointments.php" class="btn btn-success">View Appointments</a>
            <a href="update_availability.php" class="btn btn-warning">Update Availability</a>
        <?php endif; ?>

        <hr>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
