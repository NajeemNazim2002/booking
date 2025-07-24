<?php
include 'db_connect.php';
session_start();

// Allow only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle status update (if form submitted)
if (isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE complaint_id = ?");
    $stmt->bind_param("si", $new_status, $complaint_id);
    $stmt->execute();
}

// Fetch all complaints with user info
$result = $conn->query("
    SELECT c.complaint_id, u.name, u.email, c.message, c.status, c.submitted_at
    FROM complaints c
    JOIN users u ON c.user_id = u.user_id
    ORDER BY c.submitted_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaints - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">User Complaints</h2>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['complaint_id'] ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                                <td>
                                    <form method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="complaint_id" value="<?= $row['complaint_id'] ?>">
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="resolved" <?= $row['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                                <td><?= date("Y-m-d H:i", strtotime($row['submitted_at'])) ?></td>
                                <td></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No complaints found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
