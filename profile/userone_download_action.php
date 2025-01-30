<?php
session_start();  // Start the session to store error messages and form data
include '../auth/db_connect.php'; // Include database connection
include '../auth/isLogin.php';
include '../auth/isAdmin.php';

// Get the event_id from the URL or request
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

// Check if event_id is provided
if ($event_id === 0) {
    die("Invalid event ID");
}

// SQL Query to fetch participant details for the specific event
$query = "
    SELECT
        u.id as user_id,
        u.name,
        u.email,
        e.id as event_id,
        e.title,
        e.date
    FROM attendees AS a
    LEFT JOIN users AS u ON a.user_id = u.id
    LEFT JOIN events AS e ON a.event_id = e.id
    WHERE e.id = ?
    ORDER BY a.id DESC;
";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the first row to get the event title and date
$firstRow = $result->fetch_assoc();

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Set headers to force download of CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=event_' . $event_id . '_participants_list.csv');

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    $SL = 1;
    // Output column headings for the CSV
    fputcsv($output, ['Event ID', $firstRow['event_id']]);
    fputcsv($output, ['Event Date', $firstRow['date']]);
    fputcsv($output, ['Event Title', $firstRow['title']]);
    fputcsv($output, []);
    fputcsv($output, ['SL', 'User_id', 'Name', 'Email']);
    fputcsv($output, [$SL++, $firstRow['user_id'], $firstRow['name'], $firstRow['email']]);
    // Fetch rows and write them to the CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $SL++,
            $row['user_id'],
            $row['name'],
            $row['email']
        ]);
    }

    // Close the file pointer
    fclose($output);
} else {
    echo "No participants found for this event!";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
