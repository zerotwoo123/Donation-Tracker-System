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
                    <h4>Expenses Tracker</h4>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <span><i class="bi bi-wallet-fill me-2"></i></span> Add Expense
                            </div>
                            <div class="card-body">
                                <form id="expenseForm" method="POST" action="add_expense.php" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="cash" class="form-label">Amount</label>
                                        <input type="cashForm" step="0.01" class="form-control" id="cash" name="cash" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="date" name="date" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Upload Photo for Receipt</label>
                                        <input type="file" class="form-control" id="photo" name="photo">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Expense</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <span><i class="bi bi-table me-2"></i></span> Expenses Table
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped data-table" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Photo</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
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

                                            $sql = "SELECT expID, description, cash, date, photo FROM expenses_tbl";
                                            $result = $con->query($sql);

                                            if ($result->num_rows > 0) {
                                                // output data of each row
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row["description"] . "</td>";
                                                    echo "<td>" . $row["cash"] . "</td>";
                                                    echo "<td>" . $row["date"] . "</td>";
                                                    echo "<td><button class='btn btn-primary viewReceiptBtn' data-photo='uploads/" . $row["photo"] . "'>View Receipt</button></td>"; // View Receipt button
                                                    echo "<td>";
                                                    echo "<button class='btn btn-danger deleteBtn' data-id='" . $row["expID"] . "'>Delete</button>";
                                                    echo "<button class='btn btn-primary editBtn ms-2' data-id='" . $row["expID"] . "' data-description='" . $row["description"] . "' data-cash='" . $row["cash"] . "' data-date='" . $row["date"] . "'>Edit</button>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No expenses found</td></tr>";
                                            }
                                            $con->close();
                                            ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Photo</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Expense Modal -->
        <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editExpenseModalLabel">Edit Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editExpenseForm" method="POST" action="edit_expense.php">
                            <div class="mb-3">
                                <label for="editDescription" class="form-label">Description</label>
                                <input type="text" class="form-control" id="editDescription" name="description" required>
                            </div>
                            <div class="mb-3">
                                <label for="editCash" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="editCash" name="cash" required>
                            </div>
                            <div class="mb-3">
                                <label for="editDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="editDate" name="date" required>
                            </div>
                            <input type="hidden" id="editExpID" name="expID">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="viewReceiptModal" tabindex="-1" aria-labelledby="viewReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReceiptModalLabel">View Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="receiptImage" src="" class="img-fluid" alt="Receipt">
            </div>
        </div>
    </div>
</div>


    </main>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();

            // Handle delete button click
            $(document).on('click', '.deleteBtn', function() {
                var expID = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    timer: 2000 // Add a delay of 2 seconds
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_expense.php',
                            type: 'POST',
                            data: {
                                expID: expID
                            },
                            success: function(response) {
                                if (response === 'success') {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'The expense has been deleted.',
                                        icon: 'success',
                                        timer: 2000 // Add a delay of 2 seconds
                                    });
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000); // Reload the page after 2 seconds
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'There was an error deleting the expense.',
                                        icon: 'error',
                                        timer: 2000 // Add a delay of 2 seconds
                                    });
                                }
                            }
                        });
                    }
                });
            });

            // Handle edit button click
            $(document).on('click', '.editBtn', function() {
                var expID = $(this).data('id');
                var description = $(this).data('description');
                var cash = $(this).data('cash');
                var date = $(this).data('date');

                // Fill the modal with existing data
                $('#editDescription').val(description);
                $('#editCash').val(cash);
                $('#editDate').val(date);
                $('#editExpID').val(expID);

                // Show the modal
                $('#editExpenseModal').modal('show');
            });

            $(document).ready(function() {
    $('#expenseForm').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: "This will add the expense.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!',
            cancelButtonText: 'No, cancel',
            timerProgressBar: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the form submission
                setTimeout(() => {
                    this.submit();
                }, 2000); // Submit the form after 2 seconds
                Swal.fire({
                    title: 'Success!',
                    text: 'The expense has been added.',
                    icon: 'success',
                    timerProgressBar: true
                });
            }
        });
    });
});


            // Handle edit expense form submission
            $('#editExpenseForm').submit(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Show SweetAlert confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will save the changes.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save changes!',
                    cancelButtonText: 'No, cancel',
                    timerProgressBar: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with the form submission
                        setTimeout(() => {
                            this.submit();
                        }, 2000); // Submit the form after 2 seconds
                        Swal.fire({
                            title: 'Success!',
                            text: 'The changes have been saved.',
                            icon: 'success',
                            timerProgressBar: true
                        });
                    }
                });
            });
        });
        $(document).on('click', '.viewReceiptBtn', function() {
        var photoPath = $(this).data('photo');
        // Update modal content with the receipt photo
        $('#viewReceiptModal img').attr('src', photoPath);
        // Show the modal
        $('#viewReceiptModal').modal('show');
    });
      
    </script>


</body>

</html>