<?php
session_start();
include 'db_con/db_connect.php';

// Ensure user is a manager
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Get manager's centre
$user_id = $_SESSION['user_id'];
$centre_query = "SELECT c.* FROM centres c 
                 INNER JOIN centre_managers cm ON c.centre_id = cm.centre_id 
                 WHERE cm.user_id = ?";
$stmt = $conn->prepare($centre_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$centre = $stmt->get_result()->fetch_assoc();

$msg = "";

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_trainer'])) {
        // Add new trainer
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $specialization = $_POST['specialization'];
        $experience = $_POST['experience'];
        $rate = $_POST['rate'];

        // First create user account
        $password = password_hash(generateRandomPassword(), PASSWORD_DEFAULT);
        $insert_user = "INSERT INTO users (name, email, phone, password, role, status) VALUES (?, ?, ?, ?, 'trainer', 'active')";
        $stmt = $conn->prepare($insert_user);
        $stmt->bind_param("ssss", $name, $email, $phone, $password);
        
        if ($stmt->execute()) {
            $user_id = $conn->insert_id;
            
            // Then create trainer profile
            $insert_trainer = "INSERT INTO trainers (user_id, specialization, experience_years, hourly_rate) 
                             VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_trainer);
            $stmt->bind_param("isid", $user_id, $specialization, $experience, $rate);
            
            if ($stmt->execute()) {
                $msg = "✅ Trainer added successfully!";
            } else {
                $msg = "❌ Error adding trainer profile.";
            }
        } else {
            $msg = "❌ Error creating user account.";
        }
    }
    
    if (isset($_POST['remove_trainer'])) {
        $trainer_id = $_POST['trainer_id'];
        
        // First get user_id
        $get_user = "SELECT user_id FROM trainers WHERE trainer_id = ?";
        $stmt = $conn->prepare($get_user);
        $stmt->bind_param("i", $trainer_id);
        $stmt->execute();
        $user_id = $stmt->get_result()->fetch_assoc()['user_id'];
        
        // Delete trainer profile
        $delete_trainer = "DELETE FROM trainers WHERE trainer_id = ?";
        $stmt = $conn->prepare($delete_trainer);
        $stmt->bind_param("i", $trainer_id);
        
        if ($stmt->execute()) {
            // Update user status to inactive
            $update_user = "UPDATE users SET status = 'inactive' WHERE user_id = ?";
            $stmt = $conn->prepare($update_user);
            $stmt->bind_param("i", $user_id);
            
            if ($stmt->execute()) {
                $msg = "✅ Trainer removed successfully!";
            } else {
                $msg = "❌ Error updating user status.";
            }
        } else {
            $msg = "❌ Error removing trainer.";
        }
    }
}

// Fetch trainers for this centre
$trainers_query = "SELECT t.*, u.name, u.email, u.phone 
                   FROM trainers t 
                   INNER JOIN users u ON t.user_id = u.user_id 
                   WHERE u.status = 'active'";
$trainers_result = $conn->query($trainers_query);

// Helper function
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Trainers - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="manager_dashboard.php">Manager Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="manager_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="manage_trainers.php">Manage Trainers</a></li>
                    <li class="nav-item"><a class="nav-link" href="blockmaintenancedate.php">Maintenance Dates</a></li>
                    <li class="nav-item"><a class="nav-link" href="uploadphotos.php">Upload Photos</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Manage Trainers/Coaches/Referees</h2>
                <?php if ($msg): ?>
                    <div class="alert alert-info"><?= $msg ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <!-- Add Trainer Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Add New Trainer</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Years of Experience</label>
                                <input type="number" name="experience" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hourly Rate ($)</label>
                                <input type="number" name="rate" step="0.01" class="form-control" required>
                            </div>
                            <button type="submit" name="add_trainer" class="btn btn-primary">Add Trainer</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Trainers List -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Current Trainers</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($trainers_result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Specialization</th>
                                            <th>Experience</th>
                                            <th>Rate</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($trainer = $trainers_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($trainer['name']) ?></td>
                                                <td>
                                                    <?= htmlspecialchars($trainer['email']) ?><br>
                                                    <?= htmlspecialchars($trainer['phone']) ?>
                                                </td>
                                                <td><?= htmlspecialchars($trainer['specialization']) ?></td>
                                                <td><?= $trainer['experience_years'] ?> years</td>
                                                <td>$<?= number_format($trainer['hourly_rate'], 2) ?></td>
                                                <td>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="trainer_id" value="<?= $trainer['trainer_id'] ?>">
                                                        <button type="submit" name="remove_trainer" class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('Are you sure you want to remove this trainer?')">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No trainers found</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
