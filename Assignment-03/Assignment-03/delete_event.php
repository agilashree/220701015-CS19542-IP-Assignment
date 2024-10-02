<?php
session_start();
include 'db.php'; // Ensure this file includes your database connection

// Check if the ID parameter is set
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Prepare the SQL statement to delete the event
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id); // Assuming 'id' is an integer

    if ($stmt->execute()) {
        // Redirect back to the admin dashboard with a success message
        header("Location: admin_dashboard.php?msg=Event deleted successfully.");
        exit;
    } else {
        // Redirect back with an error message
        header("Location: admin_dashboard.php?msg=Error deleting event: " . $stmt->error);
        exit;
    }
} else {
    // If ID is not set, redirect with an error
    header("Location: admin_dashboard.php?msg=No event ID specified.");
    exit;
}
