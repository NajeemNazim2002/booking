<?php
include 'db_con/db_connect.php';
session_start();

// Restrict access to trainers only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id'];

// Fetch feedback for the logged-in trainer
$sql = "
    SELECT tf.comment, tf.rating, tf.submitted_at, u.name AS user_name
    FROM trainer_feedback tf
    JOIN users u ON tf.user_id = u.user_id
    WHERE tf.trainer_id = ?
    ORDER BY tf.submitted_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $trainer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainer Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">Feedback from Users</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="card p-4 shadow-sm">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= intval($row['rating']) ?>/5</td>
                            <td><?= htmlspecialchars($row['comment']) ?></td>
                            <td><?= date("Y-m-d H:i", strtotime($row['submitted_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">No feedback available yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
