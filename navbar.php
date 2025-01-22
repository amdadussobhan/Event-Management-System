<nav class="navbar navbar-expand-lg bg-info">
    <div class="container-fluid">
        <a class="navbar-brand" href="/ems/"><img src="Logo.png" style="height: 44px;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link py-3" href="/ems/"><b>Home</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3" href="events.php"><b>Events</b></a>
                </li>
            </ul>

            <h2 class="ms-auto me-5 pe-4">Event Management System</h2>

            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout_action.php"><b>Logout</b>
                        <i class="fa-solid fa-power-off ms-2"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item d-flex ">
                        <a class="nav-link" href="login_form.php"><b>Login</b></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="register_form.php"><b>Sign up</b></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>