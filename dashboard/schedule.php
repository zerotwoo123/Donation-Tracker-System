<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../home.php");
    exit;
}

require '../php/config.php';
$con = new mysqli('localhost', 'root', '', 'donation');
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
    <title>Giving Grace: Donation Tracker System</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="offcanvasExample">
                <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
            </button>
            <a class="navbar-brand me-auto ms-lg-0 ms-3 text-uppercase fw-bold" href="#"><img src="/assets/image/sfdef.png" alt="Bootstrap" width="40" height="40"> Giving Grace</a>
        </div>
    </nav>
    <!-- top navigation bar -->
    <!-- offcanvas -->
    <div class="offcanvas offcanvas-start sidebar-nav bg-dark" tabindex="-1" id="sidebar">
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
                    <a href="Tracker.php" class="nav-link px-3">
                        <span class="me-2"><i class="bi bi-graph-up"></i></span>
                        <span>Donation Tracker</span>
                    </a>
                    <a href="schedule.php" class="nav-link px-3">
                        <span class="me-2"><i class="ri-calendar-schedule-fill"></i></span>
                        <span>Schedule</span>
                    </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="navbar-dark position-absolute bottom-0 w-100">
            <ul class="navbar-nav">
                <li>
                    <a href="../login/logout.php" class="nav-link px-3">
                        <span class="me-2"><i class="ri-logout-box-r-line"></i></span>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <main class="mt-5 pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h4>Schedule</h4>
                    <!-- Box for adding schedule -->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Add Schedule</h5>
                                    <!-- Form to input schedule -->
                                    <form id="addScheduleForm">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="date" class="form-label">Date</label>
                                            <input type="date" class="form-control" id="date" name="date">
                                        </div>
                                        <div class="mb-3">
                                            <label for="time" class="form-label">Time</label>
                                            <input type="time" class="form-control" id="time" name="time">
                                        </div>
                                        <div class="mb-3">
                                            <label for="event" class="form-label">Event</label>
                                            <input type="text" class="form-control" id="event" name="event">
                                        </div>

                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Schedule table -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Event</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                            <?php
                            // Fetch schedules from the database
                            $sql = "SELECT scheduleID, name, date, time, event FROM schdule_tbl"; // Corrected table name
                            $result = $con->query($sql);

                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["date"] . "</td>";
                                echo "<td>" . $row["time"] . "</td>";
                                echo "<td>" . $row["event"] . "</td>";
                                echo "<td>";
                                echo '<button type="button" class="btn btn-danger btn-sm w-100 mb-2 btn-delete" onclick="deleteSchedule(' . $row["scheduleID"] . ')">
                                <i class="bi bi-trash"></i> Delete
                            </button>';
                            
                                // Corrected data attribute name
                                echo "</td>";
                                echo "</tr>";
                            }

                            // Close connection
                            $con->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/jquery-3.5.1.js"></script>
    <script src="./js/jquery.dataTables.min.js"></script>
    <script src="./js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
// Define the deleteSchedule function in the global scope
function deleteSchedule(scheduleID) {
    // Show confirmation dialog before deleting
    Swal.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: 'You are about to delete this schedule.',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed deletion, proceed with AJAX request
            console.log('Deleting schedule with ID:', scheduleID); // Debugging

            $.ajax({
                type: 'POST',
                url: '/dashboard/delete_schedule.php',
                data: {
                    scheduleID: scheduleID
                },
                success: function(response) {
                    console.log('Delete request successful:', response); // Debugging
                    // Show success message using SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Schedule deleted successfully!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload the page after successful deletion
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Delete request error:', error); // Debugging
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // User cancelled deletion, do nothing
            console.log('Deletion cancelled.');
        }
    });
}

$(document).ready(function() {
    // Handle form submission for adding schedules
    $('#addScheduleForm').submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        // Manually check if all required fields are filled
        var name = $('#name').val().trim();
        var date = $('#date').val().trim();
        var time = $('#time').val().trim();
        var event = $('#event').val().trim();

        if (name === '' || date === '' || time === '' || event === '') {
            // Show error message using SweetAlert2 if any required field is empty
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill out all required fields.',
                confirmButtonText: 'OK'
            });
            return; // Exit the function early if any required field is empty
        }

        // Serialize form data
        var formData = $(this).serialize();

        // Send AJAX request to backend for adding schedule
        $.ajax({
            type: 'POST',
            url: '/dashboard/add_schedules.php',
            data: formData,
            success: function(response) {
                console.log('Add schedule request successful:', response); // Debugging
                // Show success message using SweetAlert2
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Schedule added successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reload the page after successful addition
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                console.error('Add schedule request error:', error); // Debugging
                // Show error message using SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add schedule. Please try again later.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
</script>





</body>

</html>