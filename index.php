<?php

// Model
require('config/Database.php');
require('models/Author.php');
require('models/Category.php');
require('models/Quote.php');

// Get connection
$database = new Database();
$db = $database->connect();

// Instantiate the models needed
$quote = new Quote($db);
$author = new Author($db);
$category = new Category($db);

// Read data
$authors = $author->read();
$categories = $category->read();
$quotes = $quote->read();

// Get Parameter data sent to Controller
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

$authorId = filter_input(INPUT_GET, 'authorId', FILTER_VALIDATE_INT);
$categoryId = filter_input(INPUT_GET, 'categoryId', FILTER_VALIDATE_INT);
$quoteId = filter_input(INPUT_GET, 'quoteId', FILTER_VALIDATE_INT);

// Get Request Quote Data
if ($authorId) {
    $quote->authorId = $authorId;
}
if ($categoryId) {
    $quote->categoryId = $categoryId;
}

// Read the data calling read() functions from the model as already posted

// Default action is view quotes list
switch($action) {
        default:
            include('view/quotes_list.php');
}