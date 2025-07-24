<?php
include 'db_connect.php';
session_start();

// Allow only admin or manager roles
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

// Handle block request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $centre_id = $_POST['centre_id'];
    $block_date = $_POST['block_date'];
    $reason = $_POST['reason'];

    // Prevent duplicate entries
    $check = $conn->prepare("SELECT * FROM maintenance_blocks WHERE centre_id = ? AND block_date = ?");
    $check->bind_param("is", $centre_id, $block_date);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $msg = "❌ This date is already blocked.";
    } else {
        $stmt = $conn->prepare("INSERT INTO maintenance_blocks (centre_id, block_date, reason) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $centre_id, $block_date, $reason);
        if ($stmt->execute()) {
            $msg = "✅ Date blocked successfully!";
        } else {
            $msg = "❌ Error blocking date.";
        }
    }
}

// Fetch centres
$centres = $conn->query("SELECT centre_id, name FROM centres");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Block Maintenance Date</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="text-center mb-4">Block Maintenance Date</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info text-center"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="centre_id" class="form-label">Select Indoor Centre</label>
                <select name="centre_id" class="form-select" required>
                    <option value="">-- Select Centre --</option>
                    <?php while ($row = $centres->fetch_assoc()): ?>
                        <option value="<?= $row['centre_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="block_date" class="form-label">Block Date</label>
                <input type="date" name="block_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Maintenance</label>
                <input type="text" name="reason" class="form-control" required placeholder="E.g. Court Repair, Painting">
            </div>

            <button type="submit" class="btn btn-danger w-100">Block This Date</button>
        </form>
    </div>
</div>

</body>
</html>
