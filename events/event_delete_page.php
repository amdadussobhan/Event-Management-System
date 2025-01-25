<?php
$pageTitle = 'Event Details | EMS';

// Include the header
include '../layout/header.php';
include '../auth/isLogin.php';
include '../auth/isAdmin.php';

$event_id = $_GET['event_id'];
$_SESSION['event_id'] = $event_id;

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
    $conn->close();

    if (!empty($title)): ?>
        <div>
            <h3 class="text-danger">Are you sure want to delete this Event ?</h3>
            <br>
            <a href="event_list_page.php" class="btn btn-primary px-5 me-5">No, Back</a>
            <a href="event_delete_action.php?event_id=<?php echo $id; ?>" class="btn btn-danger px-5 ms-5">Yes, Delete</a>
            <br>
            <br>
            <hr>
            <hr>
        </div>
        <h4 class='text-start py-1 pb-0'> Title: <?php echo $title; ?> </h4>
        <div class="overflow-hidden text-primary">
            <h5 class='pe-5' style='float: left;'> Date: <?php echo $date; ?></h5>
            <h5 style='float: left;'> Max Capacity: <?php echo $max_capacity; ?> Person</h5>
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