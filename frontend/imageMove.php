<section class="position-relative">
    <?php
    $images = ['s1.jpg', 's2.jpg', 's3.jpg', 's4.jpg', 's5.jpg'];
    ?>

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <?php foreach ($images as $index => $img): ?>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $index ?>"
                    <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?> aria-label="Slide <?= $index+1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <?php foreach ($images as $index => $img): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="images/<?= $img ?>" class="d-block w-100" alt="Slide <?= $index+1 ?>" style="max-height: 600px; object-fit: cover;">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Text Overlay -->
    <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center text-white text-center"
         style="background: rgba(0,0,0,0.4); top: 0; left: 0; right: 0; bottom: 0;">
        <h1 class="fw-bold">Your Indoor Facility, Just a Click Away</h1>
        <p class="lead">Effortlessly book and manage your indoor sports and event facilities with our intuitive platform.</p>
    </div>
</section>
