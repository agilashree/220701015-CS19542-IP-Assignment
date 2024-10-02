<?php
session_start();
include 'db.php';

// If the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize filtering variables
$date_filter = '';
$location_filter = '';

// Handle filters
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_filter = isset($_POST['date']) ? $_POST['date'] : '';
    $location_filter = isset($_POST['location']) ? $_POST['location'] : '';
}

// Fetch events from the database with filtering
$sql = "SELECT * FROM events WHERE 1=1";
if (!empty($date_filter)) {
    $sql .= " AND date = '" . $conn->real_escape_string($date_filter) . "'";
}
if (!empty($location_filter)) {
    $sql .= " AND location = '" . $conn->real_escape_string($location_filter) . "'";
}

$result = $conn->query($sql);

// Check if any results are returned
if ($result === false) {
    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Event Management</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active"><a class="nav-link" href="events.php">Events</a></li>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-item"><a class="nav-link" href="login.php">Login/Register</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Available Events</h2>

    <form method="POST" class="mb-3">
        <div class="form-row">
            <div class="col">
                <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date_filter); ?>" placeholder="Filter by date">
            </div>
            <div class="col">
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($location_filter); ?>" placeholder="Filter by location">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <?php while($event = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                    <p class="card-text">Date: <?php echo htmlspecialchars($event['date']); ?></p>
                    <p class="card-text">Type: <?php echo htmlspecialchars($event['type']); ?></p>
                    <p class="card-text">Location: <?php echo htmlspecialchars($event['location']); ?></p>
                    <p class="card-text">Description: <?php echo htmlspecialchars($event['description']); ?></p>
                    <p class="card-text">Ticket Price: <?php echo htmlspecialchars($event['ticket_price']); ?></p>
                    <p class="card-text">Time: <?php echo htmlspecialchars($event['time']); ?></p>
                    <form method="POST" action="book_ticket.php">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" class="btn btn-success">Book Ticket</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No events found.</p>
    <?php endif; ?>
</div>

</body>
</html>
