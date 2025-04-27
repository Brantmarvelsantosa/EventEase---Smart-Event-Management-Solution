<?php
include 'db_connect.php';
$conn = getConnectionToDatabase();

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_venue'])) {
    $venue_name = $_POST['venue_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $capacity = $_POST['capacity'];

    $check = $conn->prepare("SELECT * FROM venues WHERE venue_name = ?");
    $check->bind_param("s", $venue_name);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "A venue with the name '$venue_name' already exists.";
    } else {
        $result = $conn->query("SELECT MAX(venue_id) AS max_id FROM venues");
        $row = $result->fetch_assoc();
        $new_venue_id = $row['max_id'] ? $row['max_id'] + 1 : 1;

        $sql = "INSERT INTO venues (venue_id, venue_name, address, city, capacity, created_at) VALUES (?, ?, ?, ?, ?, CURDATE())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssi", $new_venue_id, $venue_name, $address, $city, $capacity);

        if ($stmt->execute()) {
            $success = "Venue added successfully!";
        } else {
            $error = "Error adding venue: " . $stmt->error;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $max_attendees = $_POST['max_attendees'];
    $venue_id = $_POST['venue_id'];

    $check = $conn->prepare("SELECT * FROM events WHERE event_name = ?");
    $check->bind_param("s", $event_name);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "An event named '$event_name' already exists.";
    } else {
        $check2 = $conn->prepare("SELECT * FROM events WHERE event_date = ? AND venue_id = ?");
        $check2->bind_param("si", $event_date, $venue_id);
        $check2->execute();
        $result2 = $check2->get_result();

        if ($result2->num_rows > 0) {
            $error = "Another event is already scheduled at this venue on $event_date.";
        } else {
            $result = $conn->query("SELECT MAX(event_id) AS max_id FROM events");
            $row = $result->fetch_assoc();
            $new_event_id = $row['max_id'] ? $row['max_id'] + 1 : 1;

            $sql = "INSERT INTO events (event_id, event_name, category, description, event_date, created_at, updated_at, max_attendees, venue_id)
                    VALUES (?, ?, ?, ?, ?, CURDATE(), CURDATE(), ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssii", $new_event_id, $event_name, $category, $description, $event_date, $max_attendees, $venue_id);

            if ($stmt->execute()) {
                $success = "Event added successfully!";
            } else {
                $error = "Error adding event: " . $stmt->error;
            }
        }
    }
}

$venues = $conn->query("SELECT * FROM venues");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Event</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="validate.js" defer></script>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container mt-5">
    <h2>Add New Event</h2>

    <?php if ($success): ?>
      <div class="alert alert-success"> <?= $success ?> </div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"> <?= $error ?> </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateEventForm();" novalidate>
      <input type="hidden" name="add_event" value="1">

      <div class="mb-3">
        <label class="form-label">Event Name</label>
        <input type="text" name="event_name" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Event Date</label>
        <input type="date" name="event_date" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Max Attendees</label>
        <input type="number" name="max_attendees" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Venue</label>
        <select name="venue_id" class="form-select">
          <option value="">-- Select Venue --</option>
          <?php while ($venue = $venues->fetch_assoc()): ?>
            <option value="<?= $venue['venue_id'] ?>"><?= $venue['venue_name'] ?> (<?= $venue['city'] ?>)</option>
          <?php endwhile; ?>
        </select>
        <div class="form-text text-muted mt-2">
          If your venue is unavailable, you can add it below!
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Add Event</button>
    </form>

    <hr>

    <h2 class="mt-4">Add New Venue</h2>
    <form method="POST" onsubmit="return validateVenueForm();" novalidate>
      <input type="hidden" name="add_venue" value="1">

      <div class="mb-3">
        <label class="form-label">Venue Name</label>
        <input type="text" name="venue_name" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Capacity</label>
        <input type="number" name="capacity" class="form-control">
      </div>
      <button type="submit" class="btn btn-success">Add Venue</button>
    </form>
  </div>

  <div class="footer">
    <p>&copy; <?= date("Y") ?> EventEase. All rights reserved.</p>
  </div>
</body>
</html>

<?php $conn->close(); ?>
