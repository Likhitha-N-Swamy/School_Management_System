<?php include('../includes/config.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<?php
$success_message = '';

// Add New Class
if (isset($_POST['submit']) && $_POST['submit'] === 'add') {
    $title = mysqli_real_escape_string($db_conn, $_POST['title']);
    $sections = $_POST['section'] ?? [];

    // Insert the class into the 'posts' table
    $query = mysqli_query($db_conn, "INSERT INTO posts(author, title, description, type, status, parent) 
        VALUES ('1', '$title', 'description', 'class', 'publish', 0)") or die('Error: ' . mysqli_error($db_conn));

    if ($query) {
        $post_id = mysqli_insert_id($db_conn);

        // Insert selected sections into the 'metadata' table
        foreach ($sections as $value) {
            mysqli_query($db_conn, "INSERT INTO metadata (item_id, meta_key, meta_value) 
                VALUES ('$post_id', 'section', '$value')") or die('Error: ' . mysqli_error($db_conn));
        }

        $success_message = "Class added successfully!";
    }
}

// Edit Class
if (isset($_GET['edit'])) {
    $class_id = $_GET['edit'];

    // Fetch class details
    $class_query = mysqli_query($db_conn, "SELECT * FROM posts WHERE id = '$class_id' AND type = 'class'");
    $class = mysqli_fetch_assoc($class_query);

    if (!$class) {
        echo "<script>alert('Class not found!');</script>";
        exit;
    }

    // Fetch associated sections
    $sections_query = mysqli_query($db_conn, "SELECT meta_value FROM metadata WHERE item_id = '$class_id' AND meta_key = 'section'");
    $selected_sections = [];
    while ($meta = mysqli_fetch_assoc($sections_query)) {
        $selected_sections[] = $meta['meta_value'];
    }

    // Handle the form submission for updating class
    if (isset($_POST['submit']) && $_POST['submit'] === 'edit') {
        $title = mysqli_real_escape_string($db_conn, $_POST['title']);
        $sections = $_POST['section'] ?? [];

        // Update the class title
        $update_query = mysqli_query($db_conn, "UPDATE posts SET title = '$title' WHERE id = '$class_id'");
        if (!$update_query) {
            echo "<script>alert('Error updating class title');</script>";
        }

        // Clear existing sections
        mysqli_query($db_conn, "DELETE FROM metadata WHERE item_id = '$class_id' AND meta_key = 'section'");

        // Insert updated sections
        foreach ($sections as $value) {
            mysqli_query($db_conn, "INSERT INTO metadata (item_id, meta_key, meta_value) 
                VALUES ('$class_id', 'section', '$value')") or die('Error: ' . mysqli_error($db_conn));
        }

        $success_message = "Class updated successfully!";
    }
}

// Delete Class
if (isset($_GET['delete'])) {
    $class_id = $_GET['delete'];

    // Delete sections for the class
    mysqli_query($db_conn, "DELETE FROM metadata WHERE item_id = '$class_id' AND meta_key = 'section'");

    // Delete the class
    mysqli_query($db_conn, "DELETE FROM posts WHERE id = '$class_id'");

    $success_message = "Class deleted successfully!";
}
?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Manage Classes</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Admin</a></li>
          <li class="breadcrumb-item active">Classes</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Show Success Message -->
    <?php if ($success_message): ?>
      <div id="successMessage" class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>

    <!-- Add/Edit Class Form -->
    <?php if (isset($_GET['action']) && $_GET['action'] === 'add-new' || isset($_GET['edit'])) { ?>
      <div id="formCard" class="card">
        <div class="card-header py-2">
          <h3 class="card-title"><?= isset($_GET['edit']) ? 'Edit Class' : 'Add New Class' ?></h3>
        </div>
        <div class="card-body">
          <form action="" method="POST">
            <div class="form-group">
              <label for="title">Class Title</label>
              <input type="text" name="title" placeholder="Class Title" value="<?= isset($class['title']) ? $class['title'] : '' ?>" required class="form-control">
            </div>

            <div class="form-group">
              <label for="section">Sections</label>
              <div>
                <label>
                  <input type="checkbox" name="section[]" value="A" <?= isset($selected_sections) && in_array('A', $selected_sections) ? 'checked' : '' ?>> Section A
                </label>
              </div>
              <div>
                <label>
                  <input type="checkbox" name="section[]" value="B" <?= isset($selected_sections) && in_array('B', $selected_sections) ? 'checked' : '' ?>> Section B
                </label>
              </div>
              <div>
                <label>
                  <input type="checkbox" name="section[]" value="C" <?= isset($selected_sections) && in_array('C', $selected_sections) ? 'checked' : '' ?>> Section C
                </label>
              </div>
            </div>

            <button name="submit" value="<?= isset($_GET['edit']) ? 'edit' : 'add' ?>" class="btn btn-success"><?= isset($_GET['edit']) ? 'Update' : 'Submit' ?></button>
          </form>
        </div>
      </div>
    <?php } ?>

    <!-- Class List -->
    <div class="card">
      <div class="card-header py-2">
        <h3 class="card-title">Classes</h3>
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
                <th>Name</th>
                <th>Sections</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $count = 1;
              $classes = mysqli_query($db_conn, "SELECT * FROM posts WHERE type = 'class' AND status = 'publish'");
              while ($class = mysqli_fetch_assoc($classes)) { ?>
                <tr>
                  <td><?= $count++ ?></td>
                  <td>Class <?= $class['title'] ?></td>
                  <td>
                    <?php
                    $sections_query = mysqli_query($db_conn, "SELECT meta_value FROM metadata WHERE item_id = '{$class['id']}' AND meta_key = 'section'");
                    while ($meta = mysqli_fetch_assoc($sections_query)) {
                      echo "Section " . strtoupper($meta['meta_value']) . " ";
                    }
                    ?>
                  </td>
                  <td>
                    <a href="classes.php?edit=<?= $class['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="classes.php?delete=<?= $class['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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
  <?php if ($success_message): ?>
    setTimeout(function() {
      document.getElementById('successMessage').style.display = 'none';
    }, 3000);  // 3000ms = 3 seconds
  <?php endif; ?>

  // Hide form after submission
  <?php if ($success_message): ?>
    document.getElementById('formCard').style.display = 'none';
  <?php endif; ?>
</script>
