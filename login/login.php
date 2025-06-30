<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="/assets/image/sfdef.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Giving Grace: Donation Tracker System</title>
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="main">
    <!-- Sign in Form -->
    <section class="sign-in">
        <div class="container">
            <div class="signin-content">
                <div class="signin-form">
                    <div style="display: flex; align-items: center;">
                        <img src="/assets/image/logo.png" alt="Image Description" style="width: 60px; height: auto; margin-right: 10px; margin-bottom:10px">
                        <h2 class="form-title" style="margin-bottom: 5px; font-size:13px; font-weight:normal;">Giving Grace Donation Tracking<br> Asilo de Vicente De Paul</h2>
                    </div>
                    <h2 class="form-title" style="margin-bottom: 5px;">Login</h2>
                    <h6 style="white-space: nowrap; font-size: 14px; margin-top: 0;">
                        <span style="display: inline-block; font-size: inherit; font-weight: normal;">Not Registered?</span>
                        <a href="signup.php" class="signup-image-link" style="display: inline-block; font-size: inherit; font-weight:bold;">Create an Account</a>
                    </h6>
                    <form method="POST" class="register-form" id="login-form" action="logins.php">
                        <div class="form-group">
                            <label for="your_name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                            <input type="text" name="email" id="your_name" placeholder="Email" required />
                        </div>
                        <div class="form-group">
                            <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="password" id="your_pass" placeholder="Password" required />
                        </div>
                        <a href="Forgotpassword.php" data-target="img-3">Forgot Password?</a>
                        <div class="form-groups form-button mb-3 mt-3">
                            <input type="submit" name="signin" id="signin" class="form-submit" value="Log in" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="carousel">
    <img src="https://images.pexels.com/photos/8441852/pexels-photo-8441852.jpeg" alt="Slide 1" class="active">
    <img src="https://images.pexels.com/photos/1654698/pexels-photo-1654698.jpeg?cs=srgb&dl=pexels-rickyrecap-1654698.jpg&fm=jpg" alt="Slide 2">
    <img src="https://images.pexels.com/photos/1773113/pexels-photo-1773113.jpeg" alt="Slide 3">
    <img src="https://images.pexels.com/photos/8441854/pexels-photo-8441854.jpeg" alt="Slide 4">
</div>

<!-- JS -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="js/main.js"></script>

<script>
    $(document).ready(function() {
        $('#login-form').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting normally
            var formData = $(this).serialize(); // Serialize the form data

            // AJAX request to the PHP script
            $.ajax({
                type: 'POST',
                url: 'logins.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // If login successful, show success message and redirect to dashboard
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success"
                        }).then((result) => {
                            window.location.href = response.redirect;
                        });
                    } else {
                        // If login failed, show error message
                        Swal.fire({
                            title: "Oops...",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // If an error occurs during the AJAX request
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: "Error",
                        text: "An error occurred during login. Please try again later.",
                        icon: "error"
                    });
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const carouselImages = document.querySelectorAll('.carousel img');
        let index = 0;

        setInterval(() => {
            carouselImages[index].classList.remove('active');
            index = (index + 1) % carouselImages.length;
            carouselImages[index].classList.add('active');
        }, 5000); // Adjust the interval duration (in milliseconds) as needed
    });
</script>

</body>

</html>
