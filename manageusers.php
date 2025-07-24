<?php
include 'db_connect.php';
session_start();

// Restrict access to admins only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE user_id = $user_id");
    header("Location: manageusers.php");
    exit();
}

// Handle Add User
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, role, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $role, $password);

    if ($stmt->execute()) {
        $msg = "✅ User added successfully!";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}

// Fetch all users
$result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">Manage Users</h2>

    <!-- Add User Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Add New User</div>
        <div class="card-body">
            <?php if ($msg): ?>
                <div class="alert alert-info"><?= $msg ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="col-md-4">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                    </div>
                    <div class="col-md-4">
                        <select name="role" class="form-select" required>
                            <option value="">-- Select Role --</option>
                            <option value="admin">Administrator</option>
                            <option value="manager">Centre Manager</option>
                            <option value="trainer">Trainer</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success w-100">Add User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- User Table -->
    <div class="card">
        <div class="card-header bg-secondary text-white">All Registered Users</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= ucfirst($row['role']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                <td><?= ucfirst($row['status']) ?></td>
                                <td><?= $row['created_at'] ?? 'N/A' ?></td>
                                <td>
                                    <a href="?delete=<?= $row['user_id'] ?>" onclick="return confirm('Delete this user?')" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($result->num_rows === 0): ?>
                            <tr><td colspan="8" class="text-center">No users found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</body>
</html>
