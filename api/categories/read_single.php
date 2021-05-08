<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  require('../../config/Database.php');
  require('../../models/Category.php');

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category object
  $category = new Category($db);

  // Get ID
  $category->id = isset(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) : die();

  // Get category
  $category->read_single();

  // Create array
  $category_arr = array(
    'id' => $category->id,
    'category' => $category->category
  );

  // Make JSON
  print_r(json_encode($category_arr));