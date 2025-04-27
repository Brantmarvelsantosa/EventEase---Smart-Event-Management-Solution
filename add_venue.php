<?php
include 'db_connect.php';
$conn = getConnectionToDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $venue_name = $_POST['venue_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $capacity = $_POST['capacity'];

    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM venues WHERE venue_name = ? AND address = ?");
    $checkStmt->bind_param("ss", $venue_name, $address);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        echo "<div class='alert alert-warning text-center m-3'>A venue with the same name and address already exists!</div>";
    } else {
        $result = $conn->query("SELECT venue_id FROM venues ORDER BY venue_id ASC");
        $existing_ids = [];
        while ($row = $result->fetch_assoc()) {
            $existing_ids[] = $row['venue_id'];
        }

        $new_id = 1;
        foreach ($existing_ids as $id) {
            if ($id == $new_id) {
                $new_id++;
            } else {
                break; 
            }
        }

        $stmt = $conn->prepare("INSERT INTO venues (venue_id, venue_name, address, city, capacity, created_at) VALUES (?, ?, ?, ?, ?, CURDATE())");
        $stmt->bind_param("isssi", $new_id, $venue_name, $address, $city, $capacity);

        if ($stmt->execute()) {
            header("Location: manage_venues.php");
            exit();
        } else {
            echo "<div class='alert alert-danger text-center m-3'>Error adding venue.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Venue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-lg rounded-4 p-4">
            <h2 class="mb-4">Add Venue</h2>
            <form method="post" onsubmit="return validateVenueForm()">
                <div class="mb-3">
                    <label class="form-label">Venue Name:</label>
                    <input type="text" name="venue_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address:</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">City:</label>
                    <input type="text" name="city" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacity:</label>
                    <input type="number" name="capacity" class="form-control">
                </div>
                <div class="d-flex justify-content-between">
                    <a href="manage_venues.php" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success">Add Venue</button>
                </div>
            </form>
        </div>
    </div>
    <script src="validate.js"></script>
    <div class="footer">
    <p>&copy; <?= date("Y") ?> EventEase. All rights reserved.</p>
  </div>
</body>
</html>
