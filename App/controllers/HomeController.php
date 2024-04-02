<?php
namespace App\Controllers;

// Using the namespace
use Framework\Database;

class HomeController {
  protected $db;

  public function __construct() {
    // Database config
    $config = require basePath('config/db.php');

    // Instantiate Database, to get access to it
    $this->db = new Database($config);
  }

  public function index() {
    // Fetch the listings
    $listings = $this->db->query('SELECT * FROM listings LIMIT 6')->fetchAll();
    // inspect($listings);

    // Loads and passes the listings to the view 
    loadView('home', [
      'listings' => $listings
    ]);
  }
}
?>