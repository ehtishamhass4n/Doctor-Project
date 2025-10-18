
<?php
session_start();
include("./db/conection.php");// Replace with your actual DB connection file

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Sanitize inputs (better to use prepared statements)
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $role = mysqli_real_escape_string($conn, $role);

    // Query to check credentials and role
    $sql = "SELECT * FROM register WHERE email='$email' AND password='$password' AND roll='$role'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['email'] = $email;
        $_SESSION['roll'] = $role;

        // Redirect to home page after login
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials or role.');</script>";
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
            <h1><a href="index.php">Dr. Johnson</a></h1>
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
                    <li class="menu-active"><a href="about.php">About</a></li>
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
                    <h3>login</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 form">
                        <form method="post">
                            <div class="form-row">
  
                                <div class="form-group col-md-6">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email" required />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Your Password" required />
                                    <span class="password-toggle" onclick="togglePassword()">
                                        <i class="fa fa-eye" id="toggleIcon"></i>
                                    </span>
                                </div>
                               
                            </div>
                            <div class="form-row">
                                
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control" name="role" required>
                                    <option value="" disabled selected>Select your role</option>
                                    <option>Doctor</option>
                                    <option>Patient</option>
                                </select>
                            </div>
                            
                            <div>
                                <button type="submit" name="login" class="btn btn-primary">login</button>
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
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
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
