<?php
// Include the database connection file
include 'dbcon.php';

// Start the session to access user information
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $alarm_time = $_POST['alarm-time'];
    $description = $_POST['alarm-description'];
    $phone_no = $_POST['alarm-phone']; // Get phone number instead of email

    // Get the logged-in user's user_id from the session
    $user_id = $_SESSION['user_id']; // Ensure this is set during login

    // Validate input data
    if (!empty($alarm_time) && !empty($description) && !empty($phone_no) && !empty($user_id)) {
        // Prepare an SQL statement to insert data into the table
        $sql = "INSERT INTO reminderdtb (alarm_time, description, phone_no, user_id) VALUES (?, ?, ?, ?)";

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $alarm_time, $description, $phone_no, $user_id);

        // Execute the query and check for success
        if ($stmt->execute()) {
            echo "<script>
                    alert('Successfully Added!');
                    window.open('/cat304/src/page/reminder.php', '_blank');
                    window.location.href='/cat304/src/page/notify.php';
                    
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Please fill in all the fields.";
    }
}

// Close the database connection
$conn->close();
?>
