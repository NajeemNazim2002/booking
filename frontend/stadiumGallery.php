<?php
session_start();
require_once '../db_con/db_connect.php';
require_once '../Auth.php'; // Your Auth.php with requireRole() function

// Redirect to login if not user
Auth::requireRole('user');

// Get logged-in user info
$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

// Fetch all stadiums
$stmt = $conn->prepare("SELECT * FROM stadiums ORDER BY stadium_id DESC");
$stmt->execute();
$stadiums = $stmt->get_result();

// Fetch review to edit if applicable
$edit_review = null;
if (isset($_GET['review_id']) && $user_role === 'user') {
    $edit_id = intval($_GET['review_id']);
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_review = $stmt->get_result()->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Stadiums</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .star { color: #ffc107; font-size: 1.1rem; }
        .card-img-top { height: 200px; object-fit: cover; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4 text-success">🏟️ All Stadiums</h2>

    <div class="row">
        <?php while ($stadium = $stadiums->fetch_assoc()): ?>
            <div class="col-md-6 mb-5">
                <div class="card shadow-sm h-100">
                    <img src="images/<?= htmlspecialchars($stadium['image']) ?>" class="card-img-top" alt="Stadium Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($stadium['name']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($stadium['description'])) ?></p>
                        <p class="fw-bold">Rating:
                            <?php
                            $rating = round($stadium['rating']);
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<span class="star">' . ($i <= $rating ? '★' : '☆') . '</span>';
                            }
                            ?>
                            <span class="text-muted">(<?= $stadium['rating'] ?>/5)</span>
                        </p>
                        <a href="stadiumDetail.php?stadium_id=<?= $stadium['stadium_id'] ?>" class="btn btn-outline-success mb-3">View Details</a>

                        <!-- Reviews -->
                        <h6 class="mt-4">User Reviews</h6>
                        <?php
                        $review_stmt = $conn->prepare("SELECT * FROM reviews WHERE stadium_id = ? ORDER BY review_id DESC");
                        $review_stmt->bind_param("i", $stadium['stadium_id']);
                        $review_stmt->execute();
                        $reviews = $review_stmt->get_result();

                        if ($reviews->num_rows > 0):
                            while ($review = $reviews->fetch_assoc()):
                                ?>
                                <div class="border rounded p-2 mb-2 bg-light">
                                    <strong><?= htmlspecialchars($review['username']) ?></strong>
                                    <span class="text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++) {
                                            echo ($i <= $review['rating']) ? '★' : '☆';
                                        } ?>
                                    </span><br>
                                    <p class="mb-1"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>

                                    <?php if ($user_role === 'user' && $logged_in_user === $review['username']): ?>
                                        <a href="stadiumGallery.php?review_id=<?= $review['review_id'] ?>" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                        <a href="deleteReview.php?review_id=<?= $review['review_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this review?')">Delete</a>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; else: ?>
                            <p class="text-muted">No reviews yet.</p>
                        <?php endif; ?>

                        <!-- Add Review Form -->
                        <?php if ($user_role === 'user'): ?>
                            <?php
                            $check_stmt = $conn->prepare("SELECT * FROM reviews WHERE stadium_id = ? AND username = ?");
                            $check_stmt->bind_param("is", $stadium['stadium_id'], $logged_in_user);
                            $check_stmt->execute();
                            $existing = $check_stmt->get_result()->fetch_assoc();
                            ?>
                            <?php if (!$existing): ?>
                                <form action="submitReview.php" method="post" class="mt-3">
                                    <input type="hidden" name="stadium_id" value="<?= $stadium['stadium_id'] ?>">
                                    <div class="mb-2">
                                        <label for="stars" class="form-label">Your Rating</label>
                                        <select name="stars" class="form-select" required>
                                            <option value="">Select Stars</option>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="comment" class="form-label">Comment</label>
                                        <textarea name="comment" class="form-control" rows="2" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">Submit Review</button>
                                </form>
                            <?php else: ?>
                                <p class="text-success mt-3">You already reviewed this stadium.</p>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Edit Review Modal -->
<?php if ($edit_review): ?>
    <div class="modal show d-block" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">✍️ Edit Your Review</h5>
                </div>
                <form action="updateReview.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="review_id" value="<?= $edit_review['review_id'] ?>">

                        <div class="mb-3">
                            <label for="stars" class="form-label">Stars</label>
                            <select name="stars" class="form-select" required>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= $edit_review['rating'] == $i ? 'selected' : '' ?>>
                                        <?= $i ?> Star<?= $i > 1 ? 's' : '' ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea name="comment" class="form-control" rows="4" required><?= htmlspecialchars($edit_review['comment']) ?></textarea>
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
<?php endif; ?>

</body>
</html>
