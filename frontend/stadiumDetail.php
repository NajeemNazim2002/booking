<?php global $review;
if ($_SESSION['role'] === 'user' && $_SESSION['username'] === $review['username']): ?>
    <div class="mt-2">
        <a href="editReview.php?review_id=<?= $review['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
        <a href="deleteReview.php?review_id=<?= $review['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger">Delete</a>
    </div>
<?php endif; ?>

