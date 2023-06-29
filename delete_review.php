<?php
    session_start();
require_once 'Review.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reviewId = $_POST['review_id'];
        $userId = $_POST['user_id'];
        $eventId = $_POST['event_id'];
        if ($_SESSION['id'] == $userId) {
            Review::deleteReview($reviewId);
            echo "foi";
            header("Location: event_details.php?event_id=$eventId");
            exit;
        }
    }
?>