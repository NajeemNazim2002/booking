<?php
session_start();
include '../db_con/db_connect.php';

// Check user is logged in and has 'user' role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['review_id'])) {
    header("Location: stadiumGallery.php");
    exit();
}

$review_id = intval($_GET['review_id']);

// Optional: Verify user owns this review before deleting (recommended)
$stmt = $conn->prepare("SELECT username FROM reviews WHERE review_id = ?");
$stmt->bind_param("i", $review_id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

if (!$review || $review['username'] !== $_SESSION['username']) {
    // Not found or user is not owner, block delete
    header("Location: stadiumGallery.php");
    exit();
}

// Now delete the review
$del_stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ?");
$del_stmt->bind_param("i", $review_id);
$del_stmt->execute();

header("Location: stadiumGallery.php");
exit();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .modal-header {
            background-color: #198754;
            color: white;
        }
    </style>
</head>
<body class="bg-light">

<!-- Triggered Modal -->
<div class="modal show d-block" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title">✍️ Edit Your Review</h5>
            </div>
            <form action="updateReview.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">

                    <div class="mb-3">
                        <label for="stars" class="form-label">Stars</label>
                        <select name="stars" id="stars" class="form-select" required>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= $review['stars'] == $i ? 'selected' : '' ?>>
                                    <?= $i ?> Star<?= $i > 1 ? 's' : '' ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea name="comment" id="comment" class="form-control" rows="4" required><?= htmlspecialchars($review['comment']) ?></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="stadiumGallery.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Update Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
