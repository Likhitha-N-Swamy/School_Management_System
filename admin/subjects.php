<?php include('../includes/config.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<?php
$message = '';

// Add New Subject
if (isset($_POST['submit']) && $_POST['submit'] === 'add') {
    $subject_title = mysqli_real_escape_string($db_conn, $_POST['subject_title']);

    // Insert the subject into the 'posts' table
    $query = mysqli_query($db_conn, "INSERT INTO posts(author, title, description, type, status, parent) 
        VALUES ('1', '$subject_title', 'description', 'subject', 'publish', 0)") or die('Error: ' . mysqli_error($db_conn));

    if ($query) {
        $message = '<div id="successMessage" class="alert alert-success">Subject added successfully!</div>';
    }
}

// Delete Subject
if (isset($_GET['delete'])) {
    $subject_id = $_GET['delete'];

    // Delete the subject
    $delete_query = mysqli_query($db_conn, "DELETE FROM posts WHERE id = '$subject_id' AND type = 'subject'");
    if ($delete_query) {
        $message = '<div id="successMessage" class="alert alert-success">Subject deleted successfully!</div>';
    }
}
?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Manage Subjects</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Admin</a></li>
          <li class="breadcrumb-item active">Subjects</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Display Message -->
    <?= $message ?>

    <!-- Add Subject Form -->
    <?php if (isset($_GET['action']) && $_GET['action'] === 'add-new' && !$message) { ?>
      <div id="formCard" class="card">
        <div class="card-header py-2">
          <h3 class="card-title">Add New Subject</h3>
        </div>
        <div class="card-body">
          <form action="" method="POST">
            <div class="form-group">
              <label for="subject_title">Subject Title</label>
              <input type="text" name="subject_title" placeholder="Subject Title" required class="form-control">
            </div>
            <button name="submit" value="add" class="btn btn-success">Submit</button>
          </form>
        </div>
      </div>
    <?php } ?>

    <!-- Subject List -->
    <div class="card">
      <div class="card-header py-2">
        <h3 class="card-title">Subjects</h3>
        <div class="card-tools">
          <a href="?action=add-new" class="btn btn-success btn-xs"><i class="fa fa-plus mr-2"></i>Add New</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive bg-white">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Subject Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $count = 1;
              $subjects = mysqli_query($db_conn, "SELECT * FROM posts WHERE type = 'subject' AND status = 'publish'");
              while ($subject = mysqli_fetch_assoc($subjects)) { ?>
                <tr>
                  <td><?= $count++ ?></td>
                  <td><?= $subject['title'] ?></td>
                  <td>
                    <a href="subjects.php?delete=<?= $subject['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->

<?php include('footer.php'); ?>

<script>
  // Hide success message after 3 seconds
  <?php if ($message): ?>
    setTimeout(function() {
      document.getElementById('successMessage').style.display = 'none';
    }, 1000);  // 3000ms = 3 seconds
  <?php endif; ?>

  // Hide the Add Subject form after submission
  <?php if ($message): ?>
    document.getElementById('formCard').style.display = 'none';
  <?php endif; ?>
</script>
