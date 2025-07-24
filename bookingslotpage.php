<?php
session_start();
include 'db_connect.php';

// Allow only users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $centre_id = $_POST['centre_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Check if the slot is already booked
    $check_sql = "SELECT * FROM bookings WHERE centre_id = ? AND booking_date = ? AND (
        (start_time <= ? AND end_time > ?) OR
        (start_time < ? AND end_time >= ?)
    )";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("isssss", $centre_id, $booking_date, $start_time, $start_time, $end_time, $end_time);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $msg = "❌ Slot already booked. Choose another time.";
    } else {
        $insert_sql = "INSERT INTO bookings (user_id, centre_id, booking_date, start_time, end_time, status) VALUES (?, ?, ?, ?, ?, 'pending')";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iisss", $user_id, $centre_id, $booking_date, $start_time, $end_time);
        if ($insert_stmt->execute()) {
            $msg = "✅ Booking request submitted!";
        } else {
            $msg = "❌ Booking failed.";
        }
    }
}

// Fetch indoor centres
$centres = $conn->query("SELECT centre_id, name FROM centres");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Facility Slot - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">Book Facility Slot</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info text-center"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Indoor Centre</label>
                <select name="centre_id" class="form-select" required>
                    <option value="">-- Choose Centre --</option>
                    <?php while ($centre = $centres->fetch_assoc()): ?>
                        <option value="<?= $centre['centre_id'] ?>"><?= htmlspecialchars($centre['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Booking Date</label>
                <input type="date" name="booking_date" class="form-control" required min="<?= date('Y-m-d') ?>">
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Book Slot</button>
        </form>
    </div>
</div>
</body>
</html>
