<?php
include 'db_con/db_connect.php';
session_start();

// Allow only admin or manager
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["photo"])) {
    $centre_id = $_POST['centre_id'];
    $photo = $_FILES["photo"];
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

    // Ensure upload folder exists
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Validate
    if (!in_array($photo["type"], $allowed_types)) {
        $msg = "❌ Only JPG, JPEG, PNG files are allowed.";
    } elseif ($photo["size"] > 2 * 1024 * 1024) {
        $msg = "❌ File size should be under 2MB.";
    } else {
        $filename = time() . "_" . basename($photo["name"]);
        $target = $upload_dir . $filename;

        if (move_uploaded_file($photo["tmp_name"], $target)) {
            $stmt = $conn->prepare("INSERT INTO facility_photos (centre_id, file_name) VALUES (?, ?)");
            $stmt->bind_param("is", $centre_id, $filename);
            if ($stmt->execute()) {
                $msg = "✅ Photo uploaded successfully!";
            } else {
                $msg = "❌ Failed to save photo in database.";
            }
        } else {
            $msg = "❌ Failed to move uploaded file.";
        }
    }
}

// Fetch centres
$centre_result = $conn->query("SELECT centre_id, name FROM centres");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Facility Photos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="text-center mb-4">Upload Facility Photos</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info text-center"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="centre_id" class="form-label">Select Indoor Centre</label>
                <select name="centre_id" class="form-select" required>
                    <option value="">-- Select Centre --</option>
                    <?php while ($row = $centre_result->fetch_assoc()): ?>
                        <option value="<?= $row['centre_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Upload Photo</label>
                <input type="file" name="photo" accept="image/*" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Upload</button>
        </form>
    </div>
</div>
</body>
</html>
