<?php

$stadiums = [
    [
        'id' => 1,
        'name' => 'Golden Arena',
        'description' => 'A modern indoor stadium with premium facilities.',
        'rating' => 4.5,
        'image' => 's1.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Crystal Dome',
        'description' => 'Perfect for badminton and volleyball tournaments.',
        'rating' => 3.8,
        'image' => 's2.jpg'
    ],
    [
        'id' => 3,
        'name' => 'Arena Max',
        'description' => 'Spacious courts with professional lighting.',
        'rating' => 4.8,
        'image' => 's3.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Sunrise Hall',
        'description' => 'Ideal for practice sessions and community events.',
        'rating' => 4.2,
        'image' => 's4.jpg'
    ],
    [
        'id' => 5,
        'name' => 'Metro Zone',
        'description' => 'Budget-friendly indoor centre in town.',
        'rating' => 3.4,
        'image' => 's5.jpg'
    ]
];
?>

<div class="container my-4">
    <div id="stadiumCarousel" class="carousel slide carousel-fade shadow rounded" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($stadiums as $index => $stadium): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="position-relative">
                        <img src="images/<?= htmlspecialchars($stadium['image']) ?>" class="d-block w-100 rounded" style="height: 480px; object-fit: cover;" alt="<?= htmlspecialchars($stadium['name']) ?>">

                        <!-- Glassmorphic overlay -->
                        <div style="
                            position: absolute;
                            bottom: 0;
                            left: 0;
                            width: 100%;
                            padding: 25px;
                            background: rgba(0, 0, 0, 0.45);
                            backdrop-filter: blur(10px);
                            color: #fff;
                            border-bottom-left-radius: 0.75rem;
                            border-bottom-right-radius: 0.75rem;
                        ">
                            <h3 class="fw-bold mb-2"><?= htmlspecialchars($stadium['name']) ?></h3>
                            <p class="small mb-2"><?= htmlspecialchars($stadium['description']) ?></p>

                            <!-- Rating Stars -->
                            <div class="mb-3">
                                <?php
                                $full = floor($stadium['rating']);
                                $half = $stadium['rating'] - $full >= 0.5;
                                for ($i = 0; $i < $full; $i++) echo '<i class="bi bi-star-fill text-warning"></i>';
                                if ($half) echo '<i class="bi bi-star-half text-warning"></i>';
                                for ($i = $full + $half; $i < 5; $i++) echo '<i class="bi bi-star text-warning"></i>';
                                ?>
                                <span class="ms-2"><?= htmlspecialchars($stadium['rating']) ?>/5</span>
                            </div>

                            <?php
                                if (session_status() === PHP_SESSION_NONE) {
                                    session_start();
                                }
                                ?>

                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                                    <a href="frontend/stadiumGallery.php?stadium_id=<?php echo urlencode($stadium['id']); ?>" class="btn btn-sm btn-light fw-bold px-4">Read Reviews</a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-sm btn-secondary fw-bold px-4" onclick="alert('Please log in as a user to read reviews.'); return false;">Read Reviews</a>
                                <?php endif; ?>


                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Carousel controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#stadiumCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#stadiumCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
