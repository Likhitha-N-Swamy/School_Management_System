<?php
session_start();
include('header.php');
include('sidebar.php');
$servername = "localhost"; // your database server
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "school_system"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = $_SESSION['user_id']; 

// Fetch the current user's information
$sql = "SELECT id, name, email, password FROM accounts WHERE id = $student_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['name'];
} else {
    echo "No user found.";
    exit();
}

// Check if the form is submitted to change the password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    
    // Hash the new password with MD5 before storing it
    $hashed_password = md5($new_password);

    // Update the password in the database
    $update_sql = "UPDATE accounts SET password = '$hashed_password' WHERE id = $student_id";
    if ($conn->query($update_sql) === TRUE) {
        echo "<div class='message success'>Password updated successfully.</div>";
    } else {
        echo "<div class='message error'>Error updating password: " . $conn->error . "</div>";
    }
}

$conn->close();
?>

<?php include("footer.php");?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 16px;
            color: #555;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            font-size: 16px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .password-info {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }
        .fun-message {
            font-size: 14px;
            color: #888;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Change Your Password</h2>

    <p class="password-info">Hey <?php echo $username; ?>, let's keep your account safe! 😎</p>

    <form method="POST" action="">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <input type="submit" value="Update Password">
    </form>

    <div class="fun-message">
        <p>Remember, to provide a strong password</p>
    </div>
</div>

</body>
</html>
