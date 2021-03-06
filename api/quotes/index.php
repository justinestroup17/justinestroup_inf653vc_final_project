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

  // Quote query
  $result = $quote->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any quotes
  if($num > 0) {
    // Quote array
    $quotes_arr = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $quote_item = array(
        'id' => $id,
        'quote' => html_entity_decode($quote),
        'author' => $author,
        'authorId' => $authorId
        //'categoryId' => $categoryId,
        //'category' => $categoryName
      );


      // Push to array
      array_push($quotes_arr, $quote_item);
    }

    // Turn to JSON & output
    echo json_encode($quotes_arr);

  } else {
    // No Posts
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }
