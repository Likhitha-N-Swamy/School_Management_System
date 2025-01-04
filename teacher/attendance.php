<?php

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

// Function to fetch students based on class and section
function fetchStudents($conn, $class, $section) {
    $sql = "
        SELECT s.id AS student_id, a.name AS student_name 
        FROM students s 
        JOIN accounts a ON s.account_id = a.id 
        WHERE s.class = ? AND s.section = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $class, $section);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = [];

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    $stmt->close();
    return $students;
}

// Function to fetch distinct classes from the database
function fetchClasses($conn) {
    $sql = "SELECT DISTINCT class FROM students ORDER BY class ASC";
    $result = $conn->query($sql);
    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row['class'];
    }
    return $classes;
}

// Function to fetch distinct sections based on the selected class
function fetchSections($conn, $class) {
    $sql = "SELECT DISTINCT section FROM students WHERE class = ? ORDER BY section ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $class);
    $stmt->execute();
    $result = $stmt->get_result();
    $sections = [];
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row['section'];
    }
    $stmt->close();
    return $sections;
}

// Function to save attendance
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

// Handle AJAX request to fetch students
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'fetch_students') {
    $class = $_POST['class'];
    $section = $_POST['section'];
    $students = fetchStudents($conn, $class, $section);

    // Return student data as an HTML table
    foreach ($students as $student) {
        echo "
            <tr>
                <td>" . htmlspecialchars($student['student_name']) . "</td>
                <td><input type='radio' name='attendance[" . $student['student_id'] . "]' value='present' required></td>
                <td><input type='radio' name='attendance[" . $student['student_id'] . "]' value='absent' required></td>
            </tr>";
    }
    exit;
}

// Handle AJAX request to fetch sections based on class
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'fetch_sections') {
    $class = $_POST['class'];
    $sections = fetchSections($conn, $class);

    // Return sections as HTML options
    foreach ($sections as $section) {
        echo "<option value='" . htmlspecialchars($section) . "'>" . htmlspecialchars($section) . "</option>";
    }
    exit;
}

// Handle AJAX request to save attendance
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'save_attendance') {
    $attendanceDate = $_POST['attendance_date'];
    $attendanceData = $_POST['attendance'];

    try {
        saveAttendance($conn, $attendanceDate, $attendanceData);
        echo json_encode(["success" => true, "message" => "Attendance saved successfully!"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// Fetch classes for dropdown
$classes = fetchClasses($conn);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        button {
            width: 100%;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        #message {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Mark Attendance</h1>

    <!-- Form to Select Class, Section, and Date -->
    <div id="message"></div>
    <form id="attendanceForm">
        <label for="attendanceDate">Date:</label>
        <input type="date" name="attendance_date" id="attendanceDate" value="<?php echo date('Y-m-d'); ?>" required>

        <label for="class">Class:</label>
        <select name="class" id="class" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo htmlspecialchars($class); ?>"><?php echo htmlspecialchars($class); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="section">Section:</label>
        <select name="section" id="section" required>
            <option value="">Select Section</option>
        </select>

        <button type="button" id="fetchStudentsBtn">Fetch Students</button>

        <table id="studentsTable">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <button type="submit">Save Attendance</button>
    </form>

    

    <script>
        document.getElementById('class').addEventListener('change', function () {
            var className = this.value;
            if (className) {
                fetch('?', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=fetch_sections&class=' + encodeURIComponent(className)
                })
                .then(response => response.text())
                .then(data => document.getElementById('section').innerHTML = "<option value=''>Select Section</option>" + data);
            } else {
                document.getElementById('section').innerHTML = "<option value=''>Select Section</option>";
            }
        });

        document.getElementById('fetchStudentsBtn').addEventListener('click', function () {
            var className = document.getElementById('class').value;
            var section = document.getElementById('section').value;

            if (className && section) {
                fetch('?', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=fetch_students&class=' + encodeURIComponent(className) + '&section=' + encodeURIComponent(section)
                })
                .then(response => response.text())
                .then(data => document.querySelector('#studentsTable tbody').innerHTML = data);
            } else {
                alert('Please select Class and Section!');
            }
        });

        document.getElementById('attendanceForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('action', 'save_attendance');

            fetch('?', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                var messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                messageDiv.style.color = data.success ? 'green' : 'red';
                messageDiv.textContent = data.message;
                messageDiv.scrollIntoView();

                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 3000);
            });
        });
    </script>
</body>
</html>
