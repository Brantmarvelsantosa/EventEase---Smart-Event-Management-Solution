<?php
include 'db_connect.php';
$conn = getConnectionToDatabase();

$venues_result = $conn->query("SELECT venue_id, venue_name FROM venues");
$venues = [];
while ($row = $venues_result->fetch_assoc()) {
    $venues[] = $row;
}

$event = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $event_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $event = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Event not found.</div>";
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<div class='alert alert-warning'>Invalid event ID.</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $max_attendees = $_POST['max_attendees'];
    $venue_id = $_POST['venue_id'];

    $stmt = $conn->prepare("UPDATE events SET event_name=?, category=?, description=?, event_date=?, updated_at=NOW(), max_attendees=?, venue_id=? WHERE event_id=?");
    
    $stmt->bind_param("ssssiii", $event_name, $category, $description, $event_date, $max_attendees, $venue_id, $event_id);

    if ($stmt->execute()) {
        header("Location: view_events.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating event: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="validate.js"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-lg rounded-4 p-4">
            <h2 class="mb-4">Edit Event</h2>
            <form method="post" id="edit-event-form" onsubmit="return validateEditEventForm()">
                <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                <div class="mb-3">
                    <label class="form-label">Event Name:</label>
                    <input type="text" name="event_name" class="form-control" value="<?= htmlspecialchars($event['event_name']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Category:</label>
                    <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($event['category']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description:</label>
                    <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($event['description']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Date:</label>
                    <input type="date" name="event_date" class="form-control" value="<?= $event['event_date'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Max Attendees:</label>
                    <input type="number" name="max_attendees" class="form-control" value="<?= $event['max_attendees'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Venue:</label>
                    <select name="venue_id" class="form-select">
                        <option value="">Select Venue</option>
                        <?php foreach ($venues as $venue): ?>
                            <option value="<?= $venue['venue_id'] ?>" <?= $event['venue_id'] == $venue['venue_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($venue['venue_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="view_events.php" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Update Event</button>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
    <p>&copy; <?= date("Y") ?> EventEase. All rights reserved.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
