<?php
include 'db_connect.php';
$conn = getConnectionToDatabase();

$message = '';
$messageType = '';

if (isset($_GET['delete'])) {
    $venue_id = $_GET['delete'];
    $check = $conn->prepare("SELECT COUNT(*) as total FROM events WHERE venue_id = ?");
    $check->bind_param("i", $venue_id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();

    if ($result['total'] > 0) {
        $message = '⚠️ Cannot delete: This venue is still being used in one or more events.';
        $messageType = 'warning';
    } else {
        $stmt = $conn->prepare("DELETE FROM venues WHERE venue_id = ?");
        $stmt->bind_param("i", $venue_id);

        if ($stmt->execute()) {
            $message = '✅ Venue deleted successfully.';
            $messageType = 'success';
        } else {
            $message = '❌ Error deleting venue.';
            $messageType = 'danger';
        }
    }
}

$venues_result = $conn->query("SELECT * FROM venues");
$venues = [];
while ($row = $venues_result->fetch_assoc()) {
    $venues[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Venues</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Manage Venues</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?>"><?= $message ?></div>
        <?php endif; ?>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Venue ID</th>
                    <th>Venue Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($venues) > 0): ?>
                    <?php foreach ($venues as $venue): ?>
                        <tr>
                            <td><?= $venue['venue_id'] ?></td>
                            <td><?= htmlspecialchars($venue['venue_name']) ?></td>
                            <td><?= htmlspecialchars($venue['address']) ?></td>
                            <td><?= htmlspecialchars($venue['city']) ?></td>
                            <td><?= $venue['capacity'] ?></td>
                            <td>
                                <a href="edit_venue.php?id=<?= $venue['venue_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="manage_venues.php?delete=<?= $venue['venue_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this venue?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No venues found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="add_venue.php" class="btn btn-primary">Add New Venue</a>
    </div>
    <div class="footer">
    <p>&copy; <?= date("Y") ?> EventEase. All rights reserved.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
