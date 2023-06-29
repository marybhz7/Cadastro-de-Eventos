<?php
    class Registration {
        private $id;
        private $userId;
        private $eventId;
        private $paymentStatus;

        public function __construct($userId, $eventId, $paymentStatus) {
            $this->userId = $userId;
            $this->eventId = $eventId;
            $this->paymentStatus = $paymentStatus;
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

        public function getPaymentStatus() {
            return $this->paymentStatus;
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

        public function setPaymentStatus($paymentStatus) {
            $this->paymentStatus = $paymentStatus;
        }
        public function saveRegistration() {
            include 'Connect.inc.php';
        
            if ($this->id) {
                $sql = "UPDATE registrations SET user_id = ?, event_id = ?, payment_status = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iisi", $this->userId, $this->eventId, $this->paymentStatus, $this->id);
            } else {
                $sql = "INSERT INTO registrations (user_id, event_id, payment_status) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iis", $this->userId, $this->eventId, $this->paymentStatus);
            }
        
            $stmt->execute();
        
            if ($stmt->affected_rows > 0) {
                if (!$this->id) {
                    $this->id = $stmt->insert_id;
                }
                return true;
            } else {
                return false;
            }
        }

        public static function isUserRegistered($userId, $eventId) {
            include 'Connect.inc.php';
          
            $stmt = $conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
            $stmt->bind_param("ii", $userId, $eventId);
            $stmt->execute();
            $result = $stmt->get_result();
          
            if ($result->num_rows === 0) {
                return false;
            } else {
                return true;
            }
        }

        public static function removeRegistration($userId, $eventId) {
            include 'Connect.inc.php';
    
            $sql = "DELETE FROM registrations WHERE user_id = ? AND event_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $eventId);
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
?>
