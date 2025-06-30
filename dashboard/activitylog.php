<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php"); // Redirect to login page if not logged in
    exit;
}

require '../php/config.php';
$conn = new mysqli('localhost', 'root', '', 'donation');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT a.adminID, al.time, al.action, al.affected, al.details 
          FROM activity_log al 
          JOIN admin_tbl a ON al.adminID = a.adminID
          ORDER BY al.time DESC";

$result = $conn->query($query);
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
    <link rel="stylesheet" href="css/style.css" />
    <link rel="shortcut icon" type="x-icon" href="/assets/image/sfdef.png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.min.css">
    <title>Giving Grace: Donation Tracker System</title>
</head>

<body>

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
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button></li>
                        <li><button id="logoutButton" class="dropdown-item" type="button">Logout</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword">
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                        </div>
                        <div id="changePasswordMessage"></div> <!-- To display success or error message -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="changePassword()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!----CHANGE PASSWORD --->
    <script>
        // Function to clear the message when the modal is closed
        function clearChangePasswordMessage() {
            document.getElementById('changePasswordMessage').innerHTML = '';
        }

        // Check modal state on page load
        window.onload = function() {
            var modal = document.getElementById('changePasswordModal');
            modal.addEventListener('hide.bs.modal', clearChangePasswordMessage);
        };

        function changePassword() {
            var newPassword = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                document.getElementById('changePasswordMessage').innerHTML = "<div class='alert alert-danger' role='alert'>Passwords do not match!</div>";
                return;
            }

            var formData = new FormData();
            formData.append('newPassword', newPassword);
            formData.append('confirmPassword', confirmPassword);

            // Add logged-in user email to form data
            var userEmail = '<?php echo $_SESSION["email"]; ?>';
            formData.append('userEmail', userEmail); // Changed from 'email' to 'userEmail'

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Check if response contains error messages and display appropriate alert
                        if (xhr.responseText.includes("Passwords do not match") || xhr.responseText.includes("New password cannot be the same as the current one")) {
                            document.getElementById('changePasswordMessage').innerHTML = "<div class='alert alert-danger' role='alert'>" + xhr.responseText + "</div>";
                        } else {
                            document.getElementById('changePasswordMessage').innerHTML = "<div class='alert alert-success' role='alert'>" + xhr.responseText + "</div>";
                            document.getElementById('newPassword').value = '';
                            document.getElementById('confirmPassword').value = '';
                        }
                    } else {
                        document.getElementById('changePasswordMessage').innerHTML = "<div class='alert alert-danger' role='alert'>Error: " + xhr.responseText + "</div>";
                    }
                }
            };
            xhr.open('POST', '/dashboard/changepassword.php', true);
            xhr.send(formData);
        }
    </script>

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
                        <a href="activitylog.php" class="nav-link px-3">
                            <span class="me-2"><i class="ri-file-history-fill"></i></span>
                            <span>Activity Logs</span>
                        </a>
                    </li>
                    <li>
                        <a href="expenses.php" class="nav-link px-3">
                            <span class="me-2"><i class="ri-wallet-3-fill"></i></span>
                            <span>Expenses Tracker</span>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin') : ?>
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
    <!-- offcanvas -->
    <main class="mt-5 pt-5">
        <div class="container mt-4">
        <h2>Activity Logs</h2>
        <table class="table table-striped" id="logsTable">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Action</th>
                    <th>Affected</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['time'] . "</td>";
                        echo "<td>" . $row['action'] . "</td>";
                        echo "<td>" . $row['affected'] . "</td>";
                        echo "<td>" . $row['details'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No activity logs found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#logsTable').DataTable();
        });
    </script>
    </main>
    </body>
    </html>
