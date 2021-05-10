<?php 
  class Database {
    // DB Params
    //private $host = 'wcwimj6zu5aaddlj.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
    //private $db_name = 'quotesdb';
    //private $username = 'u8q7lyfw1vlm8io2';
    //private $password = 'tn6uqo7avcuhavc9';
    private $conn;

    // DB Connect
    public function connect() {
      $this->conn = null;
      $url = getenv('JAWSDB_URL');
      $dbparts = parse_url($url);

      $hostname = $dbparts['host'];
      $username = $dbparts['user'];
      $password = $dbparts['pass'];
      $database = ltrim($dbparts['path'],'/');

      $dsn = "mysql:host={$hostname};dbname={$database}";

      try { 
        $this->conn = new PDO($dsn, $username, $password);
        //$this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        //$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }