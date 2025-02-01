<?php
$pageTitle = 'ALL Events | EMS';

// Include the header
include '../layout/header.php';

// Set default values for pagination, sorting, and filtering
$limit = 15; // Number of events per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

$filter_title = isset($_GET['filter_title']) ? $_GET['filter_title'] : '';

// Prepare SQL query with filtering, sorting, and pagination
$sql = "
    SELECT e.id, e.title, e.date, e.max_capacity, COUNT(a.user_id) as participants
    FROM events as e
    LEFT JOIN attendees as a on e.id = a.event_id        
    WHERE title LIKE ?
    GROUP BY e.id
    ORDER BY $sort_by $order
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$search_term = '%' . $filter_title . '%';
$stmt->bind_param('sii', $search_term, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Count total number of events for pagination
$stmt = $conn->prepare("SELECT COUNT(*) FROM events WHERE title LIKE ?");
$stmt->bind_param('s', $search_term);
$stmt->execute();
$stmt->bind_result($total_events);
$stmt->fetch();
$stmt->close();
$conn->close();

$total_pages = ceil($total_events / $limit); ?>

<div class="row justify-content-center mb-2">
    <div class="col-8">
        <!-- Filter Form -->
        <form method="GET" class="d-flex">
            <input type="text" name="filter_title" class="form-control me-2" placeholder="Search by Title" value="<?php echo htmlspecialchars($filter_title); ?>">
            <button id="event_search_button" type="submit" class="btn btn-warning px-5">Search</button>
        </form>
    </div>
</div>

<div class="row">
    <?php if (isset($_SESSION['role'])):
        if ($_SESSION['role'] == "admin"): ?>
            <div class="col">
                <h5 id="heading_font" style="float: left;">All Event List</h5>
                <h5 id="heading_font" style="float: left;"><a href="event_create_page.php" class="text-decoration-none pe-2"><i class="fa-solid fa-plus pe-2 ps-4"></i>Create New</a></h5>
                <h5 id="heading_font" style="float: right;"><a href="../profile/userall_download_action.php" class="text-decoration-none pe-2"><i class="fa-solid fa-download pe-2"></i>Download All Participants</a></h5>
            </div>
        <?php else: ?>
            <div class="col">
                <h5 id="heading_font" style="float: left;">All Event List</h5>
                <h5 id="heading_font" style="float: right;"><a href="event_create_page.php" class="text-decoration-none pe-2"><i class="fa-solid fa-plus pe-2 ps-4"></i>Create New</a></h5>
            </div>
        <?php endif;
    else: ?>
        <div class="text-center">
            <h4 id="heading_font">All Active Event List</h4>
        </div>
    <?php endif; ?>
</div>


<!-- Event Table with Sorting -->
<div id="table_size" class="text-center">
    <table id="table_font" class="table table-striped table-bordered table-hover text-center align-middle">
        <thead class="table-info">
            <tr>
                <th>SL</th>
                <th><a class="text-decoration-none" href="?sort_by=title&order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>">Title<i class="fa-solid fa-sort ps-2"></i></a></th>
                <th><a class="text-decoration-none" href="?sort_by=date&order=<?php echo $order === 'ASC' ? 'desc' : 'asc'; ?>">Date<i class="fa-solid fa-sort ps-2"></i></a></th>
                <th>Max Capacity</th>
                <th>Participants</th>
                <?php if (isset($_SESSION['role'])):
                    if ($_SESSION['role'] == "admin"): ?>
                        <th>Download</th>
                    <?php endif; ?>
                <?php endif; ?>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php $SL = $offset + 1;
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $SL++; ?></td>
                    <td><a href="event_details_page.php?event_id=<?php echo $row['id']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($row['title']); ?></a></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['max_capacity']); ?></td>
                    <td><?php echo htmlspecialchars($row['participants']); ?></td>
                    <?php if (isset($_SESSION['role'])):
                        if ($_SESSION['role'] == "admin"): ?>
                            <td><a href="../profile/userone_download_action.php?event_id=<?php echo $row['id']; ?>" class="px-2 mx-2"><i class="fa-solid fa-download"></i></a></td>
                        <?php endif; ?>
                        <td>
                            <a href="event_details_page.php?event_id=<?php echo $row['id']; ?>" class="px-2 mx-2"><i class="fa-solid fa-eye"></i></a>
                            <a href="event_update_page.php?event_id=<?php echo $row['id']; ?>" class="px-2 mx-2 text-secondary"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="event_delete_action.php?event_id=<?php echo $row['id']; ?>" class="px-2 mx-2 text-danger" onclick="return confirm('Are you sure you want to delete this Event.?');"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    <?php else: ?>
                        <td>
                            <a href="event_details_page.php?event_id=<?php echo $row['id']; ?>" class="mx-2"><i class="fa-solid fa-eye pe-2"></i>view</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?php echo $page - 1; ?>&filter_title=<?php echo htmlspecialchars($filter_title); ?>&sort_by=<?php echo $sort_by; ?>&order=<?php echo $order; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link px-4" href="?page=<?php echo $i; ?>&filter_title=<?php echo htmlspecialchars($filter_title); ?>&sort_by=<?php echo $sort_by; ?>&order=<?php echo $order; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?php echo $page + 1; ?>&filter_title=<?php echo htmlspecialchars($filter_title); ?>&sort_by=<?php echo $sort_by; ?>&order=<?php echo $order; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<?php
// Include the footer
include '../layout/footer.php';
unset($_SESSION['info']);  // Clear the info message after displaying
unset($_SESSION['errors']);  // Clear the error message after displaying
unset($_SESSION['success']);  // Clear the success message after displaying
?>