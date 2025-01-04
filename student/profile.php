<?php
// Include the database connection file
include('../includes/config.php'); // Ensure your db_connection.php file contains the correct database connection details

// Check if the student is logged in (assuming the user_id is stored in session)
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if student is not logged in
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID (assuming 'user_id' corresponds to the account ID)
$account_id = $_SESSION['user_id'];

// Fetch student details from the database using account_id
$query = "SELECT s.*, a.email, a.name AS account_name, a.type AS account_type 
          FROM students s
          JOIN accounts a ON s.account_id = a.id
          WHERE a.id = ?"; // Use account_id from session

// Prepare the statement
$stmt = $db_conn->prepare($query);
$stmt->bind_param("i", $account_id); // Bind the account_id
$stmt->execute();
$result = $stmt->get_result();

// Check if student exists
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();

    // Fetch posts associated with the student if needed
    $query_posts = "SELECT * FROM posts WHERE author = ?";
    $stmt_posts = $db_conn->prepare($query_posts);
    $stmt_posts->bind_param("i", $account_id); // Use account_id to fetch posts
    $stmt_posts->execute();
    $result_posts = $stmt_posts->get_result();
} else {
    echo "<p>Student not found. Please check if the user is logged in properly.</p>";
}

// Close the database connection
$stmt->close();
$db_conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Comic Sans MS', 'Arial', sans-serif;
}

body {
    background:whitesmoke;
    color: #333;
    line-height: 1.6;
    overflow-x: hidden;
}

/* Profile Container */
.profile-container {
    width: 90%;
    max-width: 1000px;
    margin: 50px auto;
    background: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    border: 5px solid green;
}

/* Profile Title */
.profile-container h2 {
    text-align: center;
    font-size: 40px;
    margin-bottom: 30px;
    font-weight: bold;
    color: #ff6f61;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

/* Section Title */
.profile-container h3 {
    font-size: 28px;
    color: #6a1b9a;
    margin-bottom: 20px;
    font-weight: bold;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
}

/* Profile Sections */
.profile-section {
    background-color: #fefefe;
    padding: 20px;
    border-radius: 12px;
    border: 3px dashed #6a1b9a;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    transition: transform 0.3s, background-color 0.3s;
}

.profile-section:hover {
    transform: scale(1.05);
    background-color: #f3e5f5;
}

/* Personal Details Section */
.personal-details {
    background-color: #fff3e0;
    border-left: 6px solid #ff7043;
}

.personal-details p {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

.personal-details strong {
    color: #ff7043;
}

/* Parents' Details Section */
.parents-details {
    background-color: #e3f2fd;
    border-left: 6px solid #2196f3;
}

.parents-details p {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

.parents-details strong {
    color: #2196f3;
}

/* College Details Section */
.college-details {
    background-color: #ede7f6;
    border-left: 6px solid #7e57c2;
}

.college-details p {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

.college-details strong {
    color: #7e57c2;
}

/* Button Styling */
button {
    background-color: #ff6f61;
    color: white;
    font-size: 18px;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s;
}

button:hover {
    background-color: #ff8a80;
    transform: translateY(-3px);
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .profile-container {
        width: 95%;
        margin-top: 20px;
    }

    .profile-section p {
        font-size: 16px;
    }

    .profile-container h2 {
        font-size: 32px;
    }

    .profile-container h3 {
        font-size: 24px;
    }
}
</style>
</head>
<body>
    <div class="profile-container">
        <h2>Student Profile</h2>

        <!-- Personal Details Section -->
        <div class="profile-section personal-details">
            <h3>Personal Details</h3>
            <p><strong>Student Name:</strong> <?php echo htmlspecialchars($student['account_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student['dob']); ?></p>
            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($student['mobile']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></p>
            <p><strong>Country:</strong> <?php echo htmlspecialchars($student['country']); ?></p>
            <p><strong>State:</strong> <?php echo htmlspecialchars($student['state']); ?></p>
            <p><strong>Zip Code:</strong> <?php echo htmlspecialchars($student['zip']); ?></p>
        </div>

        <!-- Parents' Details Section -->
        <div class="profile-section parents-details">
            <h3>Parents' Details</h3>
            <p><strong>Father's Name:</strong> <?php echo htmlspecialchars($student['father_name']); ?></p>
            <p><strong>Father's Mobile:</strong> <?php echo htmlspecialchars($student['father_mobile']); ?></p>
            <p><strong>Mother's Name:</strong> <?php echo htmlspecialchars($student['mother_name']); ?></p>
            <p><strong>Mother's Mobile:</strong> <?php echo htmlspecialchars($student['mother_mobile']); ?></p>
            <p><strong>Parents' Address:</strong> <?php echo htmlspecialchars($student['parents_address']); ?></p>
            <p><strong>Parents' Country:</strong> <?php echo htmlspecialchars($student['parents_country']); ?></p>
            <p><strong>Parents' State:</strong> <?php echo htmlspecialchars($student['parents_state']); ?></p>
        </div>

        <!-- College Information Section -->
        <div class="profile-section college-details">
            <h3>College Information</h3>
            <p><strong>Class:</strong> <?php echo htmlspecialchars($student['class']); ?></p>
            <p><strong>Section:</strong> <?php echo htmlspecialchars($student['section']); ?></p>
            <p><strong>Date of Admission:</strong> <?php echo htmlspecialchars($student['doa']); ?></p>
      </div>
        
        </div>
    </div>
</body>
</html>
