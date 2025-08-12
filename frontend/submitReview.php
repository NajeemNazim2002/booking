<?php
global $conn;
session_start();
include '../db_con/db_connect.php';

// Check if user is logged in and is a 'user'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stadium_id = intval($_POST['stadium_id']);
    $stars = intval($_POST['stars']);
    $comment = trim($_POST['comment']);
    $username = $_SESSION['username']; // Get username from session

    // Basic validation
    if ($stars >= 1 && $stars <= 5 && !empty($comment)) {
        // Prevent duplicate review by same user
        $check_stmt = $conn->prepare("SELECT review_id FROM reviews WHERE stadium_id = ? AND username = ?");
        $check_stmt->bind_param("is", $stadium_id, $username);
        $check_stmt->execute();
        $existing = $check_stmt->get_result()->fetch_assoc();

        if (!$existing) {
            // Insert new review
            $stmt = $conn->prepare("INSERT INTO reviews (stadium_id, username, rating, comment) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isis", $stadium_id, $username, $stars, $comment);
            $stmt->execute();
        }
    }

    // Redirect back to the main stadium list or details page
    header("Location: stadiumGallery.php");
    exit();
}
?>
