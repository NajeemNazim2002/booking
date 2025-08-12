<?php
include 'db_con/db_connect.php';
session_start();

// Allow only trainer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'trainer') {
    header("Location: login.php");
    exit();
}

$trainer_id = $_SESSION['user_id'];
$msg = "";

// Update profile logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $name, $email, $phone, $trainer_id);

    if ($stmt->execute()) {
        $_SESSION['name'] = $name; // update session name
        $msg = "✅ Profile updated successfully.";
    } else {
        $msg = "❌ Failed to update profile.";
    }
}

// Fetch current details
$stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE user_id = ?");
$stmt->bind_param("i", $trainer_id);
$stmt->execute();
$result = $stmt->get_result();
$trainer = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trainer Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">My Profile</h2>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-info text-center"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($trainer['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($trainer['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($trainer['phone']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>
    </div>
</div>
</body>
</html>
