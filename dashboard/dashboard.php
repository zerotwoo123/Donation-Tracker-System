<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php"); // Redirect to login page if not logged in
    exit;
}

require '../php/config.php';
$conn = new mysqli('localhost', 'root', '', 'donation');
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
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
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><i class="bi-key-fill"></i> &nbsp; Change Password</button></li>
                        <li><button id="logoutButton" class="dropdown-item" type="button"><i class="bi-box-arrow-right"></i> &nbsp; Logout</button></li>
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
                        <a href="#" class="nav-link px-3">
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mt-4"></div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card bg-white rounded">
                        <div class="card-body py-5">
                            <h5 style="font-weight:bold;"><i class="bi bi-cash"></i> &nbsp;Cash Donation</h5>
                            <div class="metric-value d-inline-block">
                                <h1 class="mb-1" style="font-size: 35px;">₱
                                    <?php
                                    // Query to select sum of cash values from donation_tbl
                                    $sql = "SELECT SUM(cash) AS total_cash FROM donation_tbl";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $totalCash = $row["total_cash"];
                                    } else {
                                        $totalCash = 0; // Default value if no donations found
                                    }

                                    echo number_format($totalCash, 2);
                                    ?>
                                </h1>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-white">
                        <div class="card-body py-5">
                            <h5 style="font-weight:bold;"><i class="ri-shirt-line"></i>&nbsp;Clothes Donation</h5>
                            <div class="metric-value d-inline-block">
                                <?php
                                // Query to select sum of clothes values from donation_tbl
                                $sql = "SELECT SUM(howClothes) AS totalClothes FROM donation_tbl";
                                $result = $conn->query($sql);

                                // Initialize totalClothes variable
                                $totalClothes = 0;

                                if ($result->num_rows > 0) {
                                    // Fetch the total number of clothes donated from donation_tbl
                                    $row = $result->fetch_assoc();
                                    $totalClothes += $row["totalClothes"];
                                }

                                // Output the total number of clothes donated inside the HTML block
                                echo '<h1 class="mb-1">' . $totalClothes . '</h1>';
                                ?>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-white">
                        <div class="card-body py-5">
                            <h5 style="font-weight:bold;"><i class="ri-bread-line"></i>&nbsp;Food Donation</h5>
                            <div class="metric-value d-inline-block">
                                <?php
                                // Query to select sum of food values from donation_tbl
                                $sql = "SELECT SUM(howFood) AS totalFood FROM donation_tbl";
                                $result = $conn->query($sql);

                                // Initialize totalFood variable
                                $totalFood = 0;

                                if ($result->num_rows > 0) {
                                    // Fetch the total number of food donated from donation_tbl
                                    $row = $result->fetch_assoc();
                                    $totalFood += $row["totalFood"];
                                }

                                // Output the total number of food donated inside the HTML block
                                echo '<h1 class="mb-1">' . $totalFood . '</h1>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            

            <?php
            // Query to select the total number of distinct donors from donation_tbl
            $sql = "SELECT COUNT(DISTINCT donorName) AS total_donors FROM donation_tbl";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $total_donors = $row["total_donors"];
            } else {
                $total_donors = 0;
            }

            $conn->close();
            ?>

            <div class="col-md-3 mb-2">
                <div class="card bg-white">
                    <div class="card-body py-5">
                        <h5 style="font-weight:bold;"><i class="bi bi-people"></i></i>&nbsp;Total Donor</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1"><?php echo $total_donors; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!--CASH TABLE-->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <span class="me-2"><i class="bi bi-bar-chart-fill"></i></span>
                            Cash Donation Chart
                        </div>
                        <div class="card-body">
                            <canvas id="yearlyDonationChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <?php
                // Include your database configuration file
                require '../php/config.php';

                // Create a database connection
                $conn = new mysqli('localhost', 'root', '', 'donation');

                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Query to fetch yearly cash donation data from donation_tbl only
                $sql = "SELECT YEAR(date) AS year, SUM(cash) AS total_donations FROM donation_tbl GROUP BY YEAR(date)";
                $result = $conn->query($sql);

                // Initialize an empty array to store yearly donation data
                $yearlyDonations = array();

                // Check if there are any results
                if ($result->num_rows > 0) {
                    // Fetch each row from the result set
                    while ($row = $result->fetch_assoc()) {
                        // Append the row to the $yearlyDonations array
                        $yearlyDonations[] = $row;
                    }
                }

                // Close the database connection
                $conn->close();

                // Return the yearly donation data as JSON
                echo '<script>';
                echo 'const yearlyDonationData = ' . json_encode($yearlyDonations) . ';';
                echo '</script>';
                ?>

                <script>
                    // Function to render the yearly cash donation graph using Chart.js
                    function renderYearlyDonationGraph(yearlyDonationData) {
                        const years = yearlyDonationData.map(entry => entry.year);
                        const totalDonations = yearlyDonationData.map(entry => entry.total_donations);

                        const ctx = document.getElementById('yearlyDonationChart').getContext('2d');
                        const yearlyDonationChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: years,
                                datasets: [{
                                    label: 'Cash Total Donations',
                                    data: totalDonations,
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }

                    // Call the renderYearlyDonationGraph function with the yearly cash donation data
                    document.addEventListener('DOMContentLoaded', function() {
                        renderYearlyDonationGraph(yearlyDonationData);
                    });
                </script>
                <!--INKIND TABLE-->
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <span class="me-2"><i class="bi bi-bar-chart-fill"></i></span>
                            In-Kind Donation Chart
                        </div>
                        <div class="card-body">
                            <canvas id="yearlyInKindDonationChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <?php
                // Include your database configuration file
                require '../php/config.php';

                // Create a database connection
                $conn = new mysqli('localhost', 'root', '', 'donation');

                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Query to fetch yearly in-kind donation data for food from donation_tbl
                $sqlFood = "SELECT YEAR(date) AS year, SUM(howFood) AS total_food_donations FROM donation_tbl GROUP BY YEAR(date)";
                $resultFood = $conn->query($sqlFood);

                // Query to fetch yearly in-kind donation data for clothes from donation_tbl
                $sqlClothes = "SELECT YEAR(date) AS year, SUM(howClothes) AS total_clothes_donations FROM donation_tbl GROUP BY YEAR(date)";
                $resultClothes = $conn->query($sqlClothes);

                // Initialize an empty array to store yearly in-kind donation data for food and clothes
                $yearlyInKindDonations = array();

                // Check if there are any results for food
                if ($resultFood->num_rows > 0) {
                    // Fetch each row from the result set for food
                    while ($rowFood = $resultFood->fetch_assoc()) {
                        // Append the row to the $yearlyInKindDonations array
                        $yearlyInKindDonations[] = $rowFood;
                    }
                }

                // Check if there are any results for clothes
                if ($resultClothes->num_rows > 0) {
                    // Fetch each row from the result set for clothes
                    while ($rowClothes = $resultClothes->fetch_assoc()) {
                        // Find the corresponding entry in $yearlyInKindDonations for the same year
                        $found = false;
                        foreach ($yearlyInKindDonations as &$entry) {
                            if ($entry['year'] === $rowClothes['year']) {
                                // If found, add the clothes donation to the existing entry
                                $entry['total_clothes_donations'] = $rowClothes['total_clothes_donations'];
                                $found = true;
                                break;
                            }
                        }
                        // If not found, create a new entry
                        if (!$found) {
                            $yearlyInKindDonations[] = array(
                                'year' => $rowClothes['year'],
                                'total_food_donations' => 0,
                                'total_clothes_donations' => $rowClothes['total_clothes_donations']
                            );
                        }
                    }
                }

                // Close the database connection
                $conn->close();

                // Return the yearly in-kind donation data as JSON
                echo '<script>';
                echo 'const yearlyInKindDonationData = ' . json_encode($yearlyInKindDonations) . ';';
                echo '</script>';
                ?>

                <script>
                    // Function to render the yearly in-kind donation graph using Chart.js
                    function renderYearlyInKindDonationGraph(yearlyInKindDonationData) {
                        const years = yearlyInKindDonationData.map(entry => entry.year);
                        const totalFoodDonations = yearlyInKindDonationData.map(entry => entry.total_food_donations);
                        const totalClothesDonations = yearlyInKindDonationData.map(entry => entry.total_clothes_donations);

                        const ctx = document.getElementById('yearlyInKindDonationChart').getContext('2d');
                        const yearlyInKindDonationChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: years,
                                datasets: [{
                                        label: 'Food Total Donations',
                                        data: totalFoodDonations,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Clothes Total Donations',
                                        data: totalClothesDonations,
                                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }

                    // Call the renderYearlyInKindDonationGraph function with the yearly in-kind donation data
                    document.addEventListener('DOMContentLoaded', function() {
                        renderYearlyInKindDonationGraph(yearlyInKindDonationData);
                    });
                </script>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <span><i class="bi bi-table me-2"></i></span> Admin
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                // Start the session if it hasn't been started yet
                                if (session_status() == PHP_SESSION_NONE) {
                                    session_start();
                                }

                                // Replace with your actual database credentials
                                $localhost = "localhost"; // Or your database host
                                $root = "root"; // Your MySQL username
                                $password = ""; // Your MySQL password
                                $donation = "donation"; // Your database name

                                $con = new mysqli($localhost, $root, $password, $donation);

                                // Check connection
                                if ($con->connect_error) {
                                    die("Connection failed: " . $con->connect_error);
                                }

                                // Fetch session data
                                $role = $_SESSION['role'];
                                $email = $_SESSION['email'];

                                // Super admin can see all data, admin can only see their own data
                                if ($role === 'super_admin') {
                                    $sql = "SELECT name, email, date FROM admin_tbl";
                                } else {
                                    $sql = "SELECT name, email, date FROM admin_tbl WHERE email = ?";
                                }

                                $stmt = $con->prepare($sql);
                                if ($role !== 'super_admin') {
                                    $stmt->bind_param("s", $email);
                                }
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    echo '<table class="table table-hover table-nowrap">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Date Created</th>
                                    </tr>
                                </thead>
                                <tbody>';

                                    // Output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>
                                    <td>' . htmlspecialchars($row["name"]) . '</td>
                                    <td>' . htmlspecialchars($row["email"]) . '</td>
                                    <td>' . htmlspecialchars($row["date"]) . '</td>
                                  </tr>';
                                    }
                                    echo '</tbody></table>';
                                } else {
                                    echo "<p>No results found</p>";
                                }

                                $stmt->close();
                                $con->close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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