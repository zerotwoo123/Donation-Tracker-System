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
    <title>Giving Grace: Donation Tracker System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="css/tracker.css" />
    <link rel="shortcut icon" type="x-icon" href="/assets/image/sfdef.png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <?php
    require '../php/config.php';
    $conn = new mysqli('localhost', 'root', '', 'donation');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>
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
   

    </div>
    <main class="mt-5 pt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-4">
                <h4>Donation Tracker</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <span><i class="bi bi-table me-2"></i></span> Donation Table
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="donationTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                        <th>Print</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch distinct donor names from the donation_tbl only
                                    $sql = "SELECT DISTINCT donorName FROM donation_tbl";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        // Output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            $donorName = $row["donorName"];
                                            echo "<tr><td>" . $donorName . "</td><td><button class='btn btn-primary' onclick='viewDonations(\"" . $donorName . "\")'>View</button></td><td><button class='btn btn-secondary' onclick='printDonations(\"" . $donorName . "\")'>Print</button></td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3'>No results found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
    <div class="modal fade" id="donationModal" tabindex="-1" aria-labelledby="donationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="donationModalLabel">Donations from <span id="selectedDonor"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Corporate Name</th>
                            <th>Donation Type</th>
                            <th>Payment Method</th> <!-- New column -->
                            <th>Type of in-kind
                            <th>Details</th>
                            <th>View Receipt</th>
                        </tr>
                    </thead>
                    <tbody id="donationTableBody">
                        <!-- Donation details will be inserted here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--RECEIPT MODAL-->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm"> <!-- Adjusted size -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">View Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="receiptContent">
                    <!-- Receipt image will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>



<script src="./js/bootstrap.bundle.min.js"></script>
<script>
   function viewDonations(donorName) {
    $.ajax({
        url: '/dashboard/fetch_donor.php',
        method: 'POST',
        data: {
            donorName: donorName
        },
        dataType: 'json',
     success: function(response) {
    if (response.status === 'success') {
        $('#selectedDonor').text(donorName);
        $('#donationTableBody').empty(); // Clear existing table rows
        response.data.forEach(function(donation) {
            let row = $('<tr>');
            row.append($('<td>').text(donation.date));
            row.append($('<td>').text(donation.corporateName));
            row.append($('<td>').text(donation.typeDonation));
            row.append($('<td>').text(donation.typePayment)); // Add payment method
            row.append($('<td>').text(donation.typeInkind)); // Add type of in-kind donation
            let details = donation.typeDonation === 'Cash Donation' ? donation.cash : donation.howFood;
            row.append($('<td>').text(details)); // Display either cash or howFood based on donation type
            let viewReceiptBtn = $('<button>').addClass('btn btn-primary btn-sm').text('View Receipt').click(function() {
                viewReceipt(donation); // Call the function to view the receipt
            });
            let viewReceiptCell = $('<td>').append(viewReceiptBtn);
            row.append(viewReceiptCell);
            $('#donationTableBody').append(row);
        });
        $('#donationModal').modal('show');
    } else {
        alert('Error: ' + response.message);
    }
},

    });
}
    function viewReceipt(donation) {
        // Clear any existing content in the receipt modal
        $('#receiptContent').empty();

        // Check if there is a photo associated with the donation
        if (donation.photo) {
            // Create an image element to display the photo
            var imageElement = $('<img>').attr('src', donation.photo).addClass('img-fluid');
            $('#receiptContent').append(imageElement);
        } else {
            // If no photo is associated, display a message
            $('#receiptContent').html('<p>No photo found for this donation.</p>');
        }

        // Open the receipt modal
        $('#receiptModal').modal('show');
    }

    function printDonations(donorName) {
    $.ajax({
        url: '/dashboard/fetch_donor.php',
        method: 'POST',
        data: {
            donorName: donorName
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var printableContent = '<h1 style="text-align: center; margin-top: 20px;">Donations from ' + donorName + '</h1>';
                printableContent += '<table style="width:100%; border-collapse: collapse; border: 1px solid #000;"><thead><tr><th style="border: 1px solid #000; padding: 8px;">Date</th><th style="border: 1px solid #000; padding: 8px;">Corporate Name</th><th style="border: 1px solid #000; padding: 8px;">Donation Type</th><th style="border: 1px solid #000; padding: 8px;">Payment Method</th><th style="border: 1px solid #000; padding: 8px;">Type Of In-Kind</th><th style="border: 1px solid #000; padding: 8px;">Details</th></tr></thead><tbody>';
                response.data.forEach(function(donation) {
                    let donorDetails = donation.corporateName ? donation.corporateName : '';
                    printableContent += '<tr>';
                    printableContent += '<td style="border: 1px solid #000; padding: 8px;">' + donation.date + '</td>';
                    printableContent += '<td style="border: 1px solid #000; padding: 8px;">' + donorDetails + '</td>';
                    printableContent += '<td style="border: 1px solid #000; padding: 8px;">' + donation.typeDonation + '</td>';
                    printableContent += '<td style="border: 1px solid #000; padding: 8px;">' + donation.typePayment + '</td>';
                    printableContent += '<td style="border: 1px solid #000; padding: 8px;">' + (donation.typeInkind ? donation.typeInkind : '') + '</td>';
                    printableContent += '<td style="border: 1px solid #000; padding: 8px;">' + (donation.cash ? donation.cash : '') + '</td>';
                    printableContent += '</tr>';
                });
                printableContent += '</tbody></table>';

                // Dynamically create a hidden iframe and set its content to the printable content
                var iframe = $('<iframe id="printFrame" style="display: none;"></iframe>');
                $('body').append(iframe);
                var printDocument = iframe[0].contentWindow.document;
                printDocument.open();
                printDocument.write('<html><head><title>Donations from ' + donorName + '</title><style>@media print { @page { size: auto; margin: 0mm; } .page-number { position: fixed; bottom: 10px; right: 10px; } }</style></head><body>' + printableContent + '<div class="page-number"></div></body></html>');
                printDocument.close();

                iframe[0].contentWindow.focus();
                setTimeout(function() {
                    iframe[0].contentWindow.print();
                    iframe.remove();
                }, 1000); // Wait for the iframe to load before printing
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('Failed to fetch donations: ' + error);
        }
    });
}


</script>


</body>
</html>