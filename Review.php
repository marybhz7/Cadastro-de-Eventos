<?php
    class Review {
        private $id;
        private $userId;
        private $eventId;
        private $rating;
        private $comment;

        public function __construct($userId, $eventId, $rating, $comment) {
            $this->userId = $userId;
            $this->eventId = $eventId;
            $this->rating = $rating;
            $this->comment = $comment;
        }

        public function getId() {
            return $this->id;
        }

        public function getUserId() {
            return $this->userId;
        }

        public function getEventId() {
            return $this->eventId;
        }

        public function getRating() {
            return $this->rating;
        }

        public function getComment() {
            return $this->comment;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function setEventId($eventId) {
            $this->eventId = $eventId;
        }

        public function setRating($rating) {
            $this->rating = $rating;
        }

        public function setComment($comment) {
            $this->comment = $comment;
        }
        public function saveReview() {
            include 'Connect.inc.php'; 
            $stmt = $conn->prepare("INSERT INTO reviews (user_id, event_id, rating, comment) VALUES (?, ?, ?, ?)");
        
            if ($stmt === false) {
                die("Erro na preparação da declaração SQL: " . $conn->error);
            }
        
            $stmt->bind_param("iiis", $this->userId, $this->eventId, $this->rating, $this->comment);
        
            if ($stmt->execute() === false) {
                die("Erro na execução da declaração SQL: " . $stmt->error);
            }
        
            $stmt->close();
            $conn->close();
        }
        public static function getReviewsByEventId($eventId) {
            include 'Connect.inc.php'; 
        
            $stmt = $conn->prepare("SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.event_id = ?");
            $stmt->bind_param("i", $eventId);
        
            if ($stmt->execute() === false) {
                die("Erro na execução da declaração SQL: " . $stmt->error);
            }
        
            $result = $stmt->get_result();
        
            $reviews = [];
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        
            $stmt->close();
        
            return $reviews;
        }
        public static function deleteReview($reviewId) {
            include 'Connect.inc.php';        
            $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->bind_param("i", $reviewId);
        
            if ($stmt->execute() === false) {
                die("Erro na execução da declaração SQL: " . $stmt->error);
            }
        
            $stmt->close();
        }
        
        
    }

?>