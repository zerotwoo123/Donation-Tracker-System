<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="/assets/image/sfdef.png">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.3/dist/sweetalert2.min.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Giving Grace: Donation Tracker System </title>
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/signup.css">
</head>

<body>

    <div class="main">
        <section class="signup" id="signup-section">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <div style="display: flex; align-items: center;">
                            <img src="/assets/image/logo.png" alt="Image Description" style="width: 60px; height: auto; margin-right: 10px; margin-bottom:10px">
                            <h2 class="form-title" style="margin-bottom: 5px; font-size:13px; font-weight:normal;">Giving Grace Donation Tracking<br> Asilo de Vicente De Paul</h2>
                        </div>
                        <h2 class="form-title" style="margin-bottom: 5px;">Sign up</h2>
                        <h6 style="white-space: nowrap; font-size: 14px; margin-top: 0;"><span style="display: inline-block; font-size: inherit; font-weight: normal;">Already have an Account?</span> <a href="login.php" class="signup-image-link" style="display: inline-block; font-size: inherit;">Login</a></h6>
                        <form method="POST" class="register-form" id="register-form">
                            <div class="form-group">
                                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="name" id="name" placeholder="Name" />
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Email" />
                            </div>
                            <div class="form-group">
                                <label for="date"><i class="zmdi zmdi-calendar"></i></label>
                                <input type="date" name="date" id="date" placeholder="Date" />
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="password" placeholder="Password" />
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="confirmpass" id="confirmpass" placeholder="Confirm Password" />
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                                <label for="agree-term" class="label-agree-term">
                                    <span><span></span></span>
                                    I agree all statements in <a href="#" class="term-service">Terms of service</a>
                                </label>
                            </div>
                            <div class="form-group form-button">
                                <button type="button" id="register" class="form-submit">Register</button>
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

    <script>
        document.getElementById('register').addEventListener('click', function() {
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var date = document.getElementById('date').value;
            var password = document.getElementById('password').value;
            var confirmpass = document.getElementById('confirmpass').value;
            var agree = document.getElementById('agree-term').checked;

            if (!name || !email || !date || !password || !confirmpass || !agree) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill in all fields and agree to the terms.'
                });
                return;
            }

            if (password !== confirmpass) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Passwords do not match.'
                });
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'insert.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: xhr.responseText
                    }).then(() => {
                        window.location.reload();
                    });
                }
            };
            xhr.send('name=' + name + '&email=' + email + '&date=' + date + '&password=' + password);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.3/dist/sweetalert2.all.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
