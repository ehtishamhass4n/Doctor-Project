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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);   // <-- Added this
    $specialization = $role == 'Doctor' ? trim($_POST['specialization']) : NULL;

    // Handle image upload if exists
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
                $profile_pic = $newFileName;
            } else {
                $error = "Error moving uploaded file.";
            }
        } else {
            $error = "Invalid file type. Only jpg, jpeg, png, gif allowed.";
        }
    }

    if (empty($error)) {
        // Check if profile already exists
        $check_sql = "SELECT * FROM profiles WHERE user_email=? AND role=?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("ss", $email, $role);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $error = "Profile already created. You can edit it from the dashboard.";
        } else {
            // Insert new profile with address
            $stmt = $conn->prepare("INSERT INTO profiles (user_email, role, status, name, phone, address, specialization, profile_pic) VALUES (?, ?, 'created', ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $email, $role, $name, $phone, $address, $specialization, $profile_pic);
            
            if ($stmt->execute()) {
                // Redirect to home page after successful profile creation
                header("Location: index.php");
                exit();
            } else {
                $error = "Error creating profile. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create Profile - Patient Care</title>
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
    </style>
</head>
<body>

<div class="profile-form shadow-sm">
    <h2>Create Your Profile</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" required value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter your phone number" required value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
        </div>

        <!-- Address Field Added -->
        <div class="mb-3">
            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
            <input type="text" name="address" id="address" class="form-control" placeholder="Enter your address" required value="<?php echo isset($address) ? htmlspecialchars($address) : ''; ?>">
        </div>

        <?php if ($role == 'Doctor'): ?>
        <div class="mb-3">
            <label for="specialization" class="form-label">Specialization <span class="text-danger">*</span></label>
            <input type="text" name="specialization" id="specialization" class="form-control" placeholder="e.g., Cardiologist" required value="<?php echo isset($specialization) ? htmlspecialchars($specialization) : ''; ?>">
        </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="profile_pic" class="form-label">Profile Picture (optional)</label>
            <input type="file" name="profile_pic" id="profile_pic" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary w-100">Create Profile</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
