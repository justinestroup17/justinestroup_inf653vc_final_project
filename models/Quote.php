<?php 
  class Quote {
    // Database
    private $conn;
    private $table = 'quotes';

    // Post Properties
    public $id;
    public $categoryId;
    public $authorId;
    public $categoryName;
    public $quote;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Quotes
    public function read() {
        // Create query
        $query =   'SELECT c.category as categoryName, q.id, q.categoryId, q.authorId, q.quote, a.author
                    FROM ' . $this->table . ' q
                    LEFT JOIN
                      categories c ON q.categoryId = c.id
                    RIGHT JOIN
                      authors a ON q.authorId = a.id';
                    
        /*if ($this->authorId && $this->categoryId) {
          $query = $query . ' WHERE q.authorId = :authorId AND q.categoryId = :categoryId';
        } else*/ if ($this->authorId) {
          $query = $query . ' WHERE q.authorId = :authorId';
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(":authorId", $this->authorId);
          // Execute query
        $stmt->execute();
        echo 'Did do if clause';

      return $stmt;

        } /*else /if ($this->categoryId) {
          $query = $query . ' WHERE q.categoryId = :categoryId';
        } */
        // Prepare statement
        //$stmt = $this->conn->prepare($query);
        //$stmt->bindParam(":authorId", $this->authorId, PDO::FETCH_ASSOC);
        //$stmt->bindParam(":categoryId", $this->categoryId, PDO::FETCH_ASSOC);
      
      // Prepare Query
      $stmt = $this->conn->prepare($query);
      // Execute query
      $stmt->execute();
      echo 'Did not do if clause';
      return $stmt;
    }

    // Get Single Post
    public function read_single() {
          // Create query
          $query = 'SELECT c.category as categoryName, q.id, q.categoryId, q.authorId, q.quote, a.author
                    FROM ' . $this->table . ' q
                    LEFT JOIN
                      categories c ON q.categoryId = c.id
                    RIGHT JOIN
                      authors a ON q.authorId = a.id
                    WHERE
                      q.id = ?
                    LIMIT 0, 1';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set properties
          $this->quote = $row['quote'];
          $this->authorId = $row['authorId'];
          $this->categoryId = $row['categoryId'];
          $this->categoryName = $row['categoryName'];
          $this->author = $row['author'];
    }

    // Create Quote
    public function create() {
          // Create query
          $query = 'INSERT INTO ' . $this->table . '
                    SET 
                      quote = :quote,
                      authorId = :authorId,
                      categoryId = :categoryId';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->quote = htmlspecialchars(strip_tags($this->quote));
          $this->authorId = htmlspecialchars(strip_tags($this->authorId));
          $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

          // Bind data
          $stmt->bindParam(':quote', $this->quote);
          $stmt->bindParam(':authorId', $this->authorId);
          $stmt->bindParam(':categoryId', $this->categoryId);

          // Execute query
          if($stmt->execute()) {
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }
    
    // Update Quote
    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . '
                    SET
                      quote = :quote,
                      authorId = :authorId,
                      categoryId = :categoryId
                    WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->quote = htmlspecialchars(strip_tags($this->quote));
          $this->authorId = htmlspecialchars(strip_tags($this->authorId));
          $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
          $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
          $stmt->bindParam(':quote', $this->quote);
          $stmt->bindParam(':authorId', $this->authorId);
          $stmt->bindParam(':categoryId', $this->categoryId);
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
    // Delete Quote
    public function delete() {
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          echo json_encode("Error: %s.\n", $stmt->error);

          return false;
    }
    
  }