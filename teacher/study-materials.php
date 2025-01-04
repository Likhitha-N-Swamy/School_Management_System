<?php
// Start output buffering and session
ob_start();
session_start();
include('../includes/config.php');
include('header.php');
include('sidebar.php');

// Database connection
$host = 'localhost';
$db = 'school_system';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch class and subject options
$classes_result = $conn->query("SELECT id, title FROM posts WHERE type = 'class'");
$subjects_result = $conn->query("SELECT id, title FROM posts WHERE type = 'subject'");

// Handle material upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['study_material'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $class_id = intval($_POST['class']);
    $subject_id = intval($_POST['subject']);
    $file_name = $_FILES['study_material']['name'];
    $file_tmp = $_FILES['study_material']['tmp_name'];

    $upload_dir = dirname(__DIR__, 2) . '/uploads/';  // Correct the upload path
    $file_path = '/uploads/' . $file_name;  // Store relative path in the database

    // Ensure the uploads directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Move uploaded file and insert record
    if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
        $sql = "INSERT INTO study_materials (title, description, file_name, file_path, class_id, subject_id, uploaded_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $title, $description, $file_name, $file_path, $class_id, $subject_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Material uploaded successfully!";
        } else {
            $_SESSION['message'] = "Database error: " . $conn->error;
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Failed to upload the file.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle material deletion
if (isset($_GET['id'])) {
    $material_id = intval($_GET['id']);

    // Fetch file path
    $sql = "SELECT file_path FROM study_materials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = dirname(__DIR__, 2) . '/uploads/' . basename($row['file_path']);  // Correct the file path

        // Delete file and database record
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $sql = "DELETE FROM study_materials WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $material_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Material deleted successfully!";
        } else {
            $_SESSION['message'] = "Error deleting material: " . $conn->error;
        }
    } else {
        $_SESSION['message'] = "Material not found.";
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch study materials with class and subject titles
$sql = "SELECT sm.*, 
        c.title AS class_title, 
        s.title AS subject_title 
        FROM study_materials sm 
        LEFT JOIN posts c ON sm.class_id = c.id 
        LEFT JOIN posts s ON sm.subject_id = s.id";
$result = $conn->query($sql);
?>

<div class="content-header" style="padding: 0; margin: 0;">
    <div class="container-fluid" style="text-align: left;">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Study Materials</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Teacher</a></li>
                    <li class="breadcrumb-item active">Study Materials</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content" style="padding: 0; margin: 0;">
    <div class="container-fluid" style="text-align: left;">
        <?php if (isset($_GET['action']) && $_GET['action'] == 'add-new') { ?>
            <div class="card">
                <div class="card-header">
                    <h3>Add New Study Material</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" placeholder="Enter the title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="class">Select Class</label>
                            <select required name="class" class="form-control">
                                <option value="">Select Class</option>
                                <?php while ($class = $classes_result->fetch_assoc()) { ?>
                                    <option value="<?= $class['id'] ?>"><?= $class['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subject">Select Subject</label>
                            <select required name="subject" class="form-control">
                                <option value="">Select Subject</option>
                                <?php while ($subject = $subjects_result->fetch_assoc()) { ?>
                                    <option value="<?= $subject['id'] ?>"><?= $subject['title'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="study_material">Upload Material</label>
                            <input type="file" name="study_material" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Study Materials</h3>
                    <div class="card-tools">
                        <a href="?action=add-new" class="btn btn-success btn-xs"><i class="fa fa-plus mr-2"></i>Add New</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Title</th>
                                <th>File Name</th>
                                <th>File</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Uploaded At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0) {
                                $count = 1;
                                while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= $count++ ?></td>
                                        <td><?= $row['title'] ?></td>
                                        <td><?= $row['file_name'] ?></td>
                                        <td><a href="<?= $row['file_path'] ?>" target="_blank">View</a></td>

                                        <td><?= $row['class_title'] ?></td>
                                        <td><?= $row['subject_title'] ?></td>
                                        <td><?= $row['uploaded_at'] ?></td>
                                        <td><a href="?id=<?= $row['id'] ?>" class="btn btn-danger">Delete</a></td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="8">No materials found</td>
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