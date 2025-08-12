<?php
include '../db_con/db_connect.php';
session_start();

if ($_SESSION['role'] !== 'user') {
    exit("Unauthorized.");
}

if (isset($_GET['review_id'])) {
    $review_id = intval($_GET['review_id']);
    $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
}

header("Location: stadiumGallery.php");
exit();
