<?php
ob_start(); // Start output buffering
include('../includes/config.php'); 
include('header.php'); 
include('sidebar.php'); 

$message = '';

// Predefined time slots
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

// Handle Form Submission for adding a new timetable entry
if (isset($_POST['submit']) && $_POST['submit'] === 'add') {
    $class_title = mysqli_real_escape_string($db_conn, $_POST['class_title']);
    $section_title = mysqli_real_escape_string($db_conn, $_POST['section_title']);
    $day = mysqli_real_escape_string($db_conn, $_POST['day']);
    $subject_id = mysqli_real_escape_string($db_conn, $_POST['subject']);
    $time_slot = mysqli_real_escape_string($db_conn, $_POST['time_slot']);

    // Check if entry already exists
    $check_query = mysqli_query($db_conn, "SELECT * FROM timetable WHERE class_title = '$class_title' AND section_title = '$section_title' AND day = '$day' AND subject_id = '$subject_id' AND time_slot = '$time_slot'");
    if (mysqli_num_rows($check_query) == 0) {
        // Insert the new timetable entry
        $query = mysqli_query($db_conn, 
            "INSERT INTO timetable (class_title, section_title, day, subject_id, time_slot) 
             VALUES ('$class_title', '$section_title', '$day', '$subject_id', '$time_slot')");
        $message = $query 
            ? '<div class="alert alert-success" id="message">Timetable entry added successfully!</div>' 
            : '<div class="alert alert-danger" id="message">Error adding timetable entry!</div>';
    } else {
        $message = '<div class="alert alert-warning" id="message">Timetable entry already exists!</div>';
    }

    header("Location: timetable.php");
    exit(); // Prevent further output after redirect
}

// Handle Deleting a Timetable Entry
if (isset($_GET['delete'])) {
    $entry_id = $_GET['delete'];

    // Delete the entry from the timetable
    $delete_query = mysqli_query($db_conn, "DELETE FROM timetable WHERE id = '$entry_id'");
    $message = $delete_query 
        ? '<div class="alert alert-success" id="message">Timetable entry deleted successfully!</div>' 
        : '<div class="alert alert-danger" id="message">Error deleting timetable entry!</div>';
}

// Fetch Timetable Entries
$timetable_query = "SELECT t.*, p.title AS class_title, s.title AS subject_title, sec.title AS section_title 
    FROM timetable t 
    JOIN posts p ON t.class_title = p.title 
    JOIN posts sec ON t.section_title = sec.title
    JOIN posts s ON t.subject_id = s.id 
    WHERE s.type = 'subject'";
$timetable_result = mysqli_query($db_conn, $timetable_query);

// Days for dropdown
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
?>

<script>
// Hide message after 3 seconds
setTimeout(() => {
    const messageElement = document.getElementById('message');
    if (messageElement) {
        messageElement.style.display = 'none';
    }
}, 3000);
</script>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Manage Timetable</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
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

        <?php if (isset($_GET['action']) && $_GET['action'] === 'add-new') { ?>
            <!-- Add Timetable Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Timetable Entry</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="class_title">Class</label>
                            <select name="class_title" class="form-control" required>
                                <option value="" selected disabled>Select Class</option>
                                <?php
                                $classes = mysqli_query($db_conn, "SELECT * FROM posts WHERE type = 'class' AND status = 'publish'");
                                while ($class = mysqli_fetch_assoc($classes)) {
                                    echo "<option value='{$class['title']}'>{$class['title']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="section_title">Section</label>
                            <select name="section_title" class="form-control" required>
                                <option value="" selected disabled>Select Section</option>
                                <?php
                                $sections = mysqli_query($db_conn, "SELECT * FROM posts WHERE type = 'section' AND status = 'publish'");
                                while ($section = mysqli_fetch_assoc($sections)) {
                                    echo "<option value='{$section['title']}'>{$section['title']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="day">Day</label>
                            <select name="day" class="form-control" required>
                                <option value="" selected disabled>Select Day</option>
                                <?php foreach ($days as $day) { ?>
                                    <option value="<?= $day ?>"><?= $day ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select name="subject" class="form-control" required>
                                <option value="" selected disabled>Select Subject</option>
                                <?php
                                $subjects = mysqli_query($db_conn, "SELECT * FROM posts WHERE type = 'subject' AND status = 'publish'");
                                while ($subject = mysqli_fetch_assoc($subjects)) {
                                    echo "<option value='{$subject['id']}'>{$subject['title']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="time_slot">Time Slot</label>
                            <select name="time_slot" class="form-control" required>
                                <option value="" selected disabled>Select Time Slot</option>
                                <?php foreach ($time_slots as $slot) { ?>
                                    <option value="<?= $slot ?>"><?= $slot ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button name="submit" value="add" class="btn btn-success">Add Entry</button>
                        <a href="timetable.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <!-- Add New Entry Button -->
            <a href="?action=add-new" class="btn btn-success mb-3">Add New Entry</a>

            <!-- Timetable Entries -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Timetable Entries</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Day</th>
                                <th>Subject</th>
                                <th>Time Slot</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($timetable_result)) { ?>
                                <tr>
                                    <td><?= $row['class_title'] ?></td>
                                    <td><?= $row['section_title'] ?></td>
                                    <td><?= $row['day'] ?></td>
                                    <td><?= $row['subject_title'] ?></td>
                                    <td><?= $row['time_slot'] ?></td>
                                    <td>
                                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?')">Delete</a>
                                    </td>
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
