<?php
global $conn;
session_start();
include '../db_con/db_connect.php';

// Get stadium ID
if (!isset($_GET['stadium_id'])) {
    header("Location: stediumView.php");
    exit();
}
$stadium_id = intval($_GET['stadium_id']);

// Stadium details
$stmt = $conn->prepare("SELECT * FROM stadiums WHERE id = ?");
$stmt->bind_param("i", $stadium_id);
$stmt->execute();
$result = $stmt->get_result();
$stadium = $result->fetch_assoc();

// Reviews
$review_stmt = $conn->prepare("SELECT * FROM reviews WHERE stadium_id = ? ORDER BY id DESC");
$review_stmt->bind_param("i", $stadium_id);
$review_stmt->execute();
$reviews = $review_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($stadium['name']) ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .star {
            color: #ffc107;
            font-size: 1.2rem;
        }
        .review-card {
            border-left: 5px solid #198754;
        }
        .review-username {
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">

    <!-- Stadium Info -->
    <div class="card shadow mb-5">
        <img src="images/<?= htmlspecialchars($stadium['image']) ?>" class="card-img-top" alt="Stadium Image" style="max-height: 400px; object-fit: cover;">
        <div class="card-body">
            <h2 class="card-title text-success"><?= htmlspecialchars($stadium['name']) ?></h2>
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
        </div>
    </div>

    <!-- Review Section -->
    <div class="mb-5">
        <h4 class="mb-3">🗣️ User Reviews</h4>
        <?php if ($reviews->num_rows > 0): ?>
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="card mb-3 p-3 review-card shadow-sm">
                    <div class="d-flex justify-content-between">
                        <div class="review-username"><?= htmlspecialchars($review['username']) ?></div>
                        <div>
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<span class="star">' . ($i <= $review['stars'] ? '★' : '☆') . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="mt-2"><?= nl2br(htmlspecialchars($review['comment'])) ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No reviews yet.</p>
        <?php endif; ?>
    </div>

    <!-- Review Form -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
        <div class="card p-4 shadow-sm bg-white">
            <h4 class="mb-3 text-success">✍️ Add Your Review</h4>
            <form action="submitReview.php" method="post">
                <input type="hidden" name="stadium_id" value="<?= $stadium_id ?>">
                <div class="mb-3">
                    <label for="stars" class="form-label">Rating</label>
                    <select name="stars" id="stars" class="form-select" required>
                        <option value="">-- Select Stars --</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Share your experience..." required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit Review</button>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-4">Only logged-in users can add reviews. <a href="../login.php">Login here</a></div>
    <?php endif; ?>
</div>

</body>
</html>
