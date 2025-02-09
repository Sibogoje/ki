<?php
require_once 'session.php';
?>

<!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Kwakha Indvodza</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">0</span>
          </a><!-- End Notification Icon -->


        </li><!-- End Notification Nav -->


        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $username; ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>


            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link " href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <!-- User Management -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#user-management-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>User Management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="user-management-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="clients.php">
                        <i class="bi bi-circle"></i><span>All Users</span>
                    </a>
                </li>
                <!--<li>-->
                <!--    <a href="counselors.php">-->
                <!--        <i class="bi bi-circle"></i><span>Counselors</span>-->
                <!--    </a>-->
                <!--</li>-->
                <li>
                    <a href="roles-permissions.php">
                        <i class="bi bi-circle"></i><span>Password Change</span>
                    </a>
                </li>
            </ul>
        </li><!-- End User Management Nav -->

        <!-- Appointment Management -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#appointments-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-calendar-check"></i><span>Appointment Management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="appointments-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="all-appointments.php">
                        <i class="bi bi-circle"></i><span>All Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="calendar-view.php">
                        <i class="bi bi-circle"></i><span>Calendar View</span>
                    </a>
                </li>
                <li>
                    <a href="pending-requests.php">
                        <i class="bi bi-circle"></i><span>Pending Requests</span>
                    </a>
                </li>
                <li>
                    <a href="reminders.php">
                        <i class="bi bi-circle"></i><span>Reminders</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Appointment Management Nav -->

        <!-- Session Management -->
        <!--<li class="nav-item">-->
        <!--    <a class="nav-link collapsed" data-bs-target="#session-management-nav" data-bs-toggle="collapse" href="#">-->
        <!--        <i class="bi bi-journal-text"></i><span>Session Management</span><i class="bi bi-chevron-down ms-auto"></i>-->
        <!--    </a>-->
        <!--    <ul id="session-management-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">-->
        <!--        <li>-->
        <!--            <a href="session-notes.php">-->
        <!--                <i class="bi bi-circle"></i><span>Session Notes</span>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--        <li>-->
        <!--            <a href="progress-tracking.php">-->
        <!--                <i class="bi bi-circle"></i><span>Progress Tracking</span>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--    </ul>-->
        <!--</li>
        <!-- End Session Management Nav -->

        <!-- Communication Tools -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#communication-tools-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-chat-dots"></i><span>Communication Tools</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="communication-tools-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="messages.php">
                        <i class="bi bi-circle"></i><span>Messages</span>
                    </a>
                </li>
                <!--<li>-->
                <!--    <a href="video-calls.php">-->
                <!--        <i class="bi bi-circle"></i><span>Video Calls</span>-->
                <!--    </a>-->
                <!--</li>-->
                <li>
                    <a href="notifications.php">
                        <i class="bi bi-circle"></i><span>Notifications</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Communication Tools Nav -->

        <!-- Content Management -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#content-management-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-collection"></i><span>Content Management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="content-management-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="resource-library.php">
                        <i class="bi bi-circle"></i><span>Resource Library</span>
                    </a>
                </li>
                <li>
                    <a href="news.php">
                        <i class="bi bi-circle"></i><span>News Content</span>
                    </a>
                </li>
                <!--<li>-->
                <!--    <a href="crisis-support.php">-->
                <!--        <i class="bi bi-circle"></i><span>Crisis Support</span>-->
                <!--    </a>-->
                <!--</li>-->
            </ul>
        </li><!-- End Content Management Nav -->

        <!-- Analytics & Reports -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#analytics-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-bar-chart"></i><span>Analytics & Reports</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="analytics-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="usage-metrics.php">
                        <i class="bi bi-circle"></i><span>Usage Metrics</span>
                    </a>
                </li>
                <li>
                    <a href="outcome-tracking.php">
                        <i class="bi bi-circle"></i><span>Outcome Tracking</span>
                    </a>
                </li>
                <li>
                    <a href="export-data.php">
                        <i class="bi bi-circle"></i><span>Export Data</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Analytics & Reports Nav -->

        <!-- Settings -->
        <!--<li class="nav-item">-->
        <!--    <a class="nav-link collapsed" data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#">-->
        <!--        <i class="bi bi-gear"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>-->
        <!--    </a>-->
        <!--    <ul id="settings-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">-->
                <!--<li>-->
                <!--    <a href="general-settings.php">-->
                <!--        <i class="bi bi-circle"></i><span>General Settings</span>-->
                <!--    </a>-->
                <!--</li>-->
                <!--<li>-->
                <!--    <a href="customization.php">-->
                <!--        <i class="bi bi-circle"></i><span>Customization</span>-->
                <!--    </a>-->
                <!--</li>-->
        <!--        <li>-->
        <!--            <a href="api-access.php">-->
        <!--                <i class="bi bi-circle"></i><span>API Access</span>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--    </ul>-->
        <!--</li><!-- End Settings Nav -->

        <!-- Help & Support -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#help-support-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-question-circle"></i><span>Help & Support</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="help-support-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="help-center.php">
                        <i class="bi bi-circle"></i><span>Help Center</span>
                    </a>
                </li>
                <li>
                    <a href="contact-support.php">
                        <i class="bi bi-circle"></i><span>Contact Support</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Help & Support Nav -->

    </ul>

</aside><!-- End Sidebar-->
