<?php
include 'db_connect.php';
session_start();

// Restrict access to admins only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle add new role
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_role'])) {
    $new_role = trim($_POST['new_role']);

    // Check if role already exists
    $check = $conn->prepare("SELECT * FROM roles WHERE role_name = ?");
    $check->bind_param("s", $new_role);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows > 0) {
        $msg = "⚠️ Role already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
        $stmt->bind_param("s", $new_role);
        if ($stmt->execute()) {
            $msg = "✅ Role added successfully!";
        } else {
            $msg = "❌ Error adding role: " . $conn->error;
        }
    }
}

// Handle delete role
if (isset($_GET['delete'])) {
    $role_id = $_GET['delete'];
    $conn->query("DELETE FROM roles WHERE role_id = $role_id");
    header("Location: manageroles.php");
    exit();
}

// Fetch all roles
$result = $conn->query("SELECT * FROM roles ORDER BY role_id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">Manage Roles</h2>

    <!-- Add Role Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Add New Role</div>
        <div class="card-body">
            <?php if ($msg): ?>
                <div class="alert alert-info"><?= $msg ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-10">
                        <input type="text" name="new_role" class="form-control" placeholder="Enter role name (e.g., moderator, coach)" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">Add Role</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="card">
        <div class="card-header bg-secondary text-white">Available Roles</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Role Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['role_name']) ?></td>
                                <td>
                                    <a href="?delete=<?= $row['role_id'] ?>" onclick="return confirm('Delete this role?')" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($result->num_rows === 0): ?>
                            <tr><td colspan="3" class="text-center">No roles defined.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
