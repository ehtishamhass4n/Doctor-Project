<?php
session_start();
include("./db/conection.php");

if (!isset($_SESSION['email']) || !isset($_SESSION['roll'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$role = $_SESSION['roll'];

$error = '';
$success = '';
$profile_pic = NULL;

// Fetch existing profile data
$sql = "SELECT * FROM profiles WHERE user_email=? AND role=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // No profile found, redirect to create profile page or show message
    header("Location: create_profile.php");
    exit();
}

$profile = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $specialization = $role == 'Doctor' ? trim($_POST['specialization']) : NULL;

    // Handle image upload if new file uploaded
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadDir = './uploads/profile_pics/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Delete old picture if exists
                if (!empty($profile['profile_pic']) && file_exists($uploadDir . $profile['profile_pic'])) {
                    unlink($uploadDir . $profile['profile_pic']);
                }
                $profile_pic = $newFileName;
            } else {
                $error = "Error moving uploaded file.";
            }
        } else {
            $error = "Invalid file type. Only jpg, jpeg, png, gif allowed.";
        }
    } else {
        // No new upload, keep old picture
        $profile_pic = $profile['profile_pic'];
    }

    if (empty($error)) {
        $update_sql = "UPDATE profiles SET name=?, phone=?, specialization=?, profile_pic=? WHERE user_email=? AND role=?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("ssssss", $name, $phone, $specialization, $profile_pic, $email, $role);
        if ($stmt_update->execute()) {
            $success = "Profile updated successfully.";
            // Refresh profile info
            $profile['name'] = $name;
            $profile['phone'] = $phone;
            $profile['specialization'] = $specialization;
            $profile['profile_pic'] = $profile_pic;
        } else {
            $error = "Failed to update profile.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Profile - Patient Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .profile-form {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .profile-form h2 {
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .btn-primary {
            background-color: #1abc9c;
            border: none;
        }
        .btn-primary:hover {
            background-color: #16a085;
        }
        .form-label {
            font-weight: 600;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 2px solid #1abc9c;
        }
    </style>
</head>
<body>

<div class="profile-form shadow-sm">
    <h2>Edit Your Profile</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (!empty($profile['profile_pic']) && file_exists('./uploads/profile_pics/' . $profile['profile_pic'])): ?>
        <img src="<?php echo './uploads/profile_pics/' . htmlspecialchars($profile['profile_pic']); ?>" alt="Profile Picture" class="profile-pic">
    <?php else: ?>
        <img src="https://via.placeholder.com/120?text=No+Image" alt="No Image" class="profile-pic">
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required value="<?php echo htmlspecialchars($profile['name']); ?>">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input type="tel" name="phone" id="phone" class="form-control" required value="<?php echo htmlspecialchars($profile['phone']); ?>">
        </div>

        <?php if ($role == 'Doctor'): ?>
        <div class="mb-3">
            <label for="specialization" class="form-label">Specialization <span class="text-danger">*</span></label>
            <input type="text" name="specialization" id="specialization" class="form-control" required value="<?php echo htmlspecialchars($profile['specialization']); ?>">
        </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="profile_pic" class="form-label">Change Profile Picture (optional)</label>
            <input type="file" name="profile_pic" id="profile_pic" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
