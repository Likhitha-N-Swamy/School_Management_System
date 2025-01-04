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

// Handle Form Submission to View Timetable
if (isset($_POST['view_timetable'])) {
    $class_title = mysqli_real_escape_string($db_conn, $_POST['class_id']);
    $section_title = mysqli_real_escape_string($db_conn, $_POST['section_id']);

    // Fetch Timetable Entries
    $timetable_query = "
        SELECT t.time_slot, t.day, p.title AS subject_title 
        FROM timetable t 
        JOIN posts p ON t.subject_id = p.id 
        WHERE t.class_title = '$class_title' AND t.section_title = '$section_title'";

    $result = mysqli_query($db_conn, $timetable_query);

    // Organize Timetable Data by Time Slot and Day
    while ($row = mysqli_fetch_assoc($result)) {
        $timetable_data[$row['time_slot']][$row['day']] = $row['subject_title'];
    }
}

// Fetch Classes
$classes_query = "SELECT title FROM posts WHERE type = 'class' AND status = 'publish'";
$classes = mysqli_query($db_conn, $classes_query);

// Fetch Sections
$sections_query = "SELECT title FROM posts WHERE type = 'section' AND status = 'publish'";
$sections = mysqli_query($db_conn, $sections_query);
?>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">View Timetable</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Teacher</a></li>
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

        <!-- Form to Select Class and Section -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Select Class and Section</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="class_id">Class</label>
                        <select name="class_id" class="form-control" required>
                            <option value="" selected disabled>Select Class</option>
                            <?php while ($class = mysqli_fetch_assoc($classes)) { ?>
                                <option value="<?= $class['title'] ?>"><?= $class['title'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="section_id">Section</label>
                        <select name="section_id" class="form-control" required>
                            <option value="" selected disabled>Select Section</option>
                            <?php while ($section = mysqli_fetch_assoc($sections)) { ?>
                                <option value="<?= $section['title'] ?>"><?= $section['title'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button name="view_timetable" value="view" class="btn btn-primary">View Timetable</button>
                </form>
            </div>
        </div>

        <!-- Timetable Display -->
        <?php if (!empty($timetable_data)) { ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Timetable</h3>
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
                                            <?= $timetable_data[$slot][$day] ?? '-' ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } elseif (isset($_POST['view_timetable'])) { ?>
            <div class="alert alert-warning">No timetable entries found for the selected class and section.</div>
        <?php } ?>
    </div>
</section>

<?php include('footer.php'); ?>
<?php ob_end_flush(); ?>
