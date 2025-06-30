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
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-9eX+y1pIYFfIy9Jcy2HcNsK+MDt3lq0K/+l+Uv6A83WcgOa9u5Uq2zu6veLhXhPV" crossorigin="anonymous">
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
            <h4 class="mt-4">Assign Role to Admin</h4> <!-- Moved the title inside the container -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Assign Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch admin data
                    require '../php/config.php';
                    $result = $con->query("SELECT adminID, name, email, role FROM admin_tbl");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['role']}</td>
                        <td>
                            <select class='form-select' onchange='assignRole({$row['adminID']}, this.value)'>
                                <option value='' disabled selected>Select role</option>
                                <option value='super_admin'>super_admin</option>
                                <option value='admin'>admin</option>
                            </select>
                        </td>
                    </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <script>
            function assignRole(adminID, role) {
                var formData = new FormData();
                formData.append('admin_id', adminID);
                formData.append('role', role);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'assign_role.php', true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Role assigned successfully!'
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        alert('Error: ' + xhr.responseText);
                    }
                };
                xhr.send(formData);
            }
        </script>
    </main>

    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.min.js"></script>
    <script src="./js/jquery-3.5.1.js"></script>
    <script src="./js/jquery.dataTables.min.js"></script>
    <script src="./js/dataTables.bootstrap5.min.js"></script>
    <script src="./js/script.js"></script>
</body>


</html>