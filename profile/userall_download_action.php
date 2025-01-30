<?php
session_start();  // Start the session to store error messages and form data
include '../auth/db_connect.php'; // Include database connection
include '../auth/isLogin.php';
include '../auth/isAdmin.php';

// SQL Query to fetch participant details
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
    ORDER BY a.id DESC;
";

// Execute the query
$result = $conn->query($query);

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Set headers to force download of CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=participants_list.csv');

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Output column headings for the CSV
    fputcsv($output, ['SL', 'User_id', 'Name', 'Email', 'Event_id', 'Event Title', 'Event Date']);
    $SL = 1;
    // Fetch rows and write them to the CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $SL++,
            $row['user_id'],
            $row['name'],
            $row['email'],
            $row['event_id'],
            $row['title'],
            $row['date']
        ]);
    }

    // Close the file pointer
    fclose($output);
} else {
    echo "No participants found!";
}

// Close the database connection
$conn->close();
?>
