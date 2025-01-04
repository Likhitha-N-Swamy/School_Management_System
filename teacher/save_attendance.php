<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Set your database password
$dbname = "school_system"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to save attendance to the database
function saveAttendance($conn, $attendanceDate, $attendanceData) {
    $attendanceMonth = date('Y-m', strtotime($attendanceDate)); // Extract the month in YYYY-MM format

    $sql = "
        INSERT INTO attendance (attendance_month, attendance_value, student_id, attendance_date, modified_date, current_session)
        VALUES (?, ?, ?, ?, NOW(), NOW()) 
        ON DUPLICATE KEY UPDATE attendance_value = VALUES(attendance_value), modified_date = NOW()";

    $stmt = $conn->prepare($sql);

    foreach ($attendanceData as $studentId => $attendanceValue) {
        $stmt->bind_param("ssis", $attendanceMonth, $attendanceValue, $studentId, $attendanceDate);

        if (!$stmt->execute()) {
            throw new Exception("Error saving attendance: " . $stmt->error);
        }
    }

    $stmt->close();
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['attendance_date']) && isset($_POST['attendance'])) {
    $attendanceDate = $_POST['attendance_date']; // Extract the attendance date
    $attendanceData = $_POST['attendance']; // Extract the attendance data (student_id => attendance_value)

    try {
        saveAttendance($conn, $attendanceDate, $attendanceData);
        echo "Attendance saved successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}

// Close the database connection
$conn->close();
?>