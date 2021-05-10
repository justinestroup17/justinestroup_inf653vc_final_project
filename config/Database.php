<?php 
  class Database {
    // DB Params
    private $conn;

    // DB Connect
    public function connect() {
      $url = getenv('JAWSDB_URL');
      $dbparts = parse_url($url);

      $hostname = $dbparts['host'];
      $username = $dbparts['user'];
      $password = $dbparts['pass'];
      $database = ltrim($dbparts['path'],'/');

      // host: wcwimj6zu5aaddlj.cbetxkdyhwsb.us-east-1.rds.amazonaws.com	
      // username: u8q7lyfw1vlm8io2
      // password: tn6uqo7avcuhavc9	

      //$dsn = "mysql:host={$hostname};dbname={$database}";
      $dsn = "mysql:host=wcwimj6zu5aaddlj.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=v38z3coezpqellzm";
      $this->conn = null;

      try { 
        $this->conn = new PDO($dsn, $username, $password);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }

  