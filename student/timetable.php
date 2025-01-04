<?php
ob_start(); // Start output buffering

include('../includes/config.php'); 
include('header.php'); 
include('sidebar.php'); 

$message = '';
$timetable_data = [];
$time_slots = [
    "9.00-9.45", 
    "9.45-10.30", 
    "10.45-11.30", 
    "11.30-12.15", 
    "1.00-1.45", 
    "1.45-2.30", 
    "2.30-3.15",
    "3.15-4.00"
];
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

// Ensure the student is logged in
if (isset($_SESSION['user_id'])) {
    $account_id = $_SESSION['user_id'];

    // Verify the logged-in user is a student
    $account_query = "SELECT type FROM accounts WHERE id = '$account_id'";
    $account_result = mysqli_query($db_conn, $account_query);
    $account = mysqli_fetch_assoc($account_result);

    if ($account && $account['type'] === 'student') {
        // Fetch Student's Class and Section
        $student_query = "SELECT class AS class_id, section AS section_id
                          FROM students
                          WHERE account_id = '$account_id'";
        $student_result = mysqli_query($db_conn, $student_query);

        if ($student_result && mysqli_num_rows($student_result) > 0) {
            $student_row = mysqli_fetch_assoc($student_result);
            $class_id = $student_row['class_id'];
            $section_id = $student_row['section_id'];

            // Fetch Timetable Entries with subject names from the 'posts' table
            $timetable_query = "SELECT t.time_slot, t.day, p.title AS subject_title
                                FROM timetable t
                                JOIN posts p ON t.subject_id = p.id
                                WHERE t.class_title = '$class_id' AND t.section_title = '$section_id'";

            $result = mysqli_query($db_conn, $timetable_query);

            // Organize Timetable Data by Time Slot and Day
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $timetable_data[$row['time_slot']][$row['day']] = $row['subject_title'];
                }
            } else {
                $message = '<div class="alert alert-warning">No timetable entries found for your class and section.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Student information not found.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Access denied. This page is for students only.</div>';
    }
} else {
    $message = '<div class="alert alert-danger">You must be logged in to view your timetable.</div>';
}
?>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">My Timetable</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Student</a></li>
                    <li class="breadcrumb-item active">Timetable</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <?= $message ?>

        <!-- Timetable Display -->
        <?php if (!empty($timetable_data)) { ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Timetable</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Time Slot</th>
                                <?php foreach ($days as $day) { ?>
                                    <th><?= $day ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($time_slots as $slot) { ?>
                                <tr>
                                    <td><?= $slot ?></td>
                                    <?php foreach ($days as $day) { ?>
                                        <td>
                                            <?= isset($timetable_data[$slot][$day]) ? $timetable_data[$slot][$day] : '-' ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<?php include('footer.php'); ?>
<?php ob_end_flush(); ?>
