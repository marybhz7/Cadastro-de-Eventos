<?php
class Category {
  private $id;
  private $name;

  public function __construct($name) {
    $this->name = $name;
  }

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public static function getAllCategories() {
    include 'Connect.inc.php';

    $query = "SELECT * FROM categories";
    $result = $conn->query($query);

    $categories = array();

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $category = new Category($row['name']);
        $category->setId($row['id']);

        $categories[] = $category;
      }
    }

    $conn->close();

    return $categories;
  }


}
?>
