<?php
namespace App\Controllers;

// Using the namespace
use Framework\Database;

class ListingController {
  protected $db;

  public function __construct() {
    // Database config
    $config = require basePath('config/db.php');

    // Instantiate Database, to get access to it
    $this->db = new Database($config);
  }

  public function index() {
    // Fetch the listings
    $listings = $this->db->query('SELECT * FROM listings')->fetchAll();
    // inspect($listings);

    // Loads and passes the listings to the view 
    loadView('home', [
      'listings' => $listings
    ]);
  }

  public function create() {
    loadView('listings/create');
  }

  public function show() {
    $id = $_GET['id'] ?? '';
    // inspect($id);
    
    $params = [
      'id' => $id
    ];
    
    // Fetch the listing with placeholders since we are getting the id from $_GET and that can be user input (prevents SQL injection)
    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();
    // inspect($listing);
    
    // Loads and passes the listing to the view 
    loadView('listings/show', [
      'listing' => $listing
    ]);
  }
}
?>