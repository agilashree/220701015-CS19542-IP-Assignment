<?php
session_start();
include 'db.php';

// If the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch booked tickets from the session
$booked_tickets = isset($_SESSION['booked_tickets']) ? $_SESSION['booked_tickets'] : [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Booked Tickets</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Event Management</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="events.php">Events</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-5">
    <h2>My Booked Tickets</h2>
    
    <?php if (!empty($booked_tickets)): ?>
        <ul class="list-group">
            <?php foreach ($booked_tickets as $ticket_id): ?>
                <li class="list-group-item">Ticket for Event ID: <?php echo $ticket_id; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tickets booked yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
