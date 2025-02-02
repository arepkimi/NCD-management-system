<?php
include 'dbcon.php';


// Delete reminder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM reminderdtb WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Reminder deleted successfully!";
    } else {
        echo "Error deleting reminder: " . $conn->error;
    }
}

$conn->close();
header("Location: reminder.php");
exit();
?>
