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
    <!-- Forgot Password Form -->
    <section class="sign-in">
        <div class="container">
            <div class="signin-content">
                <div class="signin-form">
                    <div style="display: flex; align-items: center;">
                        <img src="/assets/image/logo.png" alt="Image Description" style="width: 60px; height: auto; margin-right: 10px; margin-bottom:10px">
                        <h2 class="form-title" style="margin-bottom: 5px; font-size:13px; font-weight:normal;">Giving Grace Donation Tracking<br> Asilo de Vicente De Paul</h2>
                    </div>
                    <h2 class="form-title" style="margin-bottom: 5px;">Forgot Password</h2>
                    <h6 style="white-space: nowrap; font-size: 14px; margin-top: 0;">
                        <span style="display: inline-block; font-size: inherit; font-weight: normal;">Go back to?</span>
                        <a href="login.php" class="signup-image-link" style="display: inline-block; font-size: inherit; font-weight:bold;">Login</a>
                    </h6>
                    <form method="POST" class="register-form" id="register-form">
                        <div class="form-group">
                            <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                            <input type="text" name="name" id="name" placeholder="Enter Email" required />
                        </div>
                        <div class="form-group">
                            <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="pass" id="pass" placeholder="Enter New Password" required />
                        </div>
                        <div class="form-group">
                            <label for="re_pass"><i class="zmdi zmdi-lock-outline"></i></label>
                            <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password" required />
                        </div>
                        <div class="form-group form-button mb-3 mt-3">
                            <button type="button" name="signup" id="signup" class="form-submit">Confirm</button>
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

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JS -->
<script src="vendor/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#signup').on('click', function() {
            var name = $('#name').val();
            var pass = $('#pass').val();
            var re_pass = $('#re_pass').val();

            // Check if any field is empty
            if (name === '' || pass === '' || re_pass === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Please fill out all fields',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if (pass !== re_pass) {
                // Check if passwords match
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords do not match',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'forgot_password.php',
                    data: {
                        name: name,
                        pass: pass,
                        re_pass: re_pass,
                        signup: 1
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password updated successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location.href = 'Forgotpassword.php';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong. Please try again later.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
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
