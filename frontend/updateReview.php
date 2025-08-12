<?php
include '../db_con/db_connect.php';
session_start();

if ($_SESSION['role'] !== 'user') {
    exit("Unauthorized.");
}

$review_id = $_POST['review_id'];
$stars = $_POST['stars'];
$comment = $_POST['comment'];

$stmt = $conn->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE review_id = ?");
$stmt->bind_param("isi", $stars, $comment, $review_id);
$stmt->execute();

header("Location: stadiumGallery.php");
exit();

