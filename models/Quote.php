<?php 
  class Quote {
    // Database
    private $conn;
    private $table = 'quotes';

    // Quote Properties
    public $id;
    public $categoryId;
    public $authorId;
    public $categoryName;
    public $quote;
    public $limit;

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
        
        // Get and assign parameters
        $authorId = filter_input(INPUT_GET, 'authorId', FILTER_VALIDATE_INT);
        $categoryId = filter_input(INPUT_GET, 'categoryId', FILTER_VALIDATE_INT);
        $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);

        if ($authorId) {
          $this->authorId = $authorId;
        }
        if ($categoryId) {
          $this->categoryId = $categoryId;
        }

        // Both specific authorId and specific categoryId were specified
        if ($this->authorId && $this->categoryId) {
          $query = $query . ' WHERE q.authorId = :authorId AND q.categoryId = :categoryId';
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(":authorId", $this->authorId);
          $stmt->bindParam(":categoryId", $this->categoryId);
          
          // Execute query
          $stmt->execute();
          
          return $stmt; 
        }
        // Specific authorId was specified
        if ($this->authorId) {
          $query = $query . ' WHERE q.authorId = :authorId';
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(":authorId", $this->authorId);
          
          // Execute query
          $stmt->execute();
          
          return $stmt; 
        }
        // Specific authorId was specified
        if ($this->categoryId) {
          $query = $query . ' WHERE q.categoryId = :categoryId';
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(":categoryId", $this->categoryId);
          
          // Execute query
          $stmt->execute();
          
          return $stmt; 
        }
        // Specific limit was specified
        if ($limit) {
          $this->limit = $limit;
          echo 'New new new';
          //$this->limit = $limit;
          //echo 'Limit clause was entered as limit is 1';
          $query =   'SELECT c.category as categoryName, q.id, q.categoryId, q.authorId, q.quote, a.author
                    FROM ' . $this->table . ' q
                    LEFT JOIN
                      categories c ON q.categoryId = c.id
                    RIGHT JOIN
                      authors a ON q.authorId = a.id
                    LIMIT ' . $this->limit;
          //$query = $query . ' LIMIT :limit';
          $stmt = $this->conn->prepare($query);
          //$stmt->bindValue(":limit", $this->limit);
          
          // Execute query
          $stmt->execute();
          
          return $stmt; 
        }
      
      /* Additional paramaters were not specified */
      
      // Prepare Query
      $stmt = $this->conn->prepare($query);
      // Execute query
      $stmt->execute();
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