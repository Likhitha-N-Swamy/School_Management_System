<?php include('../includes/config.php') ?>

<?php
$error = '';
if (isset($_POST['submit'])) {
  $name     = mysqli_real_escape_string($db_conn, $_POST['name']);
  $email    = mysqli_real_escape_string($db_conn, $_POST['email']);
  $password = md5($_POST['password']); // Hash the admin-provided password
  $type     = mysqli_real_escape_string($db_conn, $_POST['type']);

  $check_query = mysqli_query($db_conn, "SELECT * FROM accounts WHERE email = '$email'");
  if (mysqli_num_rows($check_query) > 0) {
      $error = 'Email already exists';
  } else {
      // Insert shared information into the accounts table
      $query = "INSERT INTO accounts (name, email, password, type) VALUES ('$name', '$email', '$password', '$type')";
      if (mysqli_query($db_conn, $query)) {
          // Get the last inserted account ID
          $account_id = mysqli_insert_id($db_conn);

          // Insert type-specific data into respective tables
         if ($type == 'student') {
              // Validate student-specific fields
              if (!isset($_POST['dob']) || !isset($_POST['class']) || !isset($_POST['section'])) {
                  die('Student-specific fields are missing.');
              }
              $dob             = mysqli_real_escape_string($db_conn, $_POST['dob']);
                $mobile          = mysqli_real_escape_string($db_conn, $_POST['mobile']);
                $address         = mysqli_real_escape_string($db_conn, $_POST['address']);
                $country         = mysqli_real_escape_string($db_conn, $_POST['country']);
                $state           = mysqli_real_escape_string($db_conn, $_POST['state']);
                $zip             = mysqli_real_escape_string($db_conn, $_POST['zip']);
                $father_name     = mysqli_real_escape_string($db_conn, $_POST['father_name']);
                $father_mobile   = mysqli_real_escape_string($db_conn, $_POST['father_mobile']);
                $mother_name     = mysqli_real_escape_string($db_conn, $_POST['mother_name']);
                $mother_mobile   = mysqli_real_escape_string($db_conn, $_POST['mother_mobile']);
                $parents_address = mysqli_real_escape_string($db_conn, $_POST['parents_address']);
                $parents_country = mysqli_real_escape_string($db_conn, $_POST['parents_country']);
                $parents_state   = mysqli_real_escape_string($db_conn, $_POST['parents_state']);
                $parents_zip     = mysqli_real_escape_string($db_conn, $_POST['parents_zip']);
                $class           = mysqli_real_escape_string($db_conn, $_POST['class']);
                $section         = mysqli_real_escape_string($db_conn, $_POST['section']);
                $doa             = mysqli_real_escape_string($db_conn, $_POST['doa']);
                $class_id = mysqli_real_escape_string($db_conn, $_POST['class']);
                $section_id = mysqli_real_escape_string($db_conn, $_POST['section']);

                $class_query = "SELECT title FROM posts WHERE id = $class_id AND type = 'class'";
                $class_result = mysqli_query($db_conn, $class_query);
                $class_title = mysqli_fetch_assoc($class_result)['title'];

                $section_query = "SELECT title FROM posts WHERE id = $section_id AND type = 'section'";
                $section_result = mysqli_query($db_conn, $section_query);
                $section_title = mysqli_fetch_assoc($section_result)['title'];

                $student_query = "INSERT INTO students (account_id, dob, mobile, address, country, state, zip, father_name, father_mobile, mother_name, mother_mobile, parents_address, parents_country, parents_state, parents_zip, class, section, doa) 
                                  VALUES ('$account_id', '$dob', '$mobile', '$address', '$country', '$state', '$zip', '$father_name', '$father_mobile', '$mother_name', '$mother_mobile', '$parents_address', '$parents_country', '$parents_state', '$parents_zip', '$class_title', '$section_title',  '$doa')";

              mysqli_query($db_conn, $student_query) or die(mysqli_error($db_conn));
          }

          // Success message and redirect
          $_SESSION['success_msg'] = 'User has been successfully registered';
          header('location: user_account.php?user=' . $type);
          exit;
      } else {
          $error = 'Failed to register the user. Please try again.';
      }
  }
}
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $query = "DELETE FROM accounts WHERE id = $id";
  if (mysqli_query($db_conn, $query)) {
      $_SESSION['success_msg'] = 'User successfully deleted.';
  } else {
      $_SESSION['error_msg'] = 'Failed to delete user: ' . mysqli_error($db_conn);
  }
  header('Location: user_account.php?user=' . $_REQUEST['user']);
  exit;
}
?>

<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Manage <?php echo ucfirst($_REQUEST['user']); ?> Accounts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Accounts</a></li>
                    <li class="breadcrumb-item active"><?php echo ucfirst($_REQUEST['user']) ?></li>
                </ol>
            </div>
            <?php if (isset($_SESSION['success_msg'])) { ?>
                <div class="col-12">
                    <small class="text-success" style="font-size:16px"><?= $_SESSION['success_msg'] ?></small>
                </div>
                <?php unset($_SESSION['success_msg']); ?>
            <?php } ?>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
      
        <?php if (isset($_GET['action']) && $_GET['action'] == 'add-new') { ?>
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        <fieldset class="border border-secondary p-3">
                            <legend class="w-auto">General Information</legend>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                            </div>
                            <input type="hidden" name="type" value="<?php echo $_REQUEST['user'] ?>">
                            <div class="form-group">
    <label>Password</label>
    <input type="password" class="form-control" name="password" placeholder="Set Password" required>
</div>



                                <?php if ($_REQUEST['user'] == 'student'){ ?>
                                <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">DOB</label>
                      <input type="date" required class="form-control" placeholder="DOB" name="dob">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">Mobile</label>
                      <input type="text" class="form-control" placeholder="Mobile" name="mobile">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">Address</label>
                      <input type="text" class="form-control" placeholder="Address" name="address">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">Country</label>
                      <input type="text" class="form-control" placeholder="Country" name="country">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">State</label>
                      <input type="text" class="form-control" placeholder="State" name="state">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">Pin/Zip Code</label>
                      <input type="text" class="form-control" placeholder="Pin/Zip Code" name="zip">
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset class="border border-secondary p-3 form-group">
                <legend class="d-inline w-auto h6">Parents Information</legend>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">Father's Name</label>
                      <input type="text" class="form-control" placeholder="Father's Name" name="father_name">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">Father's Mobile</label>
                      <input type="text" class="form-control" placeholder="Father's Mobile" name="father_mobile">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">Mother's Name</label>
                      <input type="text" class="form-control" placeholder="Mother's Name" name="mother_name">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">Mothers's Mobile</label>
                      <input type="text" class="form-control" placeholder="Mothers's Mobile" name="mother_mobile">
                    </div>
                  </div>
                  <!-- Address Fields -->
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">Address</label>
                      <input type="text" class="form-control" placeholder="Address" name="parents_address">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">Country</label>
                      <input type="text" class="form-control" placeholder="Country" name="parents_country">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">State</label>
                      <input type="text" class="form-control" placeholder="State" name="parents_state">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">Pin/Zip Code</label>
                      <input type="text" class="form-control" placeholder="Pin/Zip Code" name="parents_zip">
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset class="border border-secondary p-3 form-group">
                <legend class="d-inline w-auto h6">Last Qualification</legend>
                <div class="row">

                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">School Name</label>
                      <input type="text" class="form-control" placeholder="School Name" name="school_name">
                    </div>
                  </div>
                  <div class="col-lg">
                    <div class="form-group">
                      <label for="">Class</label>
                      <input type="text" class="form-control" placeholder="Class" name="previous_class">
                    </div>
                  </div>
                  <div class="col-lg">
                    <div class="form-group">
                      <label for="">Status</label>
                      <input type="text" class="form-control" placeholder="Status" name="status">
                    </div>
                  </div>
                  <div class="col-lg">
                    <div class="form-group">
                      <label for="">Total Marks</label>
                      <input type="text" class="form-control" placeholder="Total Marks" name="total_marks">
                    </div>
                  </div>
                  <div class="col-lg">
                    <div class="form-group">
                      <label for="">Obtain Marks</label>
                      <input type="text" class="form-control" placeholder="Obtain Marks" name="obtain_mark">
                    </div>
                  </div>
                  <div class="col-lg">
                    <div class="form-group">
                      <label for="">Percentage</label>
                      <input type="text" class="form-control" placeholder="Percentage" name="previous_percentage">
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset class="border border-secondary p-3 form-group">
                <legend class="d-inline w-auto h6">Admission Details</legend>
                <div class="row">
                  <div class="col-lg">
                    <div class="form-group">
                      <label for="">Class</label>
                      <!-- <input type="text" class="form-control" placeholder="Class" name="class"> -->

                      <select name="class" id="class" class="form-control">
                        <option value="">Select Class</option>
                        <?php
                        $args = array(
                          'type' => 'class',
                          'status' => 'publish',
                        );
                        $classes = get_posts($args);
                        foreach ($classes as $class) {
                          echo '<option value="' . $class->id . '">' . $class->title . '</option>';
                        } ?>

                      </select>
                    </div>
                  </div>
                  <div class="col-lg">
                    <div class="form-group" id="section-container">
                      <label for="section">Select Section</label>
                      <select name="section" id="section" class="form-control">
                        <option value="">-Select Section-</option>
                         <?php
                        $args = array(
                          'type' => 'section',
                          'status' => 'publish',
                        );
                        $sections = get_posts($args);
                        foreach ($sections as $section) {
                          echo '<option value="' . $section->id . '">' . $section->title . '</option>';
                        } ?>
                      </select>
                    </div>
                  <div class="col-lg">
                    <div class="form-group">
                      <label for="">Date of Admission</label>
                      <input type="date" class="form-control" placeholder="Date of Admission" name="doa">
                    </div>
                  </div>
                </div>
              </fieldset>
                                
                                <?php } ?>
                            <button type="submit" name="submit" class="btn btn-primary">Register</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List of <?php echo ucfirst($_REQUEST['user']) ?>s</h3>
                    <div class="card-tools">
                        <a href="?user=<?php echo $_REQUEST['user'] ?>&action=add-new" class="btn btn-success btn-sm">Add New</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive bg-white">
                        <table class="table table-bordered" id="users-table" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $count = 1;
                                 $type = $_REQUEST['user'];
                                 $query = mysqli_query($db_conn, "SELECT * FROM accounts WHERE type = '$type'");
                                while ($row = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                    <td><?= $count++ ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">View</a>
                                            <a href="?action=delete&id=<?php echo $row['id']; ?>&user=<?php echo $type; ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<?php include('footer.php') ?>