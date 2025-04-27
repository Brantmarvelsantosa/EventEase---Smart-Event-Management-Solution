<?php
include 'db_connect.php';
$conn = getConnectionToDatabase();

$sql = "SELECT e.event_id, e.event_name, e.category, e.description, e.event_date, e.created_at, e.updated_at, 
               e.max_attendees, v.venue_name, v.city
        FROM events e
        JOIN venues v ON e.venue_id = v.venue_id
        ORDER BY e.event_id ASC";

$result = $conn->query($sql);
$events = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Events</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Event List</h2>
    <a href="add_event.php" class="btn btn-success">Add New Event</a>
  </div>

  <?php if (count($events) === 0): ?>
    <div class="alert alert-warning">No events found.</div>
  <?php else: ?>
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>Description</th>
          <th>Date</th>
          <th>Created At</th>
          <th>Updated At</th>
          <th>Max Attendees</th>
          <th>Venue</th>
          <th>City</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($events as $event): ?>
        <tr>
          <td><?= $event['event_id'] ?></td>
          <td><?= htmlspecialchars($event['event_name']) ?></td>
          <td><?= htmlspecialchars($event['category']) ?></td>
          <td><?= htmlspecialchars($event['description']) ?></td>
          <td><?= $event['event_date'] ?></td>
          <td><?= $event['created_at'] ?></td>
          <td><?= $event['updated_at'] ?></td>
          <td><?= $event['max_attendees'] ?></td>
          <td><?= htmlspecialchars($event['venue_name']) ?></td>
          <td><?= htmlspecialchars($event['city']) ?></td>
          <td class="text-center">
            <a href="edit_event.php?id=<?= $event['event_id'] ?>" class="btn btn-sm btn-primary mb-1">Edit</a>

            <a href="cancel_event.php?event_id=<?= $event['event_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this event?');">Cancel</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<div class="footer">
    <p>&copy; <?= date("Y") ?> EventEase. All rights reserved.</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
