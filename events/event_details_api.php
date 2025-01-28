<?php
session_start();
include '../auth/db_connect.php'; // Include database connection

// Get the event ID from the query parameters
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : null;

if (!$event_id) {    
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Event ID is required']); // If no event ID is provided, return an error response
    exit();
}

// Fetch event details
$stmt = $conn->prepare("SELECT id, title, date, max_capacity, description, cover_photo FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();

// If event is not found, return an error response
if (!$event) {
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Event not found']);
    exit();
}

// Fetch the number of registered attendees
$stmt = $conn->prepare("SELECT COUNT(*) AS participants FROM attendees WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$attendees = $result->fetch_assoc();
$stmt->close();

// Check if the current user is registered for this event
$registered = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT id FROM attendees WHERE event_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0)
        $registered = true;
    $stmt->close();
}

$conn->close(); // Close connection

// Return the event details as JSON
header('Content-Type: application/json');
echo json_encode([
    'event' => [
        'id' => $event['id'],
        'title' => htmlspecialchars($event['title']),
        'date' => $event['date'],
        'max_capacity' => $event['max_capacity'],
        'description' => nl2br(htmlspecialchars($event['description'])),
        'cover_photo' => $event['cover_photo'],
        'participants' => $attendees['participants'],
        'registered' => $registered,
    ]
]);
