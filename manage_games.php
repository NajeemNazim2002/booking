<?php
session_start();
include 'db_con/db_connect.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $centre_id = $_POST['centre_id'];
    $game_types = isset($_POST['game_types']) ? $_POST['game_types'] : [];
    
    // Convert game types to JSON
    $game_types_json = json_encode($game_types);
    
    // Update centre
    $update_sql = "UPDATE centres SET game_types = ? WHERE centre_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $game_types_json, $centre_id);
    
    if ($stmt->execute()) {
        $msg = "✅ Games updated successfully!";
    } else {
        $msg = "❌ Error updating games.";
    }
}

// Fetch all centres
$centres = $conn->query("SELECT * FROM centres");

// Fetch all game types
$game_types = $conn->query("SELECT * FROM game_types ORDER BY name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Games - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Manage Centre Games</h2>

        <?php if ($msg): ?>
            <div class="alert alert-info"><?= $msg ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Select Centre</label>
                        <select name="centre_id" class="form-select" required>
                            <option value="">-- Choose Centre --</option>
                            <?php while ($centre = $centres->fetch_assoc()): ?>
                                <option value="<?= $centre['centre_id'] ?>"><?= htmlspecialchars($centre['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Available Games/Sports</label>
                        <div class="row">
                            <?php while ($game = $game_types->fetch_assoc()): ?>
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="game_types[]" 
                                               value="<?= $game['game_id'] ?>" 
                                               id="game_<?= $game['game_id'] ?>">
                                        <label class="form-check-label" for="game_<?= $game['game_id'] ?>">
                                            <i class="bi <?= $game['icon'] ?>"></i>
                                            <?= htmlspecialchars($game['name']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Games</button>
                    <a href="admin.php" class="btn btn-secondary">Back to Dashboard</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
