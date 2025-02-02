<?php
include 'db_connection.php'; // Including your dbcon.php file for database connection
session_start(); // Start the session to access user information

// Check if user is logged in, and get the user_id from the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch reminders from the database for the logged-in user only
$sql = "SELECT * FROM reminderdtb WHERE user_id = ? ORDER BY alarm_time";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind the user_id parameter
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCD Alarm</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #fff2df;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: #5c4033;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header img {
            height: 40px;
        }

        .header-icons {
            display: flex;
            gap: 10px;
        }

        .header-icons img {
            margin-right: 10px;
            width: 30px;
            height: 30px;
        }

        .content {
            text-align: center;
            padding: 20px;
            flex: 1;
        }

        .bell-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .reminder-list {
            margin: 0 auto;
            max-width: 600px;
            color: black;
        }

        .note {
            color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            color: black;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #5c4033;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #fbe4c6;
        }

        .delete-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #00aaff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    font-size: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.2s ease; /* Add smooth transition */
}

.add-button:hover {
    background-color: #0088cc;  /* Darker blue on hover */
    transform: scale(1.1);  /* Slightly increase the size on hover */
}


        footer {
            background-color: #5c4033;
            color: white;
            text-align: center;
            padding: 10px 20px;
            height: 40px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="/cat304/src/pic/logo.png" alt="NCD Fitness Logo">
        <div class="header-icons">
            <a href="dashboard.php"><img src="/cat304/src/pic/6img.png" alt="Menu.symbol"></a>
            <a href="logout.html"><img src="/cat304/src/pic/5.img.png" alt="logout.symbol"></a>
        </div>
    </div>

    <div class="content">
        <div class="bell-icon">&#128276;</div>
        <div class="reminder-list">
            <h2>Reminder List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Description</th>
                        <th>Activation</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['alarm_time']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td>
                                    <form action="reminderstatus.php" method="POST">
                                        <input type="hidden" name="reminder_id" value="<?= $row['id'] ?>">
                                        <input type="checkbox" name="status" value="1" <?= $row['status'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td>
                                    <form action="deleteremind.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="delete-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No reminders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="note">
            <p>Tick To Activate Your Alarm!</p>
        </div>
    </div>

    <a href="reminder+.html"><button class="add-button">+</button></a>

    <footer>
      <p>&copy; 2024 NCD Fitness. All Rights Reserved.</p>
    </footer>
</body>
</html>
