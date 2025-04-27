<?php
include 'db_connect.php';
$conn = getConnectionToDatabase();

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']); 

    $stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        header("Location: view_events.php?message=Event+cancelled+successfully");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error cancelling the event: " . htmlspecialchars($stmt->error) . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-warning'>No event ID provided or invalid request method.</div>";
}

$conn->close();
?>
