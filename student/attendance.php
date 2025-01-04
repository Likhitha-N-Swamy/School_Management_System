<?php
include('../includes/config.php'); 
include('header.php'); 
include('sidebar.php'); 

$account_id = $_SESSION['user_id'];

// Query to get the student ID from the students table using the account_id
$query = "SELECT id FROM students WHERE account_id = ?";
$stmt = $db_conn->prepare($query);
$stmt->bind_param("i", $account_id); // "i" stands for integer type
$stmt->execute();
$result = $stmt->get_result();

// Check if the student exists
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $student_id = $student['id']; // Student ID retrieved from the students table
} else {
    die("Student record not found.");
}

// Query to get attendance records for the student
$attendance_query = "SELECT attendance_month, attendance_value, attendance_date 
                     FROM attendance 
                     WHERE student_id = ? 
                     ORDER BY attendance_date DESC"; // Order by date to show recent attendance first

$stmt = $db_conn->prepare($attendance_query);
$stmt->bind_param("i", $student_id); // Bind student_id to the query
$stmt->execute();
$attendance_result = $stmt->get_result();

// Check if the student has any attendance records
?>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fc;
        margin: 0;
        padding: 0;
    }
    h2 {
        text-align: center;
        color: #2c3e50;
        margin-top: 30px;
    }
    table {
        width: 80%;
        margin: 30px auto;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }
    th, td {
        padding: 12px 20px;
        text-align: center;
        border: 1px solid #ddd;
    }
    th {
        background-color: #3498db;
        color: white;
        font-size: 16px;
    }
    td {
        color: #7f8c8d;
        font-size: 14px;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #ecf0f1;
    }
    .no-records {
        text-align: center;
        font-size: 18px;
        color: #e74c3c;
    }
</style>

<?php
// Display attendance records if available
if ($attendance_result->num_rows > 0) {
    echo "<h2>Your Attendance Records</h2>";
    echo "<table>";
    echo "<tr><th>Month</th><th>Attendance Status</th><th>Attendance Date</th></tr>";

    // Display each attendance record
    while ($attendance = $attendance_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $attendance['attendance_month'] . "</td>";
        echo "<td>" . ucfirst($attendance['attendance_value']) . "</td>";
        echo "<td>" . $attendance['attendance_date'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<div class='no-records'>No attendance records found for you.</div>";
}

// Close the database connection
$db_conn->close();
?>
<?php include('footer.php') ?>
