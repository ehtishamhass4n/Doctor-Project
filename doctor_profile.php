<?php
session_start();
include("./db/conection.php");

// Get doctor email from URL
$doctor_email = isset($_GET['email']) ? $_GET['email'] : '';

if (empty($doctor_email)) {
    header("Location: service.php");
    exit();
}

// Fetch doctor details from profiles table
$sql = "SELECT * FROM profiles WHERE user_email = ? AND role = 'Doctor'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctor_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: service.php");
    exit();
}

$doctor = $result->fetch_assoc();

// Handle appointment booking
$booking_message = "";
$booking_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $patient_name = $_POST['patient_name'];
    $patient_email = $_POST['patient_email'];
    $patient_phone = $_POST['patient_phone'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = $_POST['notes'];
    
    // Check if doctor is available on this date/time
    $availability_sql = "SELECT * FROM doctor_availability WHERE doctor_email = ? AND availability_value = ? AND status = 'Available'";
    $avail_stmt = $conn->prepare($availability_sql);
    $avail_stmt->bind_param("ss", $doctor_email, $appointment_date);
    $avail_stmt->execute();
    $avail_result = $avail_stmt->get_result();
    
    if ($avail_result->num_rows > 0) {
        // Insert appointment
        $insert_sql = "INSERT INTO appointments (patient_name, patient_email, patient_phone, doctor_email, appointment_date, appointment_time, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssssss", $patient_name, $patient_email, $patient_phone, $doctor_email, $appointment_date, $appointment_time, $notes);
        
        if ($insert_stmt->execute()) {
            $booking_success = true;
            $booking_message = "Appointment booked successfully! You will receive a confirmation email shortly.";
        } else {
            $booking_message = "Failed to book appointment. Please try again.";
        }
    } else {
        $booking_message = "Doctor is not available on the selected date. Please choose another date.";
    }
}

// Fetch doctor's availability
$availability_sql = "SELECT * FROM doctor_availability WHERE doctor_email = ? AND status = 'Available' ORDER BY availability_value";
$avail_stmt = $conn->prepare($availability_sql);
$avail_stmt->bind_param("s", $doctor_email);
$avail_stmt->execute();
$availability_result = $avail_stmt->get_result();
$available_dates = [];
while ($row = $availability_result->fetch_assoc()) {
    $available_dates[] = $row['availability_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($doctor['name']); ?> - Book Appointment</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Bootstrap CSS File -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        .doctor-profile {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .doctor-image-large {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #1abc9c;
            margin: 0 auto;
            display: block;
        }
        .specialization-badge {
            background: linear-gradient(45deg, #1abc9c, #16a085);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .booking-form {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-top: 20px;
        }
        .availability-calendar {
            background: #e8f5e8;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .available-date {
            background: #27ae60;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Top Header Start -->
    <section class="banner-header">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h1><a href="index.php">Dr. Johnson</a></h1>
                </div>
            </div>
            <div class="col-md-12">
                <h2>Book Appointment</h2>
            </div>
        </div>
    </section>
    <!-- Top Header End -->

    <!-- Header Start -->
    <header id="header">
        <div class="container">
            <nav id="nav-menu-container">
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="service.php">Services</a></li>
                    <li><a href="booking.php">Booking</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['email'])): ?>
                        <li class="menu-has-children"><a href="#"><i class="fa fa-user"></i></a>
                            <ul>
                                <li><a href="view_profile.php">View Profile</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <!-- Header End -->

    <main id="main">
        <div class="container">
            <div class="row">
                <!-- Doctor Profile -->
                <div class="col-md-4">
                    <div class="doctor-profile text-center">
                        <?php if (!empty($doctor['profile_pic'])): ?>
                            <img src="uploads/profile_pics/<?php echo htmlspecialchars($doctor['profile_pic']); ?>" 
                                 alt="<?php echo htmlspecialchars($doctor['name']); ?>" 
                                 class="doctor-image-large">
                        <?php else: ?>
                            <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=200&h=200&fit=crop&crop=face" 
                                 alt="<?php echo htmlspecialchars($doctor['name']); ?>" 
                                 class="doctor-image-large">
                        <?php endif; ?>
                        
                        <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                        <div class="specialization-badge"><?php echo htmlspecialchars($doctor['specialization']); ?></div>
                        
                        <div class="mt-3">
                            <p><i class="fa fa-envelope"></i> <strong><?php echo htmlspecialchars($doctor['user_email']); ?></strong></p>
                            <p><i class="fa fa-phone"></i> <?php echo htmlspecialchars($doctor['phone']); ?></p>
                            <p><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($doctor['address']); ?></p>
                        </div>
                        
                        <div class="mt-3">
                            <h5>About Doctor</h5>
                            <p><?php echo htmlspecialchars($doctor['address'] ?: 'Professional medical services'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Form -->
                <div class="col-md-8">
                    <?php if ($booking_message): ?>
                        <div class="alert <?php echo $booking_success ? 'alert-success' : 'alert-danger'; ?>">
                            <?php echo htmlspecialchars($booking_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Availability Calendar -->
                    <div class="availability-calendar">
                        <h4><i class="fa fa-calendar-check"></i> Available Dates</h4>
                        <?php if (empty($available_dates)): ?>
                            <p class="text-muted">No available dates at the moment. Please contact the doctor directly.</p>
                        <?php else: ?>
                            <p>Doctor is available on the following dates:</p>
                            <?php foreach ($available_dates as $date): ?>
                                <span class="available-date"><?php echo date('M d, Y', strtotime($date)); ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Booking Form -->
                    <div class="booking-form">
                        <h4><i class="fa fa-calendar-plus"></i> Book Your Appointment</h4>
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Your Name *</label>
                                        <input type="text" class="form-control" name="patient_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Your Email *</label>
                                        <input type="email" class="form-control" name="patient_email" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number *</label>
                                        <input type="tel" class="form-control" name="patient_phone" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Preferred Date *</label>
                                        <input type="date" class="form-control" name="appointment_date" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Preferred Time *</label>
                                        <select class="form-control" name="appointment_time" required>
                                            <option value="">Select Time</option>
                                            <option value="09:00">9:00 AM</option>
                                            <option value="10:00">10:00 AM</option>
                                            <option value="11:00">11:00 AM</option>
                                            <option value="12:00">12:00 PM</option>
                                            <option value="14:00">2:00 PM</option>
                                            <option value="15:00">3:00 PM</option>
                                            <option value="16:00">4:00 PM</option>
                                            <option value="17:00">5:00 PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Special Notes</label>
                                        <textarea class="form-control" name="notes" rows="3" placeholder="Any special requirements or symptoms..."></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center mt-3">
                                <button type="submit" name="book_appointment" class="btn btn-primary btn-lg">
                                    <i class="fa fa-calendar-check"></i> Book Appointment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Start -->
    <footer id="footer">
        <div class="container">
            <div class="copyright">
                <p>&copy; Copyright <a href="#">Your Site Name</a>. All Rights Reserved</p>
                <p>Designed By <a href="https://htmlcodex.com">HTML Codex</a></p>
            </div>
        </div>
    </footer>
    <!-- Footer end -->

    <!-- JavaScript Libraries -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
