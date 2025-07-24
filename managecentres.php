<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle add centre form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_centre'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $sport_type = $_POST['sport_type'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO centres (name, location, sport_type, price_per_hour) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $name, $location, $sport_type, $price);
    $stmt->execute();
    header("Location: managecentres.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM centres WHERE centre_id = $id");
    header("Location: managecentres.php");
    exit();
}

// Fetch all centres
$result = $conn->query("SELECT * FROM centres");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Centres | Admin - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 text-center">Manage Indoor Centres</h2>

    <!-- Add Centre Button -->
    <div class="text-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCentreModal">
            <i class="bi bi-plus-circle"></i> Add Centre
        </button>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Sport Type</th>
                    <th>Price/Hour (Rs)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['centre_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['sport_type']) ?></td>
                    <td><?= number_format($row['price_per_hour'], 2) ?></td>
                    <td>
                        <a href="?delete=<?= $row['centre_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this centre?');">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Centre Modal -->
<div class="modal fade" id="addCentreModal" tabindex="-1" aria-labelledby="addCentreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCentreModalLabel">Add New Centre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label for="name" class="form-label">Centre Name</label>
            <input type="text" name="name" required class="form-control" id="name">
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" required class="form-control" id="location">
        </div>
        <div class="mb-3">
            <label for="sport_type" class="form-label">Sport Type</label>
            <input type="text" name="sport_type" class="form-control" id="sport_type">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price per Hour (Rs)</label>
            <input type="number" step="0.01" name="price" required class="form-control" id="price">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_centre" class="btn btn-success">Add Centre</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
