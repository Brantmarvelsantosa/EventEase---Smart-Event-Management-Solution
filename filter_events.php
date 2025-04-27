<?php
include 'db_connect.php';
$conn = getConnectionToDatabase();

$eventName = $_GET['event_name'] ?? '';
$selectedVenue = $_GET['venue_id'] ?? '';
$selectedDate = $_GET['event_date'] ?? '';

$query = "SELECT e.event_id, e.event_name, e.event_date, v.venue_name, v.city 
          FROM events e 
          JOIN venues v ON e.venue_id = v.venue_id 
          WHERE 1=1";

$params = [];
$types = '';

if (!empty($eventName)) {
    $query .= " AND e.event_name LIKE ?";
    $types .= 's';
    $params[] = '%' . $eventName . '%';
}
if (!empty($selectedVenue)) {
    $query .= " AND e.venue_id = ?";
    $types .= 'i';
    $params[] = $selectedVenue;
}
if (!empty($selectedDate)) {
    $query .= " AND e.event_date = ?";
    $types .= 's';
    $params[] = $selectedDate;
}

$query .= " ORDER BY e.event_id ASC";

$stmt = $conn->prepare($query);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$results = $stmt->get_result();

$venues = $conn->query("SELECT venue_id, venue_name FROM venues");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Filter Events & Venues</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container mt-5">
    <h2 class="mb-4">Filter Events & Venues</h2>

    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-4">
        <label for="event_name" class="form-label">Event Name</label>
        <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Enter event name..." value="<?= htmlspecialchars($eventName) ?>">
      </div>

      <div class="col-md-4">
        <label for="venue_id" class="form-label">Venue</label>
        <select name="venue_id" id="venue_id" class="form-select">
          <option value="">-- All Venues --</option>
          <?php while ($venue = $venues->fetch_assoc()): ?>
            <option value="<?= $venue['venue_id'] ?>" <?= $venue['venue_id'] == $selectedVenue ? 'selected' : '' ?>>
              <?= htmlspecialchars($venue['venue_name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label for="event_date" class="form-label">Event Date</label>
        <input type="date" name="event_date" id="event_date" class="form-control" value="<?= htmlspecialchars($selectedDate) ?>">
      </div>

      <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="filter_events.php" class="btn btn-secondary">Reset</a>
      </div>
    </form>

    <?php if ($results->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Event ID</th>
              <th>Event Name</th>
              <th>Date</th>
              <th>Venue</th>
              <th>City</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($event = $results->fetch_assoc()): ?>
              <tr>
                <td><?= $event['event_id'] ?></td>
                <td><?= htmlspecialchars($event['event_name']) ?></td>
                <td><?= $event['event_date'] ?></td>
                <td><?= htmlspecialchars($event['venue_name']) ?></td>
                <td><?= htmlspecialchars($event['city']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No events found for the selected filters.</div>
    <?php endif; ?>
  </div>
  <div class="footer">
    <p>&copy; <?= date("Y") ?> EventEase. All rights reserved.</p>
  </div>
</body>
</html>
