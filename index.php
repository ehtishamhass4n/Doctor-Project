<?php
session_start();
include("./db/conection.php");
if (isset($_POST["data"])) {
    $_n = $_POST["N"];
    $_e = $_POST["E"];
    $_s = $_POST["S"];
    $_m = $_POST["M"];
   
 $query ="INSERT INTO contact(name,email,subject,message) values ('$_n','$_e','$_s','$_m')";
    $res=mysqli_query($conn ,$query);

    if ($res!=null) {
        echo "Send sucessfull.";
    } else {
        echo "Send failed.";
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
    </head>

    <body>
        <!-- Top Header Start -->
        <section class="top-header">
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
                    <?php if (!isset($_SESSION['email'])): ?>
                    <a href="signup.php" class="btn btn-outline-light btn-sm">Signup</a>
                    <a href="login.php" class="btn btn-light btn-sm ml-2">login</a>
                    <?php endif; ?>
</div>
            </div>
        </section>
        <!-- Top Header End -->

        <!-- Header Start -->
        <header id="header">
            <div class="container">
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li class="menu-active"><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="service.php">Services</a></li>
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

            <!-- About Section Start-->
            <section id="about">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 col-md-6">
                            <div class="about-col-left">
                                <img class="img-fluid" src="img/about-us.jpg" />
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-6">
                            <div class="about-col-right">
                                <header class="section-header">
                                    <h3>About Dr. Johnson</h3>
                                </header>
                                <ul class="icon">
                                    <li><a href="#" class="fa fa-twitter"></a></li>
                                    <li><a href="#" class="fa fa-facebook"></a></li>
                                    <li><a href="#" class="fa fa-pinterest"></a></li>
                                    <li><a href="#" class="fa fa-google-plus"></a></li>
                                </ul>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam convallis quam sed tincidunt accumsan. Aliquam at tincidunt tortor, ac porta turpis. Curabitur lacinia venenatis semper.
                                </p>
                                <p>
                                    Aliquam ut nibh ut lacus posuere facilisis. Vestibulum ullamcorper arcu et bibendum ultrices. Suspendisse rutrum turpis vitae.
                                </p>
                                <a href="about.php">Read More</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="about-col">
                                <h4>Education</h4>
                                <p>Medical School - University of Dulton Health Science Center.</p>
                                <p>Residency in Family Medicine - University of Dulton Health Science Center.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="about-col">
                                <h4>Experience</h4>
                                <p>Medical School - University of Dulton Health Science Center.</p>
                                <p>Residency in Family Medicine - University of Dulton Health Science Center.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- About Section End-->

            <!-- Services Section Start -->
           <section id="services">
  <div class="container">
    <header class="section-header">
      <h3>Our Expert Doctors</h3>
      <p>Choose from our team of experienced medical professionals</p>
    </header>

    <?php
    // Fetch doctors from profiles table where role = 'Doctor'
    $doctors_sql = "SELECT * FROM profiles WHERE role = 'Doctor' AND (status = 'approved' OR status = 'created') ORDER BY specialization, name LIMIT 6";
    $doctors_result = mysqli_query($conn, $doctors_sql);
    $doctors = [];
    if ($doctors_result && mysqli_num_rows($doctors_result) > 0) {
        while ($row = mysqli_fetch_assoc($doctors_result)) {
            $doctors[] = $row;
        }
    }
    ?>

    <div class="row">
      <?php if (empty($doctors)): ?>
        <div class="col-12">
          <div class="alert alert-info text-center">
            <h4>No doctors available at the moment.</h4>
            <p>Please check back later or contact our support team.</p>
          </div>
        </div>
      <?php else: ?>
        <?php foreach ($doctors as $doctor): ?>
          <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
            <div class="single-service card p-3 shadow-sm">
              <?php if (!empty($doctor['profile_pic'])): ?>
                <img src="uploads/profile_pics/<?php echo htmlspecialchars($doctor['profile_pic']); ?>" 
                     alt="<?php echo htmlspecialchars($doctor['name']); ?>" 
                     class="card-img-top mb-3" style="height: 200px; object-fit: cover;">
              <?php else: ?>
                <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=300&h=200&fit=crop&crop=face" 
                     alt="<?php echo htmlspecialchars($doctor['name']); ?>" 
                     class="card-img-top mb-3" style="height: 200px; object-fit: cover;">
              <?php endif; ?>
              
              <h4><?php echo htmlspecialchars($doctor['name']); ?></h4>
              <span><?php echo htmlspecialchars($doctor['specialization']); ?> | Contact: <?php echo htmlspecialchars($doctor['phone']); ?></span>
              <p><?php echo htmlspecialchars($doctor['address'] ? substr($doctor['address'], 0, 80) . '...' : 'Professional medical services'); ?></p>
              <a href="doctor_profile.php?email=<?php echo urlencode($doctor['user_email']); ?>" class="btn btn-primary mt-2">Book Appointment</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Service Section End-->

            <!-- Team Section Start -->
            <section id="team">
                <div class="container">
                    <div class="section-header">
                        <h3>Meet My Assistant</h3>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="box8">
                                <img src="img/team-1.jpg" alt="">
                                <div class="box-content">
                                    <ul class="icon">
                                        <li><a href="#" class="fa fa-twitter"></a></li>
                                        <li><a href="#" class="fa fa-facebook"></a></li>
                                        <li><a href="#" class="fa fa-pinterest"></a></li>
                                        <li><a href="#" class="fa fa-google-plus"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <h4>Maureen L. Reidy</h4>
                            <span>Assistant Nurse</span>
                            <p>
                                Lorem ipsum dolor sit amet adipiscing elit. Proin consequat cursus sit amet elit proin consequat.
                            </p>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="box8">
                                <img src="img/team-2.jpg" alt="">
                                <div class="box-content">
                                    <ul class="icon">
                                        <li><a href="#" class="fa fa-twitter"></a></li>
                                        <li><a href="#" class="fa fa-facebook"></a></li>
                                        <li><a href="#" class="fa fa-pinterest"></a></li>
                                        <li><a href="#" class="fa fa-google-plus"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <h4>Janelle J. Hittle</h4>
                            <span>Assistant Nurse</span>
                            <p>
                                Lorem ipsum dolor sit amet adipiscing elit. Proin consequat cursus sit amet elit proin consequat.
                            </p>                     
                        </div>

                        <div class="col-md-4">
                            <div class="box8">
                                <img src="img/team-3.jpg" alt="">
                                <div class="box-content">
                                    <ul class="icon">
                                        <li><a href="#" class="fa fa-twitter"></a></li>
                                        <li><a href="#" class="fa fa-facebook"></a></li>
                                        <li><a href="#" class="fa fa-pinterest"></a></li>
                                        <li><a href="#" class="fa fa-google-plus"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <h4>Michael C. Powell</h4>
                            <span>Assistant Nurse</span>
                            <p>
                                Lorem ipsum dolor sit amet adipiscing elit. Proin consequat cursus sit amet elit proin consequat.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Team Section End -->
            
            <!-- Testimonials Section Start -->
            <section id="testimonials" class="section-bg wow fadeInUp">
                <div class="container">
                    <div class="section-header">
                        <h3>Happy Client</h3>
                    </div>
                    
                    <div class="owl-carousel testimonials-carousel">
                        <div class="row testimonial-item">
                            <div class="col-sm-4">
                                <div class="box8">
                                    <img src="img/testimonial-1.jpg" class="testimonial-img" alt="">
                                    <div class="box-content">
                                        <ul class="icon">
                                            <li><a href="#" class="fa fa-twitter"></a></li>
                                            <li><a href="#" class="fa fa-facebook"></a></li>
                                            <li><a href="#" class="fa fa-pinterest"></a></li>
                                            <li><a href="#" class="fa fa-google-plus"></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="content">
                                    <h3>Jamie D. Boyd</h3>
                                    <h4>Oral Radiologist</h4>
                                    <p>
                                        <i class="fa fa-quote-left"></i>
                                        Commodo sed hendrerit id, posuere tempus odio. Phasellus vel leo aliquam, interdum massa quis, aliquam sapien. Aliquam erat volutpat. Etiam nec feugiat libero. Phasellus in ipsum nunc.
                                        <i class="fa fa-quote-right"></i>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row testimonial-item">
                            <div class="col-sm-4">
                                <div class="box8">
                                    <img src="img/testimonial-2.jpg" class="testimonial-img" alt="">
                                    <div class="box-content">
                                        <ul class="icon">
                                            <li><a href="#" class="fa fa-twitter"></a></li>
                                            <li><a href="#" class="fa fa-facebook"></a></li>
                                            <li><a href="#" class="fa fa-pinterest"></a></li>
                                            <li><a href="#" class="fa fa-google-plus"></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="content">
                                    <h3>Albert J. Cerrato</h3>
                                    <h4>Craft Artist</h4>
                                    <p>
                                        <i class="fa fa-quote-left"></i>
                                        Proin ut dui dictum ligula condimentum cursus. Ut orci arcu, commodo sed hendrerit id, posuere tempus odio. Phasellus vel leo aliquam, interdum massa quis, aliquam sapien. Aliquam erat volutpat
                                        <i class="fa fa-quote-right"></i>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row testimonial-item">
                            <div class="col-sm-4">
                                <div class="box8">
                                    <img src="img/testimonial-3.jpg" class="testimonial-img" alt="">
                                    <div class="box-content">
                                        <ul class="icon">
                                            <li><a href="#" class="fa fa-twitter"></a></li>
                                            <li><a href="#" class="fa fa-facebook"></a></li>
                                            <li><a href="#" class="fa fa-pinterest"></a></li>
                                            <li><a href="#" class="fa fa-google-plus"></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="content">
                                    <h3>Theresa R. Wood</h3>
                                    <h4>Prepress Technician</h4>
                                    <p>
                                        <i class="fa fa-quote-left"></i>
                                        Dictum ligula condimentum cursus commodo sed hendrerit id, posuere tempus odio. Phasellus vel leo aliquam, interdum massa quis, aliquam sapien. Aliquam erat volutpat. Etiam nec ultricies semper risus.
                                        <i class="fa fa-quote-right"></i>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Testimonials Section End -->

            <!-- Contact Section Start -->
            <section id="contact" class="section-bg wow fadeInUp">
                <div class="container">
                    <div class="section-header">
                        <h3>Contact Us</h3>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-detail">
                                <div class="contact-hours">
                                    <h4>Opening Hours</h4>
                                    <p>Monday-Friday: 9am to 7pm</p>
                                    <p>Saturday: 9am to 4pm</p>
                                    <p>Sunday: Closed</p>
                                </div>
                                
                                <div class="contact-info">
                                    <h4>Contact Info</h4>
                                    <p>4137  State Street, CA, USA</p>
                                    <p><a href="tel:+1-234-567-8900">+1-234-567-8900</a></p>
                                    <p><a href="mailto:info@example.com">info@example.com</a></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="contact-form">
                                <form method="post">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <input type="text" class="form-control" name="N" placeholder="Your Name" required="required" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="email" class="form-control" name="E" placeholder="Your Email" required="required" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="S" placeholder="Subject" required="required" />
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" rows="5" name="M" placeholder="Message" required="required" ></textarea>
                                    </div>
                                    <div><button type="submit" name="data">Send Message</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Contact end -->
            
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
            <section id="support">
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

        <!-- Main Javascript File -->
        <script src="js/main.js"></script>

    </body>
</html>
