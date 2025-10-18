<?php
session_start();
include("./db/conection.php");
if (isset($_POST["bookingus"])) {
    $_name = $_POST["n"];
    $_email = $_POST["e"];
    $_mobile = $_POST["m"];
    $_doctor_email = $_POST["s"]; // This is now doctor email
    $_date = $_POST["d"];
    $_time = $_POST["t"];
    $_request = $_POST["r"];
   
    // Insert into appointments table instead of booking table
    $query = "INSERT INTO appointments(patient_name, patient_email, patient_phone, doctor_email, appointment_date, appointment_time, notes, status) VALUES ('$_name','$_email','$_mobile','$_doctor_email','$_date','$_time','$_request','Pending')";
    $res = mysqli_query($conn, $query);

    if ($res != null) {
        $booking_success = true;
        $booking_data = [
            'name' => $_name,
            'email' => $_email,
            'mobile' => $_mobile,
            'doctor_email' => $_doctor_email,
            'date' => $_date,
            'time' => $_time,
            'request' => $_request
        ];
    } else {
        $booking_error = 'Booking failed.';
    }
}




?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Dr. Johnson</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <link href="img/favicon.ico" rel="icon">
        <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600|Nunito:600,700,800,900" rel="stylesheet"> 

        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="vendor/animate/animate.min.css" rel="stylesheet">
        <link href="vendor/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="vendor/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="vendor/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

        <link href="css/hover-style.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>
        <section class="banner-header">
            <div class="container text-center">
                        <div class="top-buttons text-right">
    
</div>
                
                <div class="row">
                    <div class="col-md-12">
                        <h1><a href="index.php">Dr. Johnson</a></h1>
                        <!-- <a class="brand" href="index.html" title="Home"><img alt="Logo" src="img/logo.png"></a> -->
                    </div>
                </div>

                <div class="col-md-12">
                    <h2>Your Family Doctor</h2>
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
                        <li class="menu-active"><a href="booking.php">Booking</a></li>
                        <li class="menu-has-children"><a href="#">pages</a>
                            <ul>
                                <li><a href="login.php">Login</a></li>
                                <li class="menu-has-children"><a href="#">Drop Down</a>
                                    <ul>
                                        <li><a href="#">Drop Down 1</a></li>
                                        <li><a href="#">Drop Down 2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <!-- Header End -->

        <main id="main">

            <!-- Booking Section Start -->
            <section id="booking">
                <div class="container">
                    <?php if (!empty($booking_success)): ?>
                        <div class="alert alert-success" role="alert">Booking submitted successfully.</div>
                    <?php elseif (!empty($booking_error)): ?>
                        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($booking_error); ?></div>
                    <?php endif; ?>
                    <div class="section-header">
                        <h3>Book for Getting Services</h3>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="booking-form">
                                <form method="post">
                                    <div class="form-row">
                                        <div class="control-group col-sm-6">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" name="n" placeholder="E.g. John Sina" required="required" />
                                        </div>
                                        <div class="control-group col-sm-6">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="e" placeholder="E.g. email@example.com" required="required" />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="control-group col-sm-6">
                                            <label>Mobile</label>
                                            <input type="text" class="form-control" name="m" placeholder="E.g. +1 234 567 8900" required="required" />
                                        </div>
                                        <div class="control-group col-sm-6" >
                                            <label>Select a Doctor</label>
                                            <select class="custom-select" name="s" required>
                                                <option value="">Choose a Doctor</option>
                                                <?php
                                                // Fetch doctors from profiles table
                                                $doctors_sql = "SELECT * FROM profiles WHERE role = 'Doctor' AND (status = 'approved' OR status = 'created') ORDER BY specialization, name";
                                                $doctors_result = mysqli_query($conn, $doctors_sql);
                                                if ($doctors_result && mysqli_num_rows($doctors_result) > 0) {
                                                    while ($doctor = mysqli_fetch_assoc($doctors_result)) {
                                                        echo '<option value="' . htmlspecialchars($doctor['user_email']) . '">' . 
                                                             htmlspecialchars($doctor['name']) . ' - ' . 
                                                             htmlspecialchars($doctor['specialization']) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="control-group col-sm-6" name="">
                                            <label>Appointment Date</label>
                                            <input type="text" class="form-control datetimepicker-input" id="date" data-toggle="datetimepicker" data-target="#date" name="d" placeholder="E.g. MM/DD/YYYY" required="required" />
                                        </div>
                                        <div class="control-group col-sm-6">
                                            <label>Appointment Time</label>
                                            <input type="text" class="form-control datetimepicker-input" id="time" data-toggle="datetimepicker" data-target="#time" name="t" placeholder="E.g. HH:MM AM" required="required" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label>Special Request</label>
                                        <input type="text" class="form-control" name="r" placeholder="E.g. Special Request" required="required" />
                                    </div>
                                    <div class="button"><button type="submit" name="bookingus">Book Now</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($booking_success) && !empty($booking_data)): ?>
                    <div class="row mt-4">
                        <div class="col-md-8 mx-auto">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Booking Summary</h5>
                                    <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($booking_data['name']); ?></p>
                                    <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($booking_data['email']); ?></p>
                                    <p class="mb-1"><strong>Mobile:</strong> <?php echo htmlspecialchars($booking_data['mobile']); ?></p>
                                    <p class="mb-1"><strong>Doctor:</strong> <?php echo htmlspecialchars($booking_data['doctor_email']); ?></p>
                                    <p class="mb-1"><strong>Date:</strong> <?php echo htmlspecialchars($booking_data['date']); ?></p>
                                    <p class="mb-1"><strong>Time:</strong> <?php echo htmlspecialchars($booking_data['time']); ?></p>
                                    <p class="mb-0"><strong>Special Request:</strong> <?php echo htmlspecialchars($booking_data['request']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            <!-- Booking Section End -->
            
            <!-- Subscriber Section Start -->
            <section id="subscriber">
                <div class="container">
                    <h3>Get Free Consultation</h3>
                    <form class="form-inline">
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Your Email Goes Here">
                        </div>
                        <button type="submit" class="btn">Submit</button>
                    </form>
                </div>
            </section>
            <!-- Subscriber Section end -->
            
            <!-- Support Section Start -->
            <section id="support" class="wow fadeInUp">
                <div class="container">
                    <h1>
                        Need help? Call me 24/7 at +1-234-567-8900
                    </h1>
                </div>
            </section>
            <!-- Support Section end -->

        </main>

        <!-- Footer Start -->
        <footer id="footer">
            <div class="container">
                <div class="copyright">
					<p>&copy; Copyright <a href="#">Your Site Name</a>. All Rights Reserved</p>
								
					<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
					<p>Designed By <a href="https://htmlcodex.com">HTML Codex</a></p>
                </div>
            </div>
        </footer>
        <!-- Footer end -->

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
        <!-- Uncomment below i you want to use a preloader -->
        <!-- <div id="preloader"></div> -->

        <!-- JavaScript Libraries -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/jquery/jquery-migrate.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/easing/easing.min.js"></script>
        <script src="vendor/stickyjs/sticky.js"></script>
        <script src="vendor/superfish/hoverIntent.js"></script>
        <script src="vendor/superfish/superfish.min.js"></script>
        <script src="vendor/owlcarousel/owl.carousel.min.js"></script>
        <script src="vendor/touchSwipe/jquery.touchSwipe.min.js"></script>
        <script src="vendor/tempusdominus/js/moment.min.js"></script>
        <script src="vendor/tempusdominus/js/moment-timezone.min.js"></script>
        <script src="vendor/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

        <!-- Main Javascript File -->
        <script src="js/main.js"></script>

    </body>
</html>
