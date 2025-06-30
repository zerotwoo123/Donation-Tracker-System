<?php
require '../php/config.php';
$conn = new mysqli('localhost', 'root', '', 'donation');

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $event = $_POST['event'];

    // Check if all fields are filled
    if (!empty($name) && !empty($date) && !empty($time) && !empty($event)) {
        // Prepare and bind SQL statement with prepared statement
        $stmt = $con->prepare("INSERT INTO schdule_tbl (name, date, time, event) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $date, $time, $event);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Construct HTML for the new schedule row
            $newScheduleHTML = '<tr class="scheduleRow">';
            $newScheduleHTML .= '<td>' . $name . '</td>';
            $newScheduleHTML .= '<td>' . $date . '</td>';
            $newScheduleHTML .= '<td>' . $time . '</td>';
            $newScheduleHTML .= '<td>' . $event . '</td>';
            $newScheduleHTML .= '<td>';
            $newScheduleHTML .= '<button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>';
            $newScheduleHTML .= '</td>';
            $newScheduleHTML .= '</tr>';

            // Return the HTML for the new schedule row
            echo $newScheduleHTML;
        } else {
            // Return error message if execution fails
            echo "Error: Unable to execute SQL statement";
        }

        // Close statement
        $stmt->close();
    } else {
        // Return error message if any field is empty
        echo "Error: All fields are required";
    }
} else {
    // If the form is not submitted, return an error response
    echo "Error: Form not submitted";
}

// Close connection
$con->close();
?>
