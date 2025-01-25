<?php
// Check if the user is logged in as admin. redirect to the Home page.
if ($_SESSION['role'] != 'admin') {
    header('Location: /ems/events/my_events_page.php');
    exit();
}
