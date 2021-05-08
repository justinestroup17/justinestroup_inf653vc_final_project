<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  require('../../config/Database.php');
  require('../../models/Quote.php');

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quote = new Quote($db);

  // Get ID
  $quote->id = isset(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) : die();

  // Get quote
  $quote->read_single();

  // Create array
  $quote_arr = array(
    'id' => $quote->id,
    'quote' => $quote->quote,
    'author' => $quote->author,
    'authorId' => $quote->authorId,
    'categoryId' => $quote->categoryId,
    'categoryName' => $quote->categoryName
  );

  // Make JSON
  print_r(json_encode($quote_arr));