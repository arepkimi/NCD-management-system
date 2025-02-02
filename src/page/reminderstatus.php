<?php
include 'dbcon.php'; // Including your db connection file

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the reminder ID and the new status
    $reminder_id = $_POST['reminder_id'];
    $status = isset($_POST['status']) ? 1 : 0; // If checkbox is checked, set status to 1; otherwise, set to 0

    // Update the reminder status in the database
    $sql = "UPDATE reminderdtb SET status = $status WHERE id = $reminder_id";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the reminder list page to refresh the changes
        header('Location: reminder.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
