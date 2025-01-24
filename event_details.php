<?php
session_start();
$pageTitle = 'Event Details | EMS';

// Include the header
include 'header.php';
include 'message.php';

$event_id = $_GET['event_id'];
$_SESSION['event_id'] = $event_id;

// Fetch events from the database
include 'db_connect.php';
$stmt = $conn->prepare("SELECT id, title, date, max_capacity, description, cover_photo FROM events WHERE id = ? ORDER BY date ASC");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $title, $date, $max_capacity, $description, $cover_photo);
$stmt->fetch();

if ($stmt->num_rows > 0):
    $stmt->close();

    $stmt = $conn->prepare("SELECT e.id FROM events as e INNER JOIN attendees as a ON e.id = a.event_id WHERE a.event_id = ? and a.user_id = ? ORDER BY date ASC");
    $stmt->bind_param("ii", $_SESSION['event_id'], $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        $_SESSION['registered'] = True;
        $stmt->close();
    }

    if (!empty($title)): ?>
        <h4 class='py-1'> Title: <?php echo $title; ?> </h4>
        <div class="overflow-hidden">
            <h5 class='pe-5 pt-2' style='float: left;'> Date: <?php echo $date; ?></h5>
            <h5 class='pt-2' style='float: left;'> Max Capacity: <?php echo $max_capacity; ?> Person</h5>
            <?php if (isset($_SESSION['registered'])): ?>
                <a class='btn btn-success px-5' style='float: right;'> You already Registered </a>
            <?php else: ?>
                <a href='register_form.php' class='btn btn-warning px-5' style='float: right;'> Register This Event </a>
            <?php endif; ?>
        </div>
    <?php endif;

    // Display the cover photo if available
    if (!empty($cover_photo)): ?>
        <img src='<?php echo htmlspecialchars($cover_photo) ?>' class='my-1 shadow-lg' alt='Cover Photo' style='height:333px; width:100%'>
    <?php endif;

    if (!empty($description) and $description != ""): ?>
        <p class='text-start py-2'>Description: <?php echo nl2br(htmlspecialchars($description)) ?></p>
<?php endif;
else:
    $stmt->close();
    $errors['error'] = "Events not found. Please try again.";
endif;

$conn->close();

// Include the footer
include 'footer.php';
unset($_SESSION['registered']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
?>