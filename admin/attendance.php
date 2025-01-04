<?php
include('../includes/config.php'); 
include('header.php'); 
include('sidebar.php'); 

$class = isset($_GET['class']) ? $_GET['class'] : '';
$section = isset($_GET['section']) ? $_GET['section'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Query to get all classes from the students table
$classesQuery = "SELECT DISTINCT class FROM students";
$classesResult = mysqli_query($db_conn, $classesQuery);

// Fetch sections dynamically based on the selected class
$sectionsQuery = !empty($class) ? "SELECT DISTINCT section FROM students WHERE class = '$class'" : "SELECT DISTINCT section FROM students";
$sectionsResult = mysqli_query($db_conn, $sectionsQuery);

// Query to get the attendance count for the selected date
$attendanceQuery = "
    SELECT s.class, s.section, 
           SUM(CASE WHEN a.attendance_value = 'Present' THEN 1 ELSE 0 END) AS present_count,
           SUM(CASE WHEN a.attendance_value = 'Absent' THEN 1 ELSE 0 END) AS absent_count
    FROM students s
    LEFT JOIN attendance a ON s.id = a.student_id
    WHERE s.class LIKE '%$class%' AND s.section LIKE '%$section%' 
          AND a.attendance_date = '$date'
    GROUP BY s.class, s.section
";
$attendanceResult = mysqli_query($db_conn, $attendanceQuery);

// Prepare data for the chart
$presentCount = 0;
$absentCount = 0;
if (mysqli_num_rows($attendanceResult) > 0) {
    $attendance = mysqli_fetch_assoc($attendanceResult);
    $presentCount = $attendance['present_count'];
    $absentCount = $attendance['absent_count'];
}

// Display form for filtering
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Attendance</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }
        h1 {
            margin: 0;
            font-size: 2rem;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        select, input[type="submit"], input[type="date"] {
            padding: 10px;
            font-size: 1rem;
            margin-right: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #888;
        }
        .chart-container {
            width: 50%;
            height: 400px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <h1 align="center">Attendance Management</h1>

    <div class="container">
        <!-- Filter Form -->
        <form method="GET" action="attendance.php">
            <div>
                <label for="class">Class:</label>
                <select name="class" id="class" onchange="fetchSections(this.value)">
                    <option value="">All Classes</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($classesResult)) {
                        echo "<option value='{$row['class']}'" . ($row['class'] == $class ? ' selected' : '') . ">{$row['class']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="section">Section:</label>
                <select name="section" id="section">
                    <option value="">All Sections</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($sectionsResult)) {
                        echo "<option value='{$row['section']}'" . ($row['section'] == $section ? ' selected' : '') . ">{$row['section']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" value="<?= $date ?>">
            </div>
            <input type="submit" value="Filter">
        </form>

        <!-- Attendance Table -->
        <h2>Attendance Count</h2>
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Present Count</th>
                    <th>Absent Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($attendanceResult) > 0) {
                    echo "<tr>
                        <td>{$attendance['class']}</td>
                        <td>{$attendance['section']}</td>
                        <td>{$attendance['present_count']}</td>
                        <td>{$attendance['absent_count']}</td>
                    </tr>";
                } else {
                    echo "<tr><td colspan='4' class='no-data'>No attendance data found for the selected filters.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Chart: Present vs Absent -->
        <div class="chart-container">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('attendanceChart').getContext('2d');
        var attendanceChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [<?= $presentCount; ?>, <?= $absentCount; ?>],
                    backgroundColor: ['#4CAF50', '#f44336'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    </script>
</body>
</html>

<?php
mysqli_close($db_conn);
?>
<?php include('footer.php') ?>