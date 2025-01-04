<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View and Edit User Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .card {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f5f5f5;
        }

        .card-body {
            padding: 15px;
        }

        h4 {
            margin-top: 0;
            color: #555;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        label {
            display: block;
            margin-top: 10px;
            color: #444;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            color: #333;
        }

        textarea {
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .mt-3 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php 
include('../includes/config.php');
$id = $_GET['id'];  // Get the ID of the user to be edited

// Fetch user info from accounts table
$query = mysqli_query($db_conn, "SELECT * FROM accounts WHERE id = $id");
$user = mysqli_fetch_assoc($query);

// Fetch user-specific details based on user type (student or teacher)
if ($user['type'] == 'student') {
    $details_query = mysqli_query($db_conn, "SELECT * FROM students WHERE account_id = $id");
    $details = mysqli_fetch_assoc($details_query);
}

if (isset($_POST['update'])) {
    // Basic account details
    $name = $_POST['name'];
    $email = $_POST['email'];
    
    // Update account table
    mysqli_query($db_conn, "UPDATE accounts SET name = '$name', email = '$email' WHERE id = $id");

    // Update student-specific details or teacher-specific details
    if ($user['type'] == 'student') {
        $dob = $_POST['dob'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];
        $class = $_POST['class'];
        $section = $_POST['section'];

        // Update students table
        mysqli_query($db_conn, "UPDATE students SET dob = '$dob', mobile = '$mobile', address = '$address', class = '$class', 
                                section = '$section' WHERE account_id = $id");
    } 
    // Redirect to view page after update
    header("Location: view.php?id=$id");
    exit;
}
?>

<!-- HTML Form for editing -->
<div class="container-fluid">
    <h2>Edit <?php echo ucfirst($user['type']); ?> Details</h2>
    <form method="POST">
        <!-- Basic Account Details -->
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $user['name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
        </div>

        <!-- Student Specific Fields -->
        <?php if ($user['type'] == 'student') { ?>
            <div class="form-group">
                <label>DOB</label>
                <input type="date" class="form-control" name="dob" value="<?php echo $details['dob']; ?>" required>
            </div>
            <div class="form-group">
                <label>Mobile</label>
                <input type="text" class="form-control" name="mobile" value="<?php echo $details['mobile']; ?>" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address" value="<?php echo $details['address']; ?>" required>
            </div>
            <!-- Add all other fields in a similar way -->

            <div class="form-group">
                <label>Class</label>
                <input type="text" class="form-control" name="class" value="<?php echo $details['class']; ?>" required>
            </div>
            <div class="form-group">
                <label>Section</label>
                <input type="text" class="form-control" name="section" value="<?php echo $details['section']; ?>" required>
            </div>
            <!-- Add other fields for student -->
        <?php } ?>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
    </form>
</div>
