<?php
// Using the namespace
use Framework\Database;

// Database config
$config = require basePath('config/db.php');

// Instantiate Database
$db = new Database($config);

$id = $_GET['id'] ?? '';
// inspect($id);

$params = [
  'id' => $id
];

// Fetch the listing with placeholders since we are getting the id from $_GET and that can be user input (prevents SQL injection)
$listing = $db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();
// inspect($listing);

// Loads and passes the listing to the view 
loadView('listings/show', [
  'listing' => $listing
]);
?>