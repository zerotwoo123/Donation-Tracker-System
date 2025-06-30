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
    <link rel="stylesheet" href="css/donor.css" />
    <link rel="shortcut icon" type="x-icon" href="/assets/image/sfdef.png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
                        <li><button id="logoutButton" class="dropdown-item" type="button"><i class="bi-box-arrow-right"></i>&nbsp; Logout</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- Script for logout confirmation -->
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
                    <li>
                        <a href="dashboard.php" class="nav-link px-3">
                            <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                            <span>Dashboard</span>
                        </a>
                    </li>

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
    </div>
    <main class="mt-5 pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mt-4">
                    <h4>Donors Information</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <span><i class="bi bi-person-plus-fill me-2"></i></span> Add Donor Information
                        </div>
                        <div class="card-body">
                            <form id="donorForm" method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" class="form-control" id="age" name="age" required>
                                </div>
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="phoneNumber" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                                <button type="button" class="btn btn-primary" id="addDonorBtn">Add Donor</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <span><i class="bi bi-table me-2"></i></span> Admin Input Table
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped data-table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Sample PHP code:
                                        $servername = "localhost";
                                        $username = "root";
                                        $password = "";
                                        $dbname = "donation";

                                        // Create connection
                                        $con = new mysqli($servername, $username, $password, $dbname);
                                        // Check connection
                                        if ($con->connect_error) {
                                            die("Connection failed: " . $con->connect_error);
                                        }

                                        $sql = "SELECT * FROM donationinfo_tbl";
                                        $result = $con->query($sql);

                                        if ($result->num_rows > 0) {
                                            // output data of each row
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row["name"] . "</td>";
                                                echo "<td>" . $row["age"] . "</td>";
                                                echo "<td>" . $row["gender"] . "</td>";
                                                echo "<td>" . $row["phone"] . "</td>";
                                                echo "<td>" . $row["email"] . "</td>";
                                                echo "<td>" . $row["address"] . "</td>";
                                                echo "<td>";
                                                echo "<button class='btn btn-danger deleteBtn' data-id='" . $row["donateID"] . "'>Delete</button>";
                                                echo "<button class='btn btn-primary editBtn ms-2' data-id='" . $row["donateID"] . "' data-name='" . $row["name"] . "' data-age='" . $row["age"] . "' data-gender='" . $row["gender"] . "' data-phone='" . $row["phone"] . "' data-email='" . $row["email"] . "' data-address='" . $row["address"] . "'>Edit</button>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>No results found</td></tr>";
                                        }
                                        $con->close();
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--USER  TABLE-->

        </div>
        <!-- Modal for editing donor information -->
        <div class="modal fade" id="editDonorModal" tabindex="-1" aria-labelledby="editDonorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDonorModalLabel">Edit Donor Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editDonorForm">
                            <input type="hidden" id="editDonorID" name="editDonorID">
                            <input type="hidden" id="editUserID" name="editUserID">
                            <div class="mb-3">
                                <label for="editName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="editName" name="editName" required>
                            </div>
                            <div class="mb-3">
                                <label for="editAge" class="form-label">Age</label>
                                <input type="number" class="form-control" id="editAge" name="editAge" required>
                            </div>
                            <div class="mb-3">
                                <label for="editGender" class="form-label">Gender</label>
                                <select class="form-select" id="editGender" name="editGender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editPhoneNumber" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="editPhoneNumber" name="editPhoneNumber" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="editEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="editAddress" class="form-label">Address</label>
                                <input type="text" class="form-control" id="editAddress" name="editAddress" required>
                            </div>
                            <button type="button" class="btn btn-primary" id="saveEditBtn">Save Changes</button>
                        </form>
                    </div>
                </div>
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
            $('#addDonorBtn').click(function() {
                // Get form data
                var name = $('#name').val();
                var age = $('#age').val();
                var gender = $('#gender').val();
                var phoneNumber = $('#phoneNumber').val();
                var email = $('#email').val();
                var address = $('#address').val();
                // Check if any of the form fields are empty
                if (name === '' || age === '' || gender === '' || phoneNumber === '' || email === '' || address === '') {
                    // Show error message if any field is empty
                    Swal.fire({
                        title: 'Error!',
                        text: 'All fields are required.',
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                } else {
                    // Form data is valid, proceed with AJAX request
                    var formData = {
                        'name': name,
                        'age': age,
                        'gender': gender,
                        'phoneNumber': phoneNumber,
                        'email': email,
                        'address': address
                    };
                    // Send AJAX request
                    $.ajax({
                            type: 'POST',
                            url: 'add_donor.php',
                            data: formData,
                            dataType: 'json',
                            encode: true
                        })
                        .done(function(data) {
                            // Check if the response contains 'success' message
                            if (data && data.success) {
                                // Show success message
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Donor added successfully!',
                                    icon: 'success',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });

                                // Clear form fields
                                $('#donorForm')[0].reset();

                                // Reload the page after a brief delay
                                setTimeout(function() {
                                    location.reload();
                                }, 2000); // Adjust the delay as needed
                            } else {
                                // Show error message
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to add donor. Please try again later.',
                                    icon: 'error',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });
                            }
                        })
                        .fail(function(data) {
                            // Show error message
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to add donor. Please try again later.',
                                icon: 'error',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        });
                }
                // Prevent form submission
                event.preventDefault();
            });
        });
        //DELETE FUNCTION
        // JavaScript to handle delete confirmation with SweetAlert
        $(document).ready(function() {
            $('.deleteBtn').click(function() {
                var donateID = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with delete action
                        $.ajax({
                                type: 'POST',
                                url: 'delete_donation.php', // Assuming you have a file to handle deletion
                                data: {
                                    donateID: donateID
                                },
                                dataType: 'json',
                                encode: true
                            })
                            .done(function(data) {
                                if (data && data.success) {
                                    // Show success message
                                    Swal.fire(
                                        'Deleted!',
                                        'The data has been deleted.',
                                        'success'
                                    );
                                    // Reload the page after a brief delay
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000); // Adjust the delay as needed
                                } else {
                                    // Show error message
                                    Swal.fire(
                                        'Error!',
                                        'Failed to delete donation. Please try again later.',
                                        'error'
                                    );
                                }
                            })
                            .fail(function(data) {
                                // Show error message
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete donation. Please try again later.',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
        $(document).ready(function() {
            // Edit button click event handler
            $('.editBtn').click(function() {
                // Retrieve donor information from data attributes
                var donateID = $(this).data('id');
                var name = $(this).data('name');
                var age = $(this).data('age');
                var gender = $(this).data('gender');
                var phone = $(this).data('phone');
                var email = $(this).data('email');
                var address = $(this).data('address');

                // Populate the edit modal with donor information
                $('#editDonorID').val(donateID);
                $('#editName').val(name);
                $('#editAge').val(age);
                $('#editGender').val(gender);
                $('#editPhoneNumber').val(phone);
                $('#editEmail').val(email);
                $('#editAddress').val(address);
                

                // Show the edit modal
                $('#editDonorModal').modal('show');
            });

            // Save changes button click event handler
            $('#saveEditBtn').click(function() {
                // Retrieve updated donor information from modal
                var editDonorID = $('#editDonorID').val();
                var editName = $('#editName').val();
                var editAge = $('#editAge').val();
                var editGender = $('#editGender').val();
                var editPhoneNumber = $('#editPhoneNumber').val();
                var editEmail = $('#editEmail').val();
                var editAddress = $('#editAddress').val();

                // Make an AJAX request to update donor information
                $.ajax({
                    type: 'POST',
                    url: 'update_donor.php', // Specify the URL of the PHP script for updating donor information
                    data: {
                        donateID: editDonorID,
                        name: editName,
                        age: editAge,
                        gender: editGender,
                        phoneNumber: editPhoneNumber,
                        email: editEmail,
                        address: editAddress                          
                    },
                    dataType: 'json',
                    encode: true
                }).done(function(data) {
                    // Handle response from the server
                    if (data && data.success) {
                        // Show success message
                        Swal.fire(
                            'Success!',
                            'Donor information updated successfully!',
                            'success'
                        );
                        // Reload the page after a brief delay
                        setTimeout(function() {
                            location.reload();
                        }, 2000); // Adjust the delay as needed
                    } else {
                        // Show error message
                        Swal.fire(
                            'Error!',
                            'Failed to update donor information. Please try again later.',
                            'error'
                        );
                    }
                }).fail(function(data) {
                    // Show error message
                    Swal.fire(
                        'Error!',
                        'Failed to update donor information. Please try again later.',
                        'error'
                    );
                });
            });
        });

        //DELETE FOR USER
        $(document).ready(function() {
            $('.userdeleteBtn').click(function() {
                var userID = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with delete action
                        $.ajax({
                                type: 'POST',
                                url: 'user_delete_donation.php', // Assuming you have a file to handle deletion
                                data: {
                                    userID: userID
                                },
                                dataType: 'json',
                                encode: true
                            })
                            .done(function(data) {
                                if (data && data.success) {
                                    // Show success message
                                    Swal.fire(
                                        'Deleted!',
                                        'The data has been deleted.',
                                        'success'
                                    );
                                    // Reload the page after a brief delay
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000); // Adjust the delay as needed
                                } else {
                                    // Show error message
                                    Swal.fire(
                                        'Error!',
                                        'Failed to delete donation. Please try again later.',
                                        'error'
                                    );
                                }
                            })
                            .fail(function(data) {
                                // Show error message
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete donation. Please try again later.',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
        //USER EDIT
        $(document).ready(function() {
            // Edit button click event handler
            $('.usereditBtn').click(function() {
                // Retrieve donor information from data attributes
                var userID = $(this).data('id');
                var name = $(this).data('name');
                var age = $(this).data('age');
                var gender = $(this).data('gender');
                var phone = $(this).data('phone');
                var email = $(this).data('email');
                var address = $(this).data('address');

                // Populate the edit modal with donor information
                $('#editUserID').val(userID);
                $('#editName').val(name);
                $('#editAge').val(age);
                $('#editGender').val(gender);
                $('#editPhoneNumber').val(phone);
                $('#editEmail').val(email);
                $('#editAddress').val(address);
                

                // Show the edit modal
                $('#editDonorModal').modal('show');
            });

            // Save changes button click event handler
            $('#saveEditBtn').click(function() {
                // Retrieve updated donor information from modal
                var editUserID = $('#editUserID').val();
                var editName = $('#editName').val();
                var editAge = $('#editAge').val();
                var editGender = $('#editGender').val();
                var editPhoneNumber = $('#editPhoneNumber').val();
                var editEmail = $('#editEmail').val();
                var editAddress = $('#editAddress').val();

                // Make an AJAX request to update donor information
                $.ajax({
                    type: 'POST',
                    url: 'user_update_donor.php', // Specify the URL of the PHP script for updating donor information
                    data: {
                        userID: editUserID,
                        name: editName,
                        age: editAge,
                        gender: editGender,
                        phoneNumber: editPhoneNumber,
                        email: editEmail,
                        address: editAddress                          
                    },
                    dataType: 'json',
                    encode: true
                }).done(function(data) {
                    // Handle response from the server
                    if (data && data.success) {
                        // Show success message
                        Swal.fire(
                            'Success!',
                            'Donor information updated successfully!',
                            'success'
                        );
                        // Reload the page after a brief delay
                        setTimeout(function() {
                            location.reload();
                        }, 2000); // Adjust the delay as needed
                    } else {
                        // Show error message
                        Swal.fire(
                            'Error!',
                            'Failed to update donor information. Please try again later.',
                            'error'
                        );
                    }
                }).fail(function(data) {
                    // Show error message
                    Swal.fire(
                        'Error!',
                        'Failed to update donor information. Please try again later.',
                        'error'
                    );
                });
            });
        });
       
    </script>
</body>

</html>