<?php
// Include the configuration file for database connection
include('../includes/config.php'); 
?>

<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<?php
// Initialize variables
$total_students = 0;
$total_teachers = 0;
$total_subjects = 0;

// Fetch Total Students
$query = "SELECT COUNT(*) AS total_students FROM accounts WHERE type = 'student'";
$result = mysqli_query($db_conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_students = $row['total_students'];
}

// Fetch Total Teachers
$query = "SELECT COUNT(*) AS total_teachers FROM accounts WHERE type = 'teacher'";
$result = mysqli_query($db_conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_teachers = $row['total_teachers'];
}

$query = "SELECT COUNT(*) AS total_subjects FROM posts WHERE type = 'subject'";
$result = mysqli_query($db_conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_subjects = $row['total_subjects'];
}

// Fetch Total Courses

?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Admin</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- Total Students -->
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-graduation-cap"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Students</span>
            <span class="info-box-number"><?= $total_students; ?></span>
          </div>
        </div>
      </div>
      <!-- Total Teachers -->
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Teachers</span>
            <span class="info-box-number"><?= $total_teachers; ?></span>
          </div>
        </div>
      </div>
      <!-- Total Courses -->
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book-open"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Courses</span>
            <span class="info-box-number"><?= $total_subjects; ?></span>
          </div>
        </div>
      </div>
      <!-- Placeholder for New Inquiries -->
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-question"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">New Inquiries</span>
            <span class="info-box-number">0</span> <!-- Update this when you add inquiries table -->
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->

<?php include('footer.php'); ?>