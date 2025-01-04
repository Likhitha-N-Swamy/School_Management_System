<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?=$site_url?>student/profile.php" class="nav-link">Profile</a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      
      <!-- Notifications Dropdown Menu -->
      
      <li class="nav-item">
        <a href="../logout.php" class="nav-link" title="Logout">
        Logout <i class="fa fa-sign-out-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=$site_url?>student/profile.php" class="brand-link">
      <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <!-- <span class="brand-image img-circle elevation-3"><i class="fa fa-user"></i></span> -->
      <span class="brand-text font-weight-light">Student Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <!-- Dashboard -->
          <li class="nav-item">
            <a href="<?=$site_url?>student/dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          
          
          <!-- Class Routine -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>
                Manage Class Routines
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="<?=$site_url?>student/timetable.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Time Table</p>
                </a>
              </li>
            </ul>
          </li>

        

          <!-- Attendance -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Manage Attendance
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>       
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=$site_url?>student/attendance.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Attendance</p>
                </a>
              </li>
            </ul>       
          </li>

       

          <!-- Study Materials -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-paste"></i>
              <p>
                Study Materials
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>  
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=$site_url?>student/study-materials.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Study Materials</p>
                </a>
              </li>
            </ul>           
          </li>

          <!-- Event -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-calendar-check"></i>
              <p>
                Manage Events
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>     
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=$site_url?>student/campus-functions.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Campus Functions</p>
                </a>
              </li>
              
            </ul>        
          </li>
          <!-- Communication -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Communications
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>    
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=$site_url?>student/message.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Messaging</p>
                </a>
              </li>
            </ul>          
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Manage password
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>    
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?=$site_url?>student/change_pass.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Change Password</p>
                </a>
              </li>
            </ul>          
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">