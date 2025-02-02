<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch username and age from users table
    $sql_user = "SELECT username, age FROM users WHERE user_id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $username = $user['username'];
        $age = $user['age'];
    }

    // Initialize variables
    $ncd_type = null;
    $condition = null;
    $recommendation_link = "default.asp";  // Default recommendation page
    $emergency_link = "";

    // Check for NCD type in related tables
    $sql = "SELECT 'Diabetes' AS ncd_type, risk_level FROM diabetes_info WHERE user_id = ?
            UNION
            SELECT 'High Blood Pressure (HBP)' AS ncd_type, risk_level FROM hbp_info WHERE user_id = ?
            UNION
            SELECT 'Heart Disease' AS ncd_type, risk_level FROM heart_disease_info WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $ncd_types = [];
    while ($row = $result->fetch_assoc()) {
        $ncd_types[] = [
            'ncd_type' => $row['ncd_type'],
            'condition' => $row['risk_level']
        ];
    }

    if (empty($ncd_types)) {
        $ncd_type = "None";
        $condition = "No condition detected";
    } else {
        $ncd_type = implode(", ", array_column($ncd_types, 'ncd_type'));
        $condition = implode(", ", array_column($ncd_types, 'condition'));

        // Set the correct recommendation link based on the NCD type
        if (in_array("Diabetes", array_column($ncd_types, 'ncd_type'))) {
            $recommendation_link = "dbt.html";
            $emergency_link = "emergency-dbt.html";
        } elseif (in_array("High Blood Pressure (HBP)", array_column($ncd_types, 'ncd_type'))) {
            $recommendation_link = "hbp.html";
            $emergency_link = "emergency-hbp.html";
        } elseif (in_array("Heart Disease", array_column($ncd_types, 'ncd_type'))) {
            $recommendation_link = "hd.html";
            $emergency_link = "emergency-hd.html";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('You must log in first.'); window.location.href='/login.html';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCD Fitness Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color:rgb(222, 199, 168);
            text-align: center;
            color: rgb(0, 0, 0);
            min-height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #5c4033;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header .logo {
            display: flex;
            align-items: center;
        }

        header .logo img {
            margin-right: 10px;
            width: 50px;
        }

        header .refresh-button {
            font-size: 20px;
            border: none;
            background: none;
            cursor: pointer;
            padding: 5px 10px;
            color: #FFE0B2;
            margin-right: 10px;
            width: 40px;
        }

        header .logout-icon img {
            margin-right: 10px;
            width: 30px;
        }
        

        .user-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin: 20px 0;
}

.user-info .profile-pic img {
    width: 120px;
    border-radius: 50%;
    border: 2px solid #000;
}

.user-info .info {
    text-align: left;
}

.user-box {
    background-color: #fff2df;  /* Light background */
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  /* Slight shadow for depth */
    max-width: 400px;
}

.user-box p {
    margin: 10px 0;
    font-size: 16px;
    color: #333;
}


        .dashboard {
            background-color: #fff2df;
            color: #000000;
            padding: 170px 0;
        }

        .dashboard h2 {
            margin-bottom: 20px;
        }

        .dashboard .icons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .dashboard .icon-box {
            background-color: #fbe4c6;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            color: rgb(0, 0, 0);
            transition: background-color 0.3s ease; /* Smooth background color transition */
        }

        .dashboard .icon-box:hover {
            background-color: #f2c79b; /* Slightly darker shade of the original color */
        }

        .dashboard .icon-box img {
            width: 300px;
            cursor: pointer;
            height: 300px;
        }


        footer {
            background-color: #5c4033;
            color: #fff;
            padding: 10px 20px;
            position: relative;
            bottom: 0;
            width: 100%;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="/cat304/src/pic/logo.png" alt="Logo">
            <strong>NCD Fitness</strong>
        </div>
        <div class="logout-icon">
            <a href="logout.html"><button class="refresh-button"><img src="/cat304/src/pic/5.img.png" alt="logout-icon"></button></a>
        </div>
    </header>

    <section class="user-info">
    <div class="profile-pic">
        <img src="/cat304/src/pic/7.img.jpg" alt="User Profile">
    </div>
    <div class="info">
        <div class="user-box">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?> | <strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
            <p><strong>NCD Type:</strong> <?php echo htmlspecialchars($ncd_type); ?> | <strong>Condition:</strong> <?php echo htmlspecialchars($condition); ?></p>
        </div>
    </div>
</section>


    <section class="dashboard">
        <h2>"Take Charge Of Your Health Today!"</h2>
        <div class="icons">
            <div class="icon-box">
                <div class="alarmic">
                    <a href="reminder.php"><img src="/cat304/src/pic/2.img.png" alt="Alarm"></a><p>ALARM</p>
                </div>
            </div>
            <div class="icon-box">
                <a href="<?php echo $recommendation_link; ?>"><img src="/cat304/src/pic/1.img.png" alt="Recommendation"></a><p>PLAN</p>
            </div>
            <div class="icon-box">
                <a href="EducationalPage.html"><img src="/cat304/src/pic/4.img.png" alt="Guide"></a><p>GUIDE</p>
            </div>
            <div class="icon-box">
                <a href="<?php echo $emergency_link; ?>"><img src="/cat304/src/pic/3.img.png" alt="Emergency Contact"></a><p>EMERGENCY</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 NCD Fitness. All Rights Reserved.</p>
    </footer>
</body>
</html>