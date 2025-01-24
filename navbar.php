<nav class="navbar navbar-expand-lg bg-info">
    <div class="container-fluid">
        <a class="navbar-brand" href="event_list.php"><img src="media/Logo.png" style="height: 44px;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['role'])):
                    if ($_SESSION['role'] == "admin"): ?>
                        <li class="nav-item">
                            <a class="nav-link py-3" href="index.php"><b>Dashboard</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3" href="event_list.php"><b>ALL Event</b></a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link py-3" href="my_events.php"><b>My Event</b></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3" href="event_list.php"><b>ALL Event</b></a>
                        </li>
                    <?php endif;
                else: ?>
                    <li class="nav-item">
                        <a class="nav-link py-3" href="my_events.php"><b>My Event</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3" href="event_list.php"><b>ALL Event</b></a>
                    </li>
                <?php endif; ?>
            </ul>

            <h2 class="ms-auto">Event Management System</h2>

            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="my_rofile.php"><b><?php echo $_SESSION['name']; ?></b><i class="fa-solid fa-user ms-2"></i></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="logout_action.php"><b>Logout</b><i class="fa-solid fa-power-off ms-2"></i></a>
                    </li>
                <?php else: ?>
                    <li class="nav-item d-flex ms-4">
                        <a class="nav-link ms-5 ps-5" href="login_form.php"><b>Login</b></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="signup_form.php"><b>Sign up</b></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>