<?php  
    class Event {
        private $id;
        private $userId;
        private $title;
        private $description;
        private $date;
        private $time;
        private $location;
        private $categoryId;
        private $price;
        private $image;

        public function __construct($userId, $title, $description, $date, $time, $location, $categoryId, $price, $image) {
            $this->title = $title;
            $this->userId = $userId;
            $this->description = $description;
            $this->date = $date;
            $this->time = $time;
            $this->location = $location;
            $this->categoryId = $categoryId;
            $this->price = $price;
            $this->image = $image;
        }

        public function getId() {
            return $this->id;
        }        

        public function getUserId() {
          return $this->userId;
        }

        public function getTitle() {
            return $this->title;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getDate() {
            return $this->date;
        }

        public function getTime() {
            return $this->time;
        }

        public function getLocation() {
            return $this->location;
        }

        public function getCategoryId() {
            return $this->categoryId;
        }

        public function getPrice() {
            return $this->price;
        }

        public function getImage() {
            return $this->image;
        }

        public function setId($id) {
            $this->id = $id;
        }
                

        public function setUserId($userId) {
          $this->userId = $userId;
        }

        public function setTitle($title) {
            $this->title = $title;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function setDate($date) {
            $this->date = $date;
        }

        public function setTime($time) {
            $this->time = $time;
        }

        public function setLocation($location) {
            $this->location = $location;
        }

        public function setCategoryId($categoryId) {
            $this->categoryId = $categoryId;
        }

        public function setPrice($price) {
            $this->price = $price;
        }

        public function setImage($image) {
            $this->image = $image;
        }
        public function insertEvent() {
            include 'Connect.inc.php';
        
            $sql = "INSERT INTO events (user_id, title, description, date, time, location, category_id, price, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssds", $this->userId, $this->title, $this->description, $this->date, $this->time, $this->location, $this->categoryId, $this->price, $this->image);
        
            if ($stmt->execute()) {
              $this->id = $stmt->insert_id;
              return true;
            } else {
              return false;
            }
        
            $stmt->close();
            $conn->close();
          }

          public static function getCategoryName($categoryId) {
            include 'Connect.inc.php';

            $query = "SELECT name FROM categories WHERE id = $categoryId";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              return $row['name'];
            } else {
              return 'Categoria não encontrada';
            }
          }
          
          
          public static function getFilteredEvents($search, $category) {
            include 'Connect.inc.php';
        
            $sql = "SELECT * FROM events WHERE title LIKE ? AND category_id = ?";
            $stmt = $conn->prepare($sql);
            $searchParam = '%' . $search . '%';
            $stmt->bind_param("ss", $searchParam, $category);
            $stmt->execute();
        
            $result = $stmt->get_result();
            $events = array();
        
            while ($row = $result->fetch_assoc()) {
              $event = new Event($row['user_id'],$row['title'], $row['description'], $row['date'], $row['time'], $row['location'], $row['category_id'], $row['price'], $row['image']);
              $event->setId($row['id']);
              $events[] = $event;
            }
        
            $stmt->close();
            $conn->close();
        
            return $events;
          }
          public static function getAllEvents() {
            include 'Connect.inc.php';
        
            $query = "SELECT * FROM events";
            $result = $conn->query($query);
        
            $events = array();
        
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $event = new Event($row['user_id'],$row['title'], $row['description'], $row['date'], $row['time'], $row['location'], $row['category_id'], $row['price'], $row['image']);
                $event->setId($row['id']);
                $events[] = $event;
              }
            }
        
            $conn->close();
        
            return $events;
          }
          public static function getEventsByCategory($category) {
            include 'Connect.inc.php';
        
            $category = $conn->real_escape_string($category);
        
            $query = "SELECT * FROM events WHERE category_id = '$category'";
            $result = $conn->query($query);
        
            $events = array();
        
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $event = new Event($row['user_id'], $row['title'], $row['description'], $row['date'], $row['time'], $row['location'], $row['category_id'], $row['price'], $row['image'] );
                $event->setId($row['id']);
                $events[] = $event;
              }
            }
        
            $conn->close();
        
            return $events;
          }
        
          public static function getEventsBySearch($search) {
            include 'Connect.inc.php';
        
            $search = $conn->real_escape_string($search);
        
            $query = "SELECT * FROM events WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
            $result = $conn->query($query);
        
            $events = array();
        
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $event = new Event($row['user_id'],$row['title'], $row['description'], $row['date'], $row['time'], $row['location'], $row['category_id'], $row['price'], $row['image']);
                $event->setId($row['id']);
                $events[] = $event;
              }
            }
        
            $conn->close();
        
            return $events;
          }

          public static function getEventById($eventId) {
            include 'Connect.inc.php';
          
            $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
            $stmt->bind_param("i", $eventId);
            $stmt->execute();
            $result = $stmt->get_result();
          
            if ($result->num_rows === 0) {
              return null;
            }
          
            $eventData = $result->fetch_assoc();
            $event = new Event(
              $eventData['user_id'],
              $eventData['title'],
              $eventData['description'],
              $eventData['date'],
              $eventData['time'],
              $eventData['location'],
              $eventData['category_id'],
              $eventData['price'],
              $eventData['image']
            );
          
            $event->setId($eventData['id']);
          
            return $event;
          }
          
          public static function getCategories() {
            include 'Connect.inc.php';
        
            $query = "SELECT * FROM categories";
            $result = $conn->query($query);
        
            $categories = array();
        
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $categories[] = $row['name'];
              }
            }
        
            $conn->close();
        
            return $categories;
          }

          
          public function saveEvent() {
            include 'Connect.inc.php';
        
            if ($this->id) {
                $sql = "UPDATE events SET user_id = ?, title = ?, description = ?, date = ?, time = ?, location = ?, category_id = ?, price = ?, image = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssdsi", $this->userId, $this->title, $this->description, $this->date, $this->time, $this->location, $this->categoryId, $this->price, $this->image, $this->id);
            } else {
                $sql = "INSERT INTO events (user_id, title, description, date, time, location, category_id, price, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssds", $this->userId, $this->title, $this->description, $this->date, $this->time, $this->location, $this->categoryId, $this->price, $this->image);
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
        
    
        public function deleteEvent() {
          include 'Connect.inc.php';
      
          $sql = "DELETE FROM events WHERE id = ?";
      
          $stmt = $conn->prepare($sql);
      
          $stmt->bind_param("i", $this->id);
          $stmt->execute();
      
          if ($stmt->affected_rows > 0) {
              return true;
          } else {
              return false;
          }
      }
      
          
    }
?>
