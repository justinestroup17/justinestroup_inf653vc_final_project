<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  require('../../config/Database.php');
  require('../../models/Author.php');

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category object
  $author = new Author($db);

  // Get ID
  $author->id = isset(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) : die();

  // Get author
  $author->read_single();

  // Create array
  $author_arr = array(
    'id' => $author->id,
    'author' => $author->author
  );

  // Make JSON
  print_r(json_encode($author_arr));