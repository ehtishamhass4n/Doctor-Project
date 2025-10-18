<?php
session_start();
include("./db/conection.php");

// Fetch doctors from profiles table where role = 'Doctor'
$doctors_sql = "SELECT * FROM profiles WHERE role = 'Doctor' AND (status = 'approved' OR status = 'created') ORDER BY specialization, name";
$doctors_result = mysqli_query($conn, $doctors_sql);
$doctors = [];
if ($doctors_result && mysqli_num_rows($doctors_result) > 0) {
    while ($row = mysqli_fetch_assoc($doctors_result)) {
        $doctors[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Our Doctors - Dr. Johnson</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicons -->
        <link href="img/favicon.ico" rel="icon">
        <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600|Nunito:600,700,800,900" rel="stylesheet"> 

        <!-- Bootstrap CSS File -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Libraries CSS Files -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="vendor/animate/animate.min.css" rel="stylesheet">
        <link href="vendor/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="vendor/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Main Stylesheet File -->
        <link href="css/hover-style.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        
        <style>
            .doctor-card {
                background: white;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 30px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                transition: transform 0.3s ease;
            }
            .doctor-card:hover {
                transform: translateY(-5px);
            }
            .doctor-image {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                object-fit: cover;
                margin: 0 auto 15px;
                display: block;
                border: 4px solid #1abc9c;
            }
            .specialization-badge {
                background: #1abc9c;
                color: white;
                padding: 5px 15px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: bold;
            }
            .experience-info {
                color: #666;
                font-size: 14px;
                margin: 10px 0;
            }
            .consultation-fee {
                color: #27ae60;
                font-weight: bold;
                font-size: 18px;
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
                    <h2>Our Medical Team</h2>
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
                        <li class="menu-active"><a href="service.php">Services</a></li>
                        <li><a href="booking.php">Booking</a></li>
                        <li class="menu-has-children"><a href="#">Pages</a>
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
            <!-- Doctors Section Start -->
            <section id="services">
                <div class="container">
                    <header class="section-header">
                        <h3>Our Expert Doctors</h3>
                        <p>Choose from our team of experienced medical professionals</p>
                    </header>
                    
                    <?php if (empty($doctors)): ?>
                        <div class="alert alert-info text-center">
                            <h4>No doctors available at the moment.</h4>
                            <p>Please check back later or contact our support team.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($doctors as $doctor): ?>
                                <div class="col-sm-6 col-md-4 col-lg-4">
                                    <div class="doctor-card text-center">
                                        <?php if (!empty($doctor['profile_pic'])): ?>
                                            <img src="uploads/profile_pics/<?php echo htmlspecialchars($doctor['profile_pic']); ?>" 
                                                 alt="<?php echo htmlspecialchars($doctor['name']); ?>" 
                                                 class="doctor-image">
                                        <?php else: ?>
                                            <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=120&h=120&fit=crop&crop=face" 
                                                 alt="<?php echo htmlspecialchars($doctor['name']); ?>" 
                                                 class="doctor-image">
                                        <?php endif; ?>
                                        
                                        <h4><?php echo htmlspecialchars($doctor['name']); ?></h4>
                                        <div class="specialization-badge"><?php echo htmlspecialchars($doctor['specialization']); ?></div>
                                        
                                        <div class="experience-info">
                                            <i class="fa fa-phone"></i> <?php echo htmlspecialchars($doctor['phone']); ?>
                                        </div>
                                        
                                        <div class="consultation-fee">
                                            <i class="fa fa-envelope"></i> <?php echo htmlspecialchars($doctor['user_email']); ?>
                                        </div>
                                        
                                        <?php if (!empty($doctor['address'])): ?>
                                            <p class="mt-3"><?php echo htmlspecialchars(substr($doctor['address'], 0, 100)) . '...'; ?></p>
                                        <?php endif; ?>
                                        
                                        <a href="doctor_profile.php?email=<?php echo urlencode($doctor['user_email']); ?>" class="btn btn-primary mt-2">
                                            <i class="fa fa-calendar-plus"></i> Book Appointment
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <!-- Doctors Section End -->
            
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
                        Need help? Call us 24/7 at +1-234-567-8900
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
                    <p>Designed By <a href="https://htmlcodex.com">HTML Codex</a></p>
                </div>
            </div>
        </footer>
        <!-- Footer end -->

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

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

        <!-- Main Javascript File -->
        <script src="js/main.js"></script>
    </body>
</html>