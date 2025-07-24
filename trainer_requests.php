<?php
include 'db_connect.php';
session_start();

// Ensure only trainers can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id'];
$msg = "";

// Handle request update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    if (in_array($action, ['approved', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE trainer_bookings SET status = ? WHERE booking_id = ? AND trainer_id = ?");
        $stmt->bind_param("sii", $action, $booking_id, $trainer_id);
        if ($stmt->execute()) {
            $msg = "✅ Booking $action successfully.";
        } else {
            $msg = "❌ Failed to update booking status.";
        }
    }
}

// Fetch pending requests for this trainer
$query = "
    SELECT tb.booking_id, tb.status, u.name AS user_name, tb.session_date, tb.session_time
    FROM trainer_bookings tb
    JOIN users u ON tb.user_id = u.user_id
    WHERE tb.trainer_id = ?
    ORDER BY tb.session_date, tb.session_time
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $trainer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainer Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="text-center mb-4">Training Session Requests</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info text-center"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['session_date']) ?></td>
                        <td><?= htmlspecialchars($row['session_time']) ?></td>
                        <td><?= ucfirst($row['status']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'pending'): ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">
                                    <button name="action" value="approved" class="btn btn-success btn-sm">Approve</button>
                                    <button name="action" value="rejected" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">No action needed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted text-center">No booking requests found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
