<?php
$pageTitle = 'Dashboard | EMS';
include 'layout/header.php'; // Include the header
include 'auth/isLogin.php';
include 'auth/isAdmin.php';

// Pagination variables
$limit = 15;  // Set the number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Set default values for sorting, filtering, and pagination
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'name';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

$filter_search = isset($_GET['filter_search']) ? $_GET['filter_search'] : '';

// Date filter inputs
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query to get the total number of events
$stmt = $conn->prepare("SELECT COUNT(*) AS total_events FROM events");
$stmt->execute();
$stmt->bind_result($total_events);
$stmt->fetch();
$stmt->close();

// Query to get the total number of participants
$sql_count = "
    SELECT COUNT(*) AS total_rows
    FROM attendees as a
    LEFT JOIN users as u ON a.user_id = u.id
    LEFT JOIN events as e ON a.event_id = e.id
    WHERE (u.name LIKE ? OR u.email LIKE ? OR e.title LIKE ?)
";

if (!empty($start_date) && !empty($end_date)) {
    $sql_count .= " AND e.date BETWEEN ? AND ?";
}

$stmt = $conn->prepare($sql_count);
$search_term = '%' . $filter_search . '%';

if (!empty($start_date) && !empty($end_date)) {
    $stmt->bind_param('sssss', $search_term, $search_term, $search_term, $start_date, $end_date);
} else {
    $stmt->bind_param('sss', $search_term, $search_term, $search_term);
}

$stmt->execute();
$stmt->bind_result($total_rows);
$stmt->fetch();
$stmt->close();

// Calculate the total number of pages
$total_pages = ceil($total_rows / $limit);

// Get participants list with filtering, sorting, and pagination
$sql = "
    SELECT u.name, u.email, e.title, e.max_capacity, e.date
    FROM attendees as a
    LEFT JOIN users as u ON a.user_id = u.id
    LEFT JOIN events as e ON a.event_id = e.id
    WHERE (u.name LIKE ? OR u.email LIKE ? OR e.title LIKE ?)
";

if (!empty($start_date) && !empty($end_date)) {
    $sql .= " AND e.date BETWEEN ? AND ?";
}

$sql .= " ORDER BY $sort_by $order LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);

if (!empty($start_date) && !empty($end_date)) {
    $stmt->bind_param('sssss', $search_term, $search_term, $search_term, $start_date, $end_date);
} else {
    $stmt->bind_param('sss', $search_term, $search_term, $search_term);
}

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<div id="counter_row" class="row ms-5 ps-5 mb-2">
    <div id="counter_col" class="col ms-5 ps-5">
        <div class="card w-50 ms-5 shadow counter_card">
            <div class="card-body">
                <h4 id="card_heading" class="card-title">Participants</h4>
                <h4 class="card-title"><a href="" class="text-decoration-none"><i class="fa-solid fa-user me-3"></i><?php echo $result->num_rows; ?></a></h4>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card w-50 shadow counter_card">
            <div class="card-body">
                <h4 id="card_heading" class="card-title">Total Events</h4>
                <h4 class="card-title"><a href="events/event_list_page.php" class="text-decoration-none"><i class="fa-solid fa-calendar-days me-3"></i><?php echo $total_events; ?></a></h4>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-4 search_row">
    <div class="col-8 search_col">
        <!-- Filter and Search Form -->
        <form method="GET" class="d-flex">
            <input type="text" name="filter_search" class="form-control me-2 search_field" placeholder="Search by Email, Event or Attendee" value="<?php echo htmlspecialchars($filter_search); ?>">
            <input type="date" name="start_date" class="form-control me-2 search_field" value="<?php echo htmlspecialchars($start_date); ?>">
            <input type="date" name="end_date" class="form-control me-2 search_field" value="<?php echo htmlspecialchars($end_date); ?>">
            <button id="event_search_button" type="submit" class="btn btn-warning px-5 search_field">Search</button>
        </form>
    </div>
</div>

<div>
    <h5 id="heading_font" style="float: left;">Recent Participants</h5>
    <h5 id="heading_font" style="float: right;"><a href="events/event_create_page.php" class="text-decoration-none pe-2"><i class="fa-solid fa-plus pe-2"></i>Create New Event</a></h5>
</div>

<div id="table_size" class="text-center">
    <table id="table_font" class="table table-striped table-bordered table-hover text-center">
        <thead class="table-info align-middle">
            <tr>
                <th>SL</th>
                <th><a class="text-decoration-none" href="?sort_by=name&order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>">Participant Name<i class="fa-solid fa-sort ps-2"></i></a></th>
                <th><a class="text-decoration-none" href="?sort_by=email&order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>">Email Address<i class="fa-solid fa-sort ps-2"></i></a></th>
                <th><a class="text-decoration-none" href="?sort_by=title&order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>">Event Title<i class="fa-solid fa-sort ps-2"></i></a></th>
                <th><a class="text-decoration-none" href="?sort_by=date&order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>">Event Date<i class="fa-solid fa-sort ps-2"></i></a></th>
                <th><a class="text-decoration-none" href="?sort_by=max_capacity&order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>">Max Capacity<i class="fa-solid fa-sort ps-2"></i></a></th>
            </tr>
        </thead>
        <tbody class="table-group-divider align-middle">
            <?php $SL = $offset + 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $SL++; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['max_capacity']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Pagination Controls -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?php echo $page - 1; ?>&filter_search=<?php echo htmlspecialchars($filter_search); ?>&sort_by=<?php echo $sort_by; ?>&order=<?php echo $order; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link px-4" href="?page=<?php echo $i; ?>&filter_search=<?php echo htmlspecialchars($filter_search); ?>&sort_by=<?php echo $sort_by; ?>&order=<?php echo $order; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?php echo $page + 1; ?>&filter_search=<?php echo htmlspecialchars($filter_search); ?>&sort_by=<?php echo $sort_by; ?>&order=<?php echo $order; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<?php
include 'layout/footer.php'; // Include the footer
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
?>