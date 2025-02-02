<?php
  session_start();
  include('db-connection.php');
  if(isset($_POST['submit'])){
    $history = $_POST['history'];
    $stress = $_POST['stress'];
    $exercise = $_POST['exercise'];
    $diet = $_POST['diet'];

    // Assign scores to each answer
    $score = 0;

    // Family history scoring
    if ($history === 'Yes') {
      $score += 2; // Add 2 if family history is Yes
    } else {
      $score += 0; // Add 0 if family history is No
    }

    // Stress scoring
    if ($stress === 'Often') {
      $score += 2; // Add 2 if stress is Often
    } elseif ($stress === 'Sometime') {
      $score += 1; // Add 1 if stress is Sometime
    } else { // Stress is Never
      $score += 0; // Add 0 if stress is Never
    }

    // Exercise scoring
    if ($exercise === 'Sedentary') {
      $score += 2; // Add 2 if exercise is Sedentary
    } elseif ($exercise === 'Moderate') {
      $score += 1; // Add 1 if exercise is Moderate
    } else { // Exercise is Active
      $score += 0; // Add 0 if exercise is Active
    }

    // Diet scoring
    if ($diet === 'Often') {
      $score += 2; // Add 2 if diet is Often
    } elseif ($diet === 'Sometime') {
      $score += 1; // Add 1 if diet is Sometime
    } else { // Diet is Never
      $score += 0; // Add 0 if diet is Never
    }

    // Determine risk level
    $risk_level = '';
    if ($score <= 3) {
        $risk_level = 'Normal';
    } elseif ($score <= 6) {
        $risk_level = 'Moderate Risk';
    } else {
        $risk_level = 'Critical';
    }

    // Get the user ID from the session
    if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];

      // Insert data into the database
      $query = mysqli_query($con, "Insert into hbp_info (user_id, family_history, stress, exercise, food_intake, risk_level) Values ('$user_id', '$history', '$stress', '$exercise', '$diet', '$risk_level')");

      if($query){
        echo "<script>alert('data inserted successfully'); window.location.href='login.html';</script>";
      } else {
        echo "<script>alert('there is an error')</script>";
      }
    } else {
      echo "<script>alert('User not logged in'); window.location.href='login.html';</script>";
    }  
  }
?>