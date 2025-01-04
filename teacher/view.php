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

$id = intval($_GET['id']); // Sanitize input to prevent SQL injection

// Fetch user info
$query = mysqli_query($db_conn, "SELECT * FROM accounts WHERE id = $id");
if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);
} else {
    die("User not found.");
}

// Fetch additional details based on user type
$details = [];
if ($user['type'] == 'student') {
    $details_query = mysqli_query($db_conn, "SELECT * FROM students WHERE account_id = $id");
    if (mysqli_num_rows($details_query) > 0) {
        $details = mysqli_fetch_assoc($details_query);
    } else {
        die("Student details not found.");
    }
}
?>

<div class="container-fluid">
    <h2>View <?php echo ucfirst($user['type']); ?> Details</h2>
    <form method="POST" action="update-user.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="type" value="<?php echo $user['type']; ?>">
        
        <div class="card">
            <div class="card-body">
                <h4>Basic Information</h4>
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo $user['name']; ?>" class="form-control" required>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" class="form-control" required>
                
                <h4>Additional Details</h4>
                <?php if ($user['type'] == 'student') { ?>
                <label for="dob">DOB:</label>
                <input type="date" name="dob" value="<?php echo $details['dob']; ?>" class="form-control">

                <label for="mobile">Mobile:</label>
                <input type="text" name="mobile" value="<?php echo $details['mobile']; ?>" class="form-control">

                <label for="address">Address:</label>
                <textarea name="address" class="form-control"><?php echo $details['address']; ?></textarea>

                <label for="country">Country:</label>
                <input type="text" name="country" value="<?php echo $details['country']; ?>" class="form-control">

                <label for="state">State:</label>
                <input type="text" name="state" value="<?php echo $details['state']; ?>" class="form-control">

                <label for="zip">Zip Code:</label>
                <input type="text" name="zip" value="<?php echo $details['zip']; ?>" class="form-control">

                <label for="father_name">Father's Name:</label>
                <input type="text" name="father_name" value="<?php echo $details['father_name']; ?>" class="form-control">

                <label for="father_mobile">Father's Mobile:</label>
                <input type="text" name="father_mobile" value="<?php echo $details['father_mobile']; ?>" class="form-control">

                <label for="mother_name">Mother's Name:</label>
                <input type="text" name="mother_name" value="<?php echo $details['mother_name']; ?>" class="form-control">

                <label for="mother_mobile">Mother's Mobile:</label>
                <input type="text" name="mother_mobile" value="<?php echo $details['mother_mobile']; ?>" class="form-control">

                <label for="parents_address">Parents' Address:</label>
                <textarea name="parents_address" class="form-control"><?php echo $details['parents_address']; ?></textarea>

                <label for="parents_country">Parents' Country:</label>
                <input type="text" name="parents_country" value="<?php echo $details['parents_country']; ?>" class="form-control">

                <label for="parents_state">Parents' State:</label>
                <input type="text" name="parents_state" value="<?php echo $details['parents_state']; ?>" class="form-control">

                <label for="parents_zip">Parents' Zip:</label>
                <input type="text" name="parents_zip" value="<?php echo $details['parents_zip']; ?>" class="form-control">

                <label for="class">Class:</label>
                <input type="text" name="class" value="<?php echo $details['class']; ?>" class="form-control">

                <label for="section">Section:</label>
                <input type="text" name="section" value="<?php echo $details['section']; ?>" class="form-control">

                <label for="doa">Date of Admission:</label>
                <input type="date" name="doa" value="<?php echo $details['doa']; ?>" class="form-control">
                <?php } ?>

            </div>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-secondary" onclick="redirectToDashboard()">Back</button>
        </div>
       
    </form>
</div>
</body>
</html>
<script>
    function redirectToDashboard() {
        window.location.href = 'dashboard.php'; // Replace 'dashboard.php' with the correct dashboard URL.
    }
</script>