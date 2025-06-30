<?php
// Establish database connection (replace with your actual database credentials)
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

// Fetch schedules from the database
$sql = "SELECT scheduleID, name, date, time, event FROM schdule_tbl"; // Update the table name if necessary
$result = $con->query($sql);

// Output data of each row
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["name"] . "</td>";
    echo "<td>" . $row["date"] . "</td>";
    echo "<td>" . $row["time"] . "</td>";
    echo "<td>" . $row["event"] . "</td>";
    echo "<td>";
    echo '<button type="button" class="btn btn-danger btn-sm w-100 mb-2 btn-open-delete-modal" data-scheduleID="' . $row["scheduleID"] . '">
            <i class="bi bi-trash"></i> Delete </button>';
    echo "</td>";
    echo "</tr>";
}

// Close connection
$con->close();
?>
