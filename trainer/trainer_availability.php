<?php
include 'db_con/db_connect.php';
session_start();

// Restrict access to only trainers
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id'];
$msg = "";

// Handle availability submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $available_date = $_POST['available_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $conn->prepare("INSERT INTO trainer_availability (trainer_id, available_date, start_time, end_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $trainer_id, $available_date, $start_time, $end_time);

    if ($stmt->execute()) {
        $msg = "✅ Availability added successfully.";
    } else {
        $msg = "❌ Failed to add availability.";
    }
}

// Fetch trainer's availability
$availability_result = $conn->query("SELECT * FROM trainer_availability WHERE trainer_id = $trainer_id ORDER BY available_date, start_time");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trainer Availability</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">Set Your Availability</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info text-center"><?= $msg ?></div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card shadow-sm p-4 mb-4">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Available Date</label>
                <input type="date" name="available_date" class="form-control" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Availability</button>
        </form>
    </div>

    <!-- Availability Table -->
    <div class="card shadow-sm p-4">
        <h4 class="mb-3">Your Availability</h4>
        <?php if ($availability_result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $availability_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['available_date']) ?></td>
                        <td><?= htmlspecialchars($row['start_time']) ?></td>
                        <td><?= htmlspecialchars($row['end_time']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No availability added yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
