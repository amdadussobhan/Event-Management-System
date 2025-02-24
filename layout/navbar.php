<nav class="navbar navbar-expand-lg bg-info">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>events/event_list_page.php"><img src="<?php echo BASE_URL; ?>Logo.png" style="height: 44px;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['role'])):
                    if ($_SESSION['role'] == "admin"): ?>
                        <li class="nav-item">
                            <a class="nav-link py-3" href="<?php echo BASE_URL; ?>index.php"><b><i class="fa-solid fa-list-check pe-2"></i>Dashboard</b></a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link py-3" href="<?php echo BASE_URL; ?>events/my_events_page.php"><b><i class="fa-solid fa-bookmark pe-2"></i>My Event</b></a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link py-3" href="<?php echo BASE_URL; ?>events/my_events_page.php"><b><i class="fa-solid fa-bookmark pe-2"></i>My Event</b></a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link py-3" href="<?php echo BASE_URL; ?>events/event_list_page.php"><b><i class="fa-solid fa-calendar-days pe-2"></i>ALL Event</b></a>
                </li>
            </ul>

            <h2 id="navbar_heading" class="ms-auto">Event Management System</h2>

            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['role'])):
                    if ($_SESSION['role'] == "admin"): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"><b><?php echo $_SESSION['name']; ?></b><i class="fa-solid fa-user ms-2"></i></a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile/update_profile_page.php">My Profile</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile/userprofile_list_page.php">Users List</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>profile/update_profile_page.php"><b><?php echo $_SESSION['name']; ?></b><i class="fa-solid fa-user ms-2"></i></a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>auth/logout_action.php"><b>Logout</b><i class="fa-solid fa-power-off ms-2"></i></a>
                    </li>
                <?php else: ?>
                    <li id="login_button" class="nav-item d-flex ms-4">
                        <a class="nav-link mx-2" href="<?php echo BASE_URL; ?>auth/login_form.php"><b><i class="fa-solid fa-right-to-bracket pe-2"></i>Login</b></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>auth/signup_form.php"><b><i class="fa-solid fa-user-plus pe-2"></i>Sign up</b></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>