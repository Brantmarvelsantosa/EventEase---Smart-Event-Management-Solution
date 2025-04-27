<?php
include 'db_connect.php';
$conn = getConnectionToDatabase();

$venue = null;
$message = '';
$messageType = '';

if (isset($_GET['id'])) {
    $venue_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM venues WHERE venue_id = ?");
    $stmt->bind_param("i", $venue_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $venue = $result->fetch_assoc();

    if (!$venue) {
        $message = "Venue not found.";
        $messageType = 'danger';
    }
} else {
    $message = "Invalid venue ID.";
    $messageType = 'danger';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $venue_id = $_POST['venue_id'];
    $venue_name = trim($_POST['venue_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $capacity = $_POST['capacity'];

    $check = $conn->prepare("SELECT COUNT(*) FROM venues WHERE venue_name = ? AND address = ? AND venue_id != ?");
    $check->bind_param("ssi", $venue_name, $address, $venue_id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        $message = "A venue with the same name and address already exists.";
        $messageType = 'warning';
    } else {
        $stmt = $conn->prepare("UPDATE venues SET venue_name = ?, address = ?, city = ?, capacity = ? WHERE venue_id = ?");
        $stmt->bind_param("sssii", $venue_name, $address, $city, $capacity, $venue_id);

        if ($stmt->execute()) {
            header("Location: manage_venues.php");
            exit();
        } else {
            $message = "Error updating venue.";
            $messageType = 'danger';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Venue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4 p-4">
        <h2 class="mb-4">Edit Venue</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?>"><?= $message ?></div>
        <?php endif; ?>

        <?php if ($venue): ?>
            <form method="post" onsubmit="return validateVenueForm()">
                <input type="hidden" name="venue_id" value="<?= $venue['venue_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Venue Name</label>
                    <input type="text" name="venue_name" class="form-control" value="<?= htmlspecialchars($venue['venue_name']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($venue['address']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($venue['city']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" value="<?= $venue['capacity'] ?>">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="manage_venues.php" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Update Venue</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<div class="footer">
    <p>&copy; <?= date("Y") ?> EventEase. All rights reserved.</p>
</div>
<script src="validate.js"></script>
</body>
</html>
