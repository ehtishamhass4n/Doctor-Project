<?php
session_start();
include("./db/conection.php");

if (!isset($_SESSION['email']) || !isset($_SESSION['roll'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$role = $_SESSION['roll'];

// Fetch appointments depending on role with doctor information
if ($role === 'Doctor') {
    $sql = "SELECT a.*, d.name as doctor_name, d.specialization 
            FROM appointments a 
            LEFT JOIN doctors d ON a.doctor_id = d.id 
            WHERE a.doctor_email = ? 
            ORDER BY a.appointment_date ASC, a.appointment_time ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
} else {
    // Patient view: appointments booked by patient with doctor details
    $sql = "SELECT a.*, d.name as doctor_name, d.specialization, d.phone as doctor_phone
            FROM appointments a 
            LEFT JOIN doctors d ON a.doctor_id = d.id 
            WHERE a.patient_email = ? 
            ORDER BY a.appointment_date ASC, a.appointment_time ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
}
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Your Appointments - Patient Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
        }
        th, td {
            vertical-align: middle !important;
        }
        .status-badge {
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 15px;
            color: #fff;
        }
        .status-Pending {
            background-color: #f39c12;
        }
        .status-Confirmed {
            background-color: #27ae60;
        }
        .status-Completed {
            background-color: #2980b9;
        }
        .no-appointments {
            text-align: center;
            padding: 40px 0;
            color: #7f8c8d;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Appointments</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <?php if ($role === 'Doctor'): ?>
                        <th>Patient Name</th>
                        <th>Patient Email</th>
                        <th>Patient Phone</th>
                    <?php else: ?>
                        <th>Doctor Name</th>
                        <th>Specialization</th>
                        <th>Doctor Phone</th>
                    <?php endif; ?>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php $count=1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <?php if ($role === 'Doctor'): ?>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_phone'] ?? 'N/A'); ?></td>
                        <?php else: ?>
                            <td><?php echo htmlspecialchars($row['doctor_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['specialization'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['doctor_phone'] ?? 'N/A'); ?></td>
                        <?php endif; ?>
                        <td><?php echo date("F j, Y", strtotime($row['appointment_date'])); ?></td>
                        <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo htmlspecialchars($row['status']); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td><?php echo nl2br(htmlspecialchars($row['notes'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-appointments">You have no appointments yet.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
