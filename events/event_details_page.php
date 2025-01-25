<?php
$pageTitle = 'Event Details | EMS';

// Include the header
include '../layout/header.php';

$event_id = $_GET['event_id'];

$stmt = $conn->prepare("SELECT id, title, date, max_capacity, description, cover_photo FROM events WHERE id = ? ORDER BY date ASC");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $title, $date, $max_capacity, $description, $cover_photo);
$stmt->fetch();

if ($stmt->num_rows > 0):
    $stmt->close();

    $stmt = $conn->prepare("SELECT e.id FROM events as e INNER JOIN attendees as a ON e.id = a.event_id WHERE a.event_id = ? and a.user_id = ? ORDER BY date ASC");
    $stmt->bind_param("ii", $event_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $_SESSION['registered'] = True;
    }

    // Step 1: Fetch the max_capacity of the event and the current number of registrations
    $stmt = $conn->prepare("SELECT COUNT(*) FROM attendees WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($participants);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if (!empty($title)): ?>
        <h4> Title: <?php echo $title; ?> </h4>
        <div class="overflow-hidden">
            <h5 class='pe-5 pt-2' style='float: left;'> Date: <?php echo $date; ?></h5>
            <h5 class='pt-2' style='float: left;'> Max Capacity: <?php echo $max_capacity; ?> Person</h5>
            <?php if (isset($_SESSION['registered'])): ?>
                <a class='btn btn-success px-5' style='float: right;'> You have already Registered </a>
            <?php elseif ($participants == $max_capacity): ?>
                <a class='btn btn-info px-5' style='float: right;'> Capacity Reached </a>
            <?php else: ?>
                <a href='../events/event_register_page.php?event_id=<?php echo $id ?>' class='btn btn-warning px-5' style='float: right;'> Register This Event </a>
            <?php endif; ?>
        </div>
    <?php endif;

    // Display the cover photo if available
    if (!empty($cover_photo)): ?>
        <img src='/ems/<?php echo htmlspecialchars($cover_photo) ?>' class='my-1 shadow-lg' alt='Cover Photo' style='height:250px; width:100%'>
    <?php endif;

    if (!empty($description) and $description != ""): ?>
        <p class='text-start py-2'>Description: <?php echo nl2br(htmlspecialchars($description)) ?></p>
<?php endif;
else:
    $errors['error'] = "Events not found. Please try again.";
    header('Location: event_list_page.php');
    exit();  // Stop further execution of the script
endif;

// Include the footer
include '../layout/footer.php';
unset($_SESSION['registered']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
?>