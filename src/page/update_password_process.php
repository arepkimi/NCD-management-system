<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $update_sql = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
        if ($conn->query($update_sql) === TRUE) {
            echo "<script>alert('Password successfully updated!'); window.location.href='login.html';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email not found!'); window.history.back();</script>";
    }
}
?>
