<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    // Retrieve event details
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the event exists
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        // Handle case where event is not found
        die("Event not found.");
    }

    // Here, you can add code to save the booking to a database or perform other actions

    echo "Your ticket for " . htmlspecialchars($event['title']) . " has been booked!<br>";
    echo "Date: " . htmlspecialchars($event['date']) . "<br>";
    echo "Type: " . htmlspecialchars($event['type']) . "<br>";
    echo "Location: " . htmlspecialchars($event['location']) . "<br>";
} else {
    echo "Invalid request.";
}
?>
<a href="events.php">Back to Events</a>
