<?php
// Start output buffering to prevent header errors
ob_start();

include('../includes/config.php');
include('header.php');
include('sidebar.php');

// Add Section
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    
    // Insert section into the 'sections' table
    $query = mysqli_query($db_conn, "INSERT INTO posts (author, title, description, type, status, parent) 
        VALUES ('1', '$title', 'description', 'section', 'publish', 0)") or die('DB error');

    // Redirect to avoid duplicate submissions
    header("Location: sections.php");
    exit();
}

// Edit Section
if (isset($_POST['edit_submit'])) {
    $section_id = $_POST['section_id'];
    $title = $_POST['edit_title'];

    if (!empty($section_id) && !empty($title)) {
        // Update the section title in the 'sections' table
        $query = mysqli_query($db_conn, "UPDATE posts SET title='$title' WHERE id='$section_id' AND type = 'section'");

        if ($query) {
            echo "<div id='edit-success' class='alert alert-success'>Edited successfully.</div>";
        } else {
            echo "<div id='edit-error' class='alert alert-danger'>Error: Unable to update the section. Please try again.</div>";
        }
    } else {
        echo "<div id='edit-error' class='alert alert-danger'>Error: Section ID or Title is empty.</div>";
    }
    header("Location: sections.php");
    exit();
}

// Delete Section
if (isset($_GET['delete_id'])) {
    $section_id = $_GET['delete_id'];

    // Delete the section from the 'sections' table
    $query = mysqli_query($db_conn, "DELETE FROM posts WHERE id='$section_id'");

    if ($query) {
        // Redirect with success message
        header("Location: sections.php?deleted=true");
        exit(); // Ensure script stops after redirect
    } else {
        die('Error: ' . mysqli_error($db_conn)); // Error handling
    }
    header("Location: sections.php");
    exit();
}
?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <h1 class="m-0 text-dark">Manage Sections</h1>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Show success message if section was deleted -->
    <?php
    if (isset($_GET['deleted']) && $_GET['deleted'] == 'true') {
        echo "<div id='delete-success' class='alert alert-success'>Section deleted successfully.</div>";
    }
    ?>

    <div class="row">
      <!-- Sections List -->
      <div class='col-lg-8'>
        <div class="card">
          <div class="card-header py-2">
            <h3 class="card-title">Sections</h3>
          </div>
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>S.No.</th>
                  <th>Title</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $count = 1;
                // Fetch sections from the 'sections' table
                $query = mysqli_query($db_conn, "SELECT * FROM posts WHERE type = 'section' AND status = 'publish'");
                while ($section = mysqli_fetch_object($query)) { ?>
                  <tr>
                    <td><?= $count++ ?></td>
                    <td><?= $section->title ?></td>
                    <td>
                      <!-- Edit Button -->
                      <a href="#editSectionModal" data-toggle="modal" data-id="<?= $section->id ?>" data-title="<?= $section->title ?>" class="btn btn-primary btn-sm">Edit</a>
                      <!-- Delete Button -->
                      <a href="?delete_id=<?= $section->id ?>" onclick="return confirm('Are you sure you want to delete this section?');" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Add New Section Form -->
      <div class="col-lg-4">
        <div class="card">
          <div class="card-header py-2">
            <h3 class="card-title">Add New Section</h3>
          </div>
          <div class="card-body">
            <form action="" method="POST">
              <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" required class="form-control" placeholder="Title">
              </div>
              <button name="submit" class="btn btn-success float-right">
                Submit
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>   
  </div><!--/. container-fluid -->
</section>
<!-- /.content -->

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editSectionModalLabel">Edit Section</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="section_id" id="editSectionId">
          <div class="form-group">
            <label for="edit_title">Title</label>
            <input type="text" name="edit_title" id="edit_title" required class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="edit_submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('footer.php') ?>

<script>
  // Pre-fill the edit form when the edit button is clicked
  $('#editSectionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var sectionId = button.data('id'); // Extract info from data-* attributes
    var title = button.data('title');
    
    var modal = $(this);
    modal.find('#editSectionId').val(sectionId); // Set the hidden input value for section_id
    modal.find('#edit_title').val(title); // Set the input value for edit_title
  });

  // Auto-hide success messages after a delay
  document.addEventListener("DOMContentLoaded", function () {
    const deleteSuccess = document.getElementById('delete-success');
    const editSuccess = document.getElementById('edit-success');

    [deleteSuccess, editSuccess].forEach(function(alert) {
      if (alert) {
        setTimeout(() => {
          alert.style.transition = "opacity 0.2s ease";
          alert.style.opacity = "0";
          setTimeout(() => alert.remove(), 100); // Remove after fade-out
        }, 2000);
      }
    });
  });
</script>
