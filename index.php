<?php
include 'db_connect.php';
$conn = getConnectionToDatabase();

$eventCount = $conn->query("SELECT COUNT(*) AS total FROM events")->fetch_assoc()['total'];
$venueCount = $conn->query("SELECT COUNT(*) AS total FROM venues")->fetch_assoc()['total'];

$recentEvents = $conn->query("
    SELECT e.event_name, e.event_date, v.venue_name 
    FROM events e 
    JOIN venues v ON e.venue_id = v.venue_id 
    ORDER BY e.event_date DESC 
    LIMIT 3
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EventEase Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .hero {
      padding: 60px 0;
      text-align: center;
      background: linear-gradient(to right, #6a11cb, #2575fc);
      color: white;
      border-radius: 12px;
    }
    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
    }
    .hero p {
      font-size: 1.25rem;
    }
    .dashboard-card {
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      transition: transform 0.2s ease;
    }
    .dashboard-card:hover {
      transform: translateY(-5px);
    }
    .cta-section {
      margin-top: 60px;
      padding: 50px;
      background: linear-gradient(to right, #ff6a00, #ee0979);
      color: white;
      border-radius: 12px;
      text-align: center;
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container mt-5">
    <div class="hero mb-5">
      <h1>Welcome to EventEase 🎉</h1>
      <p>Your Smart Event Management Solution</p>
    </div>

    <div class="row g-4 text-center">
      <div class="col-md-6 col-lg-3">
        <div class="dashboard-card bg-primary text-white">
          <h4>Add Event</h4>
          <p>Create a brand-new event for your audience</p>
          <a href="add_event.php" class="btn btn-light mt-2">Go</a>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="dashboard-card bg-success text-white">
          <h4>View Events</h4>
          <p>See all upcoming and past events</p>
          <a href="view_events.php" class="btn btn-light mt-2">Go</a>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="dashboard-card bg-secondary text-white">
          <h4>Manage Venues</h4>
          <p>Edit or delete available event venues</p>
          <a href="manage_venues.php" class="btn btn-light mt-2">Go</a>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="dashboard-card bg-warning text-dark">
          <h4>Filter</h4>
          <p>Find events or venues quickly</p>
          <a href="filter_events.php" class="btn btn-dark mt-2">Go</a>
        </div>
      </div>
    </div>

    <div class="row mt-5 text-center">
      <div class="col-md-6">
        <div class="dashboard-card bg-light">
          <h5>Total Events</h5>
          <h2 class="text-primary"><?= $eventCount ?></h2>
        </div>
      </div>
      <div class="col-md-6">
        <div class="dashboard-card bg-light">
          <h5>Total Venues</h5>
          <h2 class="text-success"><?= $venueCount ?></h2>
        </div>
      </div>
    </div>

    <div class="mt-5">
      <h3 class="mb-3">📅 Recent Events</h3>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th>Event Name</th>
              <th>Date</th>
              <th>Venue</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($event = $recentEvents->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($event['event_name']) ?></td>
                <td><?= $event['event_date'] ?></td>
                <td><?= htmlspecialchars($event['venue_name']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="cta-section">
      <h2>Ready to Host Your Next Big Event?</h2>
      <p>EventEase makes it easier than ever to manage everything in one place.</p>
      <a href="add_event.php" class="btn btn-light btn-lg mt-3">Start Now</a>
    </div>

    <div class="footer">
      <p>&copy; <?= date("Y") ?> EventEase. All rights reserved.</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
