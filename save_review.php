<?php
session_start();
    require_once 'Review.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_SESSION['id'];
        $eventId = $_POST['eventId'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        $review = new Review($userId, $eventId, $rating, $comment);

        $review->saveReview();

        header("Location: event_details.php?event_id=$eventId");
        exit();
    }
?>