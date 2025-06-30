<?php
session_start();
// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php"); // Redirect to login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="css/donation.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.main.css" integrity="sha384-9eX+y1pIYFfIy9Jcy2HcNsK+MDt3lq0K/+l+Uv6A83WcgOa9u5Uq2zu6veLhXhPV" crossorigin="anonymous">
    <link rel="shortcut icon" type="x-icon" href="/assets/image/sfdef.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">


    <title>Giving Grace: Donation Tracker System</title>
</head>

<body>
    <!-- top navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="offcanvasExample">
                <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
            </button>
            <a class="navbar-brand me-auto ms-lg-0 ms-3 text-uppercase fw-bold" href="#">
                <img src="/assets/image/sfdef.png" alt="Bootstrap" width="80" height="80"> Giving Grace
            </a>
            <div class="navbar-text text-light ms-auto me-3">
                <div class="dropdown">
                    <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-3"></i>
                        <span style="margin-left: 10px;"><?php echo $_SESSION['name']; ?></span>
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><button id="logoutButton" class="dropdown-item" type="button"><i class="bi-box-arrow-right"></i>&nbsp;Logout</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- top navigation bar -->
    <!-- offcanvas -->
    <div class="offcanvas offcanvas-start sidebar-nav bg-dark mt-5" tabindex="-1" id="sidebar">
        <div class="offcanvas-body p-0">
            <nav class="navbar-dark">
                <ul class="navbar-nav">
                    <li class="my-2"></li>
                    <li>
                        <div class="text-muted small fw-bold text-uppercase px-3 mb-2">
                            Core
                        </div>
                    </li>
                    <li>
                        <a href="dashboard.php" class="nav-link px-3">
                            <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="my-2"></li>
                    <li>
                        <div class="text-muted small fw-bold text-uppercase px-3 mb-2">
                            Interface
                        </div>
                    </li>
                    <li>
                        <a href="Donor.php" class="nav-link px-3">
                            <span class="me-2"><i class="bi bi-person-fill"></i></span>
                            <span>Donors Information</span>
                        </a>
                    </li>
                    <li>
                        <a href="Donation.php" class="nav-link px-3">
                            <span class="me-2"><i class="ri-hand-heart-fill"></i></span>
                            <span>Add Donation</span>
                        </a>
                    </li>
                    <li>
                        <a href="Tracker.php" class="nav-link px-3">
                            <span class="me-2"><i class="bi bi-graph-up"></i></span>
                            <span>Donation Tracker</span>
                        </a>
                    </li>
                    <li>
                        <a href="expenses.php" class="nav-link px-3">
                            <span class="me-2"><i class="ri-wallet-3-fill"></i></span>
                            <span>Expenses Tracker</span>
                        </a>
                    </li>
                    <?php if ($_SESSION['role'] === 'super_admin') : ?>
                        <li>
                            <a href="access.php" class="nav-link px-3">
                                <span class="me-2"><i class="bi bi-person-plus-fill"></i></span>
                                <span>Admin Access</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <div class="navbar-dark position-absolute bottom-0 w-100">
            <ul class="navbar-nav">
                <li>
                    <a href="#" class="nav-link px-3" id="logoutButton">
                        <span class="me-2"><i class="ri-logout-box-r-line"></i></span>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <script>
            // JavaScript to handle logout confirmation with SweetAlert
            document.getElementById('logoutButton').addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, logout!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with logout action
                        window.location.href = '../login/logout.php';
                    }
                });
            });
        </script>
    </div>
    <main class="mt-5 pt-5">
        <div class="container-fluid">
            <div class="col-md-12 mt-4">
                <h4>Donation Form</h4>
            </div>
            <div class="row">
                <!-- Donation Form -->
                <div class="col-md-6">
                    <form method="POST" action="handle_donation.php">
                        <div>
                            <div class="form-group col-md-7">
                                <label for="donorInfo">Donor's Information</label>
                                <input type="donorName" name="donorName" class="form-control" id="donorName" placeholder="Name:">
                            </div>
                            <div class="form-group col-md-7 mt-2">
                                <label for="corporate">Corporate Donors</label>
                                <input type="corporate" name="corporate" class="form-control" id="corporate" placeholder="Name:">
                            </div>
                            <div class="form-group col-md-7 mt-2">
                                <label for="date">Select Date</label>
                                <input type="date" id="date" name="date" class="form-control">
                            </div>

                            <label for="typeDonation" class="mt-2">What Type of Donation</label>
                            <select class="form-select col-md-8 mt-2" id="donationType" name="typeDonation">
                                <option value="" disabled selected>Select...</option>
                                <option value="Cash Donation">Cash Donation</option>
                                <option value="In-Kind Donation">In-Kind Donation</option>
                            </select>

                            <script>
                                $(document).ready(function() {
                                    $('#donationType').change(function() {
                                        $(this).find('option:selected').removeAttr('disabled');
                                        $(this).find('option:disabled').remove();
                                    });
                                });
                            </script>

                            <!-- Form fields for Cash Donation -->
                            <div id="cashDonationFields" class="form-group col-md-7 mt-3" style="display: none;">
                                <label for="amount">Cash Amount:</label>
                                <input type="cashForm" class="form-control" id="amount" name="cash" data-toggle="tooltip" data-html="true" title="<div style='padding: 10px; text-align: left;'>
        Here's how your donation can benefit people:<br>
        <ul style='padding-left: 20px; margin: 0;'>
            <li>1000 pesos: Helps 5 people with basic necessities.</li>
            <li>2000 pesos: Supports 10 people with food supplies.</li>
            <li>3000 pesos: Provides 15 people with clean water.</li>
            <li>4000 pesos: Assists 20 people with educational materials.</li>
            <li>5000 pesos: Aids 25 people with healthcare services.</li>
            <li>6000 pesos: Sustains 30 people with shelter.</li>
        </ul></div>">

                                <!-- Payment Method -->
                                <div class="mt-2">
                                    <label for="typePayment">Payment Method:</label>
                                    <select class="form-select" id="paymentMethod" name="typePayment">
                                        <option value="" disabled selected>Select...</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Gcash">Gcash</option>
                                        <option value="Paymaya">Paymaya</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <!-- Add more options if needed -->
                                    </select>
                                </div>

                                <!-- Photo Upload -->
                                <div class="mt-2">
                                    <label for="photo">Upload Photo:</label>
                                    <input type="file" class="form-control" id="photoUpload" name="photo">
                                </div>
                            </div>

                            <!-- Form fields for In-Kind Donation -->
                            <div id="inKindDonationFields" class="form-group col-md -7 mt-3" style="display: none;">
                                <label for="typeInkind">Type of In-Kind Donation</label>
                                <select class="form-control" id="typeInkind" name="typeInkind">
                                    <option value="" disabled selected style="display: none;">Choose...</option>
                                    <option value="Food">Food</option>
                                    <option value="Clothes">Clothes</option>
                                </select>
                                <div class="mt-2">
                                    <label for="typeDelivery">Delivery Method</label>
                                    <select class="form-select" id="deliveryMethod" name="deliverPayment">
                                        <option value="" disabled selected>Select...</option>
                                        <option value="Lalamove">Lalamove</option>
                                        <option value="Grab">Grab</option>
                                        <!-- Add more options if needed -->
                                    </select>
                                </div>

                                <!-- Photo Upload -->
                                <div class="mt-2">
                                    <label for="proof">Upload Photo:</label>
                                    <input type="file" class="form-control" id="proofUpload" name="proof">
                                </div>
                            </div>
                        </div>

                        <!-- Form fields for Food Donation -->
                        <div id="foodForm" class="col-md-7 mt-2" style="display: none;">
                            <label for="foodDetails" name="typeInkind">What Kind of Food?</label>
                            <input type="text" class="form-control" id="foodDetails" name="kindFood">
                            <label for="foodDetails" class="mt-2">How Many Food?</label>
                            <input type="text" class="form-control" id="foodQuantity" name="howFood">
                            <label for="foodDetails" class="mt-2">Year of Expiration?</label>
                            <input type="text" class="form-control" id="yearExpire" name="yearExpire">
                        </div>
                        <!-- Form fields for Clothes Donation -->
                        <div id="clothesForm" class="col-md-7 mt-2" style="display: none;">
                            <label for="clothesDetails">What Type of Clothes?</label>
                            <input type="text" class="form-control" id="clothesDetails" name="typeClothes">
                            <label for="clothesDetails" class="mt-2">How Many Clothes?</label>
                            <input type="text" class="form-control" id="clothesQuantity" name="howClothes">
                            <label for="clothesDetails" class="mt-2">Sizes of Clothes?</label>
                            <input type="text" class="form-control" id="clothesSizes" name="sizeClothes">
                        </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <img src="/assets/image/donate.png" class="img-fluid" alt="Image" style="width: 90%; height: auto; margin-top:20px;">
                    </div>
                </div>
                </form>
            </div>






            <div class="text-center">
                <button type="button" class="btn btn-primary mt-3" id="confirmDonation">Confirm</button>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Function to handle form submission
                    function submitForm() {
                        // Collect form data
                        var formData = new FormData(document.querySelector('form'));

                        // Send form data to the server using AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'handle_donation.php', true);
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                // Form submission successful
                                // Check if the response indicates success or error
                                var response = JSON.parse(xhr.responseText);
                                if (response.status === 'success') {
                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Donation Successful!',
                                        text: response.message,
                                        timer: 3000, // Auto close alert after 3 seconds
                                    }).then(() => {
                                        // Refresh the page
                                        location.reload();
                                    });
                                } else {
                                    // Show error message
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            } else {
                                // Error handling
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Server Error: ' + xhr.statusText
                                });
                            }
                        };
                        xhr.onerror = function() {
                            // Error handling
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Network Error'
                            });
                        };
                        xhr.send(formData);
                    }

                    // Attach event listener to the confirm button
                    var confirmButton = document.getElementById('confirmDonation');
                    confirmButton.addEventListener('click', function() {
                        // Call the submitForm function when the button is clicked
                        submitForm();
                    });
                });
            </script>


        </div>
        </div>
    </main>
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="./js/jquery-3.5.1.js"></script>
    <script src="./js/jquery.dataTables.min.js"></script>
    <script src="./js/dataTables.bootstrap5.min.js"></script>
    <script src="./js/script.js"></script>

    <script>
        $(document).ready(function() {
            // Function to show/hide donation form fields based on selected option
            $('#donationType').change(function() {
                var selectedOption = $(this).val();

                // Hide all donation form fields
                $('#cashDonationFields, #inKindDonationFields, #foodForm, #clothesForm').hide();

                // Show respective donation form fields based on selected option
                if (selectedOption === "Cash Donation") {
                    $('#cashDonationFields').show();
                } else if (selectedOption === "In-Kind Donation") {
                    $('#inKindDonationFields').show();
                }
            });

            // Show additional fields based on type of in-kind donation
            $('#typeInkind').change(function() {
                var selectedInKindType = $(this).val();

                // Hide all in-kind specific form fields
                $('#foodForm, #clothesForm').hide();

                // Show respective in-kind specific form fields based on selected type
                if (selectedInKindType === "Food") {
                    $('#foodForm').show();
                } else if (selectedInKindType === "Clothes") {
                    $('#clothesForm').show();
                }
            });
        });

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
                html: true,
                placement: 'right'
            });
        });
    </script>
</body>

</html>