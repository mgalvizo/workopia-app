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

  /**
   * Show all listings
   *
   * @return void
   */
  public function index() {
    // Fetch the listings
    $listings = $this->db->query('SELECT * FROM listings')->fetchAll();
    // inspect($listings);

    // Loads and passes the listings to the view 
    loadView('listings/index', [
      'listings' => $listings
    ]);
  }

  /**
   * Show the create listing form
   *
   * @return void
   */
  public function create() {
    loadView('listings/create');
  }

  /**
   * Show a single listing
   * @param array $params
   * @return void
   */
  public function show($params) {
    // $id = $_GET['id'] ?? '';
    $id = $params['id'] ?? '';
    // inspect($id);
    
    $params = [
      'id' => $id
    ];
    
    // Fetch the listing with placeholders since we are getting the id from $_GET and that can be user input (prevents SQL injection)
    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();
    // inspect($listing);
    
    // Check if listing exists
    if (!$listing) {
      ErrorController::notFound('Listing not found');
      return;
    }

    // Loads and passes the listing to the view 
    loadView('listings/show', [
      'listing' => $listing
    ]);
  }
}
?>