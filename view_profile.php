<?php
session_start();
include("./db/conection.php");

if (!isset($_SESSION['email']) || !isset($_SESSION['roll'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$role = $_SESSION['roll'];

// Fetch profile data
$sql = "SELECT * FROM profiles WHERE user_email=? AND role=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // No profile found - redirect to create profile page
    header("Location: create_profile.php");
    exit();
}

$profile = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Profile - Patient Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .profile-view {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .profile-pic {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #1abc9c;
            margin-bottom: 20px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .profile-info {
            text-align: left;
            font-size: 1.1rem;
        }
        .profile-info strong {
            color: #16a085;
        }
        .btn-edit {
            margin-top: 25px;
        }
    </style>
</head>
<body>

<div class="profile-view shadow-sm">
    <h2>Your Profile</h2>

    <?php if (!empty($profile['profile_pic']) && file_exists('./uploads/profile_pics/' . $profile['profile_pic'])): ?>
        <img src="<?php echo './uploads/profile_pics/' . htmlspecialchars($profile['profile_pic']); ?>" alt="Profile Picture" class="profile-pic">
    <?php else: ?>
        <img src="https://via.placeholder.com/140?text=No+Image" alt="No Image" class="profile-pic">
    <?php endif; ?>

    <div class="profile-info">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($profile['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($profile['user_email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile['phone']); ?></p>
        <?php if ($role == 'Doctor'): ?>
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($profile['specialization']); ?></p>
        <?php endif; ?>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($profile['status']); ?></p>
    </div>

    <div class="btn-group-vertical w-100 mt-3" role="group">
        <?php if ($role == 'Doctor'): ?>
            <a href="update_availability.php" class="btn btn-warning mb-2">Update Availability</a>
            <a href="view_appointments.php" class="btn btn-success mb-2">View Appointments</a>
        <?php else: ?>
            <a href="view_appointments.php" class="btn btn-success mb-2">View Appointments</a>
        <?php endif; ?>
        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
