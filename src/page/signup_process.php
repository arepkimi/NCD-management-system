<?php
// Include the database connection
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $fullname = $_POST['fullname'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If username or email exists, show an alert
        echo "<script>alert('Username or email already exists. Please choose another one.'); window.history.back();</script>";
    } else {
        // SQL query to insert data into the database
        $sql = "INSERT INTO users (fullname, age, phone, address, username, email, password) 
                VALUES ('$fullname', $age, '$phone', '$address', '$username', '$email', '$password')";

        session_start();

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Get the last inserted user ID
            $user_id = $conn->insert_id;

            // Store the user_id in the session
            $_SESSION['user_id'] = $user_id;

            // Success message
            echo "<script>alert('Successfully registered!'); window.location.href='ncd-selection.html';</script>";
        } else {
            // Error message
            echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
        }
    }
}
?>
