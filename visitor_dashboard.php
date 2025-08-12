<?php
session_start();
include 'db_con/db_connect.php';

// Set session as visitor if not set
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'visitor';
}

// Redirect if not a visitor
if ($_SESSION['role'] !== 'visitor') {
    header("Location: login.php");
    exit();
}

// Fetch all centres
$centres_query = "SELECT * FROM centres";

// Execute the main query
$centres_result = $conn->query($centres_query);

// Function to get games for a centre
function getGamesForCentre($conn, $game_types) {
    if (empty($game_types)) return array();
    
    $game_ids = json_decode($game_types);
    if (!$game_ids) return array();
    
    $ids = implode(',', array_map('intval', $game_ids));
    $games_query = "SELECT name, icon FROM game_types WHERE game_id IN ($ids)";
    $result = $conn->query($games_query);
    
    $games = array();
    if ($result) {
        while ($game = $result->fetch_assoc()) {
            $games[] = $game;
        }
    }
    return $games;
}
$centres_result = $conn->query($centres_query);

// Get next 7 days for the calendar view
$dates = array();
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('Y-m-d', strtotime("+$i days"));
}

// Fetch bookings for the next 7 days
$bookings_query = "SELECT centre_id, booking_date, start_time, end_time 
                   FROM bookings 
                   WHERE booking_date >= CURRENT_DATE 
                   AND booking_date <= DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY)
                   AND status != 'cancelled'
                   ORDER BY booking_date, start_time";
$bookings_result = $conn->query($bookings_query);

// Store bookings in an array for easy access
$bookings = [];
while ($booking = $bookings_result->fetch_assoc()) {
    $key = $booking['centre_id'] . '_' . $booking['booking_date'];
    if (!isset($bookings[$key])) {
        $bookings[$key] = [];
    }
    $bookings[$key][] = [
        'start_time' => $booking['start_time'],
        'end_time' => $booking['end_time']
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor Dashboard - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .time-slot {
            font-size: 0.85rem;
            padding: 2px 5px;
            margin: 2px 0;
            border-radius: 3px;
        }
        .booked {
            background-color: #ffdede;
            color: #dc3545;
        }
        .available {
            background-color: #e8f5e9;
            color: #28a745;
        }
        .calendar-date {
            min-width: 120px;
        }
        .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .badge i {
            margin-right: 4px;
        }
        .games-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 8px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.jpg" alt="Indoor Hub Logo" width="40" height="40" class="me-2">
                Indoor Hub
            </a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-light">Exit Visitor Mode</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h2 class="mb-3">Welcome, Visitor!</h2>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill"></i>
                    You are in view-only mode. To make bookings, please 
                    <a href="register.php" class="alert-link">register an account</a> or 
                    <a href="login.php" class="alert-link">login</a> if you already have one.
                </div>
                <p class="text-muted">Browse our indoor centres and check availability for the next 7 days.</p>
            </div>
        </div>

        <!-- Centres List -->
        <div class="row">
            <?php while ($centre = $centres_result->fetch_assoc()): ?>
                <div class="col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <!-- Centre Info -->
                                <div class="col-md-4">
                                    <h4><?= htmlspecialchars($centre['name']) ?></h4>
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-geo-alt-fill"></i> 
                                        <?= htmlspecialchars($centre['location'] ?? 'Location not specified') ?>
                                    </p>
                                    <p><?= htmlspecialchars($centre['description'] ?? 'No description available') ?></p>
                                    <div class="mb-3">
                                        <h6 class="mb-2">Available Sports/Games:</h6>
                                        <?php 
                                        $games = getGamesForCentre($conn, $centre['game_types']);
                                        if (!empty($games)) {
                                            foreach ($games as $game) {
                                                echo '<span class="badge bg-info me-1 mb-1">
                                                        <i class="bi ' . htmlspecialchars($game['icon']) . '"></i> 
                                                        ' . htmlspecialchars($game['name']) . '
                                                    </span>';
                                            }
                                        } else {
                                            echo '<span class="badge bg-secondary">No games specified</span>';
                                        }
                                        ?>
                                    </div>
                                    <a href="register.php" class="btn btn-primary">Register to Book</a>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i> 
                                            Registration required to make bookings
                                        </small>
                                    </div>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'manager'): ?>
                                    <!-- Manager Quick Links -->
                                    <div class="mt-4">
                                        <h6 class="mb-3">Manager Controls</h6>
                                        <a href="manager_bookings.php" class="btn btn-outline-primary mb-2 w-100">
                                            <i class="bi bi-calendar-check"></i> Manage Bookings
                                        </a>
                                        <a href="manager_staff.php" class="btn btn-outline-primary mb-2 w-100">
                                            <i class="bi bi-people"></i> Manage Staff
                                        </a>
                                        <a href="manager_reports.php" class="btn btn-outline-primary mb-2 w-100">
                                            <i class="bi bi-graph-up"></i> View Reports
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Availability Calendar -->
                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <?php foreach ($dates as $date): ?>
                                                        <th class="text-center calendar-date">
                                                            <?= date('D, M d', strtotime($date)) ?>
                                                        </th>
                                                    <?php endforeach; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php foreach ($dates as $date): ?>
                                                        <td>
                                                            <?php
                                                            $key = $centre['centre_id'] . '_' . $date;
                                                            $day_bookings = $bookings[$key] ?? [];
                                                            
                                                            if (empty($day_bookings)) {
                                                                echo '<div class="time-slot available">
                                                                        <i class="bi bi-check-circle"></i> Fully Available
                                                                    </div>';
                                                            } else {
                                                                foreach ($day_bookings as $slot) {
                                                                    echo '<div class="time-slot booked">
                                                                            <i class="bi bi-clock"></i> ' . 
                                                                            date('h:i A', strtotime($slot['start_time'])) . ' - ' . 
                                                                            date('h:i A', strtotime($slot['end_time'])) . 
                                                                          '</div>';
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
