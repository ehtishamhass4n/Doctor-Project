<?php
session_start();
include("./db/conection.php");
if (isset($_POST["register"])) {
    $_name = $_POST["N"];
    $_email = $_POST["E"];
    $_pass = $_POST["P"];
    $_rpassword = $_POST["RP"];  // Corrected variable name here
    $_number = $_POST["NU"];
    $_Address = $_POST["A"];
    $_roll = $_POST["R"];
    $_Rember = isset($_POST["RM"]) ? 1 : 0; // Checkbox value handling
   
    $query = "INSERT INTO register(name,email,password,repatepass,address,number,roll,rember) 
              VALUES ('$_name','$_email','$_pass','$_rpassword','$_Address','$_number','$_roll','$_Rember')";
    $res = mysqli_query($conn, $query);

    if ($res) {
        $_SESSION['signup_success'] = true;
        $_SESSION['email'] = $_email;
        $_SESSION['roll'] = $_roll;
        header("Location: create_profile.php");
        exit();
    } else {
        echo "Not registered.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dr. Johnson</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Bootstrap CSS File -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Other CSS Files -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        .form-group {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 16px;
        }
        .password-toggle:hover {
            color: #333;
        }
    </style>
</head>

<body>
    <!-- Top Header Start -->
    <section class="banner-header">
        <div class="container text-center">
            <h1><a href="index.html">Dr. Johnson</a></h1>
            <h2>Your Family Doctor</h2>
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
                </ul>
            </nav>
        </div>
    </header>
    <!-- Header End -->

    <main id="main">

        <!-- Register Section Start -->
        <section id="login">
            <div class="container">
                <div class="section-header">
                    <h3>register</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 form">
                        <form method="post">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="N" placeholder="Your Name" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="email" class="form-control" name="E" placeholder="Your Email" required />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="password" class="form-control" name="P" id="password" placeholder="Your Password" required />
                                    <span class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                        <i class="fa fa-eye" id="toggleIcon1"></i>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="password" class="form-control" name="RP" id="repeatPassword" placeholder="Repeat Your Password" required />
                                    <span class="password-toggle" onclick="togglePassword('repeatPassword', 'toggleIcon2')">
                                        <i class="fa fa-eye" id="toggleIcon2"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="A" placeholder="Enter your address" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="NU" placeholder="Enter your Phone number" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control" name="R" required>
                                    <option value="" disabled selected>Select your role</option>
                                    <option>Doctor</option>
                                    <option>Patient</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember1" name="RM">
                                    <label class="custom-control-label" for="remember1">Remember me</label>
                                </div>
                            </div>
                            <div>
                                <button type="submit" name="register" class="btn btn-primary">sign up</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Register Section End -->

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
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Main Javascript File -->
    <script src="js/main.js"></script>
    
    <script>
        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(iconId);
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
