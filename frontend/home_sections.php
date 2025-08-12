<!-- Key Features Section -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold mb-3">Key Features</h2>
    <p class="mb-4">Discover how our platform simplifies indoor facility management.</p>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body">
            <i class="bi bi-calendar3 fs-2 mb-3"></i>
            <h5 class="card-title fw-bold">Real-Time Availability</h5>
            <p class="card-text">View up-to-the-minute availability for all facilities, ensuring you never miss a booking opportunity.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body">
            <i class="bi bi-card-checklist fs-2 mb-3"></i>
            <h5 class="card-title fw-bold">Easy Booking Management</h5>
            <p class="card-text">Manage your bookings with ease, reschedule, or cancel with just a few clicks.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body">
            <i class="bi bi-geo-alt fs-2 mb-3"></i>
            <h5 class="card-title fw-bold">Wide Selection of Facilities</h5>
            <p class="card-text">Access a diverse range of indoor facilities, from sports courts to event spaces, all in one place.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Our Facilities Section -->
<section class="py-5">
  <div class="container">
    <h2 class="fw-bold text-center mb-4">Our Facilities</h2>
    <div class="row g-4">

      <?php
      $facilities = [
        ['badminton', 'batmiton.jpeg', 'Badminton Courts', 'Well maintained indoor badminton courts available for hourly bookings.'],
        ['basketball', 'basketball.jpeg', 'Basketball Courts', 'Professional standard indoor basketball courts for training and tournaments.'],
        ['football', 'football.jpeg', 'Football Courts', 'Standard indoor football courts for friendly matches and practice.'],
        ['cricket', 'cricket.jpeg', 'Cricket Courts', 'Professional standard indoor Cricket courts for training and tournaments.'],
        ['volleyball', 'volley.jpeg', 'Volleyball Courts', 'Standard indoor volleyball courts for training and tournaments.'],
        ['tabletennis', 'tt.jpeg', 'Table Tennis Courts', 'Indoor table tennis courts with top-tier playing surfaces.']
      ];
      foreach ($facilities as $facility) {
        echo '
        <div class="col-md-4">
          <a href="facility-details.php?court=' . $facility[0] . '" class="text-decoration-none text-dark">
            <div class="card h-100">
              <img src="images/' . $facility[1] . '" class="card-img-top" alt="' . $facility[2] . '">
              <div class="card-body">
                <h5 class="card-title">' . $facility[2] . '</h5>
                <p class="card-text">' . $facility[3] . '</p>
              </div>
            </div>
          </a>
        </div>';
      }
      ?>

    </div>
  </div>
</section>

<!--add stedium views-->
<h2 class="fw-bold text-center mb-4">Our Stediums</h2>
<?php include('frontend/stediumView.php'); ?>

<!-- About Section -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h1 class="fw-bold mb-3">About Indoor Hub</h1>
    <p class="lead">Your trusted partner in indoor facility booking and management.</p>
  </div>
</section>

<!-- Our Story Section -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-md-6">
        <img src="images/storry.jpeg" class="img-fluid rounded shadow" alt="Our Story">
      </div>
      <div class="col-md-6">
        <h2 class="fw-bold mb-3">Our Story</h2>
        <p>Indoor Hub was founded to revolutionise the way people access and manage indoor facilities. We noticed the struggle faced by both facility owners and users in scheduling, availability tracking, and efficient space utilisation.</p>
        <p>With our easy-to-use platform, owners can list and manage their facilities seamlessly, while users can book with confidence and convenience.</p>
      </div>
    </div>
  </div>
</section>

<!-- Mission Section -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-md-6 order-md-2">
        <img src="images/mission.jpeg" class="img-fluid rounded shadow" alt="Our Mission">
      </div>
      <div class="col-md-6 order-md-1">
        <h2 class="fw-bold mb-3">Our Mission</h2>
        <p>Our mission is to simplify and enhance indoor facility management for everyone. We focus on transparency, accessibility, and maximising the value of every booking made through our platform.</p>
        <p>Whether you are a sports enthusiast, trainer, or event organiser, Indoor Hub ensures you spend less time managing and more time enjoying your activities.</p>
      </div>
    </div>
  </div>
</section>



<!-- Meet Our Team -->
<section class="py-5">
  <div class="container text-center">
    <h2 class="fw-bold mb-5">Meet Our Team</h2>
    <div class="row g-4">
      <?php
      $team = [
        ['nif.jpeg', 'Nifan Fernando', 'Founder & CEO'],
        ['dinu.jpeg', 'Dinuka Perera', 'Operations Manager'],
        ['saci.jpeg', 'Sachini Jayasooriya', 'Marketing Head']
      ];
      foreach ($team as $member) {
        echo '
        <div class="col-md-4">
          <div class="card border-0 shadow-sm">
            <img src="images/' . $member[0] . '" class="card-img-top" alt="' . $member[1] . '">
            <div class="card-body">
              <h5 class="card-title">' . $member[1] . '</h5>
              <p class="card-text">' . $member[2] . '</p>
            </div>
          </div>
        </div>';
      }
      ?>
    </div>
  </div>
</section>
