<?php
// Database config
$config = require basePath('config/db.php');

// Instantiate Database
$db = new Database($config);

// Fetch the listings
$listings = $db->query('SELECT * FROM listings LIMIT 6')->fetchAll();

// inspect($listings);

// Loads and passes the listings to the view 
loadView('home', [
  'listings' => $listings
]);
?>