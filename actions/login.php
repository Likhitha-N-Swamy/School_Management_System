<?php
include('../includes/config.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    // Hash the password using MD5 (consider upgrading to more secure hashing)
    $pass_md5 = md5($pass);

    // Query to check if email and password match
    $query = mysqli_query($db_conn, "SELECT * FROM `accounts` WHERE `email` = '$email' AND `password` = '$pass_md5'");

    if (!$query) {
        die("Database query failed: " . mysqli_error($db_conn));
    }

    if (mysqli_num_rows($query) > 0) {
        // Fetch user details
        $user = mysqli_fetch_object($query);

        // Start session and set user details
        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_type'] = $user->type;
        $_SESSION['session_id'] = uniqid();

        // Redirect based on the user type
        if ($user->type === 'admin') {
            header('Location: ../admin/dashboard.php');
        } elseif ($user->type === 'teacher') {
            header('Location: ../teacher/dashboard.php');
        } elseif ($user->type === 'student') {
            header('Location: ../student/dashboard.php');
        } else {
            echo "Error: Invalid user type.";
            exit();
        }
        exit();
    } else {
        // Invalid credentials message
        echo '<div style="color: red;">Invalid email or password. Please try again.</div>';
    }
}
?>
