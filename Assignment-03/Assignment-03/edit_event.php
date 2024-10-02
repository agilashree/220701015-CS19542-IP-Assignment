
<?php
session_start();
include 'db.php'; // Ensure this file contains your database connection setup

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Get the event ID from the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch the event details from the database
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the event exists
    if ($result->num_rows === 0) {
        echo "<div class='alert alert-danger'>Event not found!</div>";
        exit();
    }

    $event = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Invalid request!</div>";
    exit();
}

// Handle form submission for updating the event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $type = $_POST['type'];
    $ticket_price = $_POST['ticket_price'];
    $description = $_POST['description'];

    // Prepare the SQL statement for updating the event
    $stmt = $conn->prepare("UPDATE events SET title = ?, date = ?, time = ?, location = ?, type = ?, ticket_price = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssssdssi", $title, $date, $time, $location, $type, $ticket_price, $description, $event_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Event updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Event Management</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="events.php">Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Edit Event</h2>

    <!-- Form to Edit Event -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Event Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($event['date']); ?>" required>
        </div>
        <div class="form-group">
            <label for="time">Time</label>
            <input type="time" name="time" class="form-control" value="<?php echo htmlspecialchars($event['time']); ?>" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <input type="text" name="type" class="form-control" value="<?php echo htmlspecialchars($event['type']); ?>" required>
        </div>
        <div class="form-group">
            <label for="ticket_price">Ticket Price</label>
            <input type="number" name="ticket_price" class="form-control" value="<?php echo htmlspecialchars($event['ticket_price']); ?>" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required><?php echo htmlspecialchars($event['description']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
</div>

</body>
</html>
