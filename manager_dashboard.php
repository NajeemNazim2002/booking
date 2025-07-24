<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';
$manager_id = $_SESSION['user_id'];
$centre = $conn->query("SELECT * FROM centres WHERE manager_id = $manager_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Centre Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 text-center">Centre Manager Dashboard</h2>

    <div class="card mb-4 shadow-sm">
        <div class="card-body text-center">
            <h4><?= $centre['name'] ?? "No Centre Assigned" ?></h4>
            <p><strong>Location:</strong> <?= $centre['location'] ?? '-' ?></p>
            <p><strong>Sport:</strong> <?= $centre['sport_type'] ?? '-' ?></p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-calendar-check fs-1 text-primary mb-3"></i>
                    <h5 class="card-title">View Bookings</h5>
                    <p class="card-text">See all bookings for your centre.</p>
                    <a href="manager_bookings.php" class="btn btn-primary">Bookings</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-people fs-1 text-primary mb-3"></i>
                    <h5 class="card-title">Manage Staff</h5>
                    <p class="card-text">Trainers, coaches, referees for your centre.</p>
                    <a href="manager_staff.php" class="btn btn-primary">Staff</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-bar-chart-line fs-1 text-primary mb-3"></i>
                    <h5 class="card-title">Reports</h5>
                    <p class="card-text">View centre-specific reports.</p>
                    <a href="manager_reports.php" class="btn btn-primary">Reports</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-cloud-upload fs-1 text-primary mb-3"></i>
                    <h5 class="card-title">Upload Facility Photos</h5>
                    <p class="card-text">Upload and manage images for your centre.</p>
                    <a href="uploadphotos.php" class="btn btn-primary">Upload</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-calendar-x fs-1 text-primary mb-3"></i>
                    <h5 class="card-title">Block Maintenance Dates</h5>
                    <p class="card-text">Schedule unavailable dates for maintenance.</p>
                    <a href="blockmaintenancedate.php" class="btn btn-primary">Block Dates</a>
                </div>
            </div>
        </div>
    </div>
</div>