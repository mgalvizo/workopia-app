<?php
namespace App\Controllers;

// Using the namespace
use Framework\Database;
use Framework\Session;
use Framework\Validation;
use Framework\Authorization;

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
    $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();
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

  /**
   * Store data in database
   * 
   * @return void
   * 
   */
  public function store() {
    // inspectAndDie($_POST);

    // Fields from the create job listing form for SECURITY
    $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

    // array_intersect_key computes the intersection of arrays using keys for comparison
    // array_intersect_key() returns an array containing all the entries of array which have keys that are present in all the arguments.
    // In this case array_intersect_key() will return only the allowed fields
    // array_flip exchanges all keys with their associated values in an array, in this case the index with value
    $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

    // Saving the user id to the particular listing
    $newListingData['user_id'] = Session::get('user')['id'];

    // array_map applies the callback to the elements of the given arrays
    $newListingData = array_map('sanitize', $newListingData);
    // inspectAndDie($newListingData);

    $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

    $errors = [];

    // Implement validation for all required fields
    // empty determines whether a variable is empty
    foreach ($requiredFields as $field) {
      if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
        // Add an entry to the errors array with a message
        // ucfirst makes a string's first character uppercase
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    // inspectAndDie($errors);
    if (!empty($errors)) {
      // Reload view with error data
      loadView('listings/create', [
        'errors' => $errors,
        // Pass the data so we do NOT lose any current filled fields in the form
        // Since this are not fetched with PDO we can access the values with []
        'listing' => $newListingData
      ]);
      exit;
    } else {
      // Submit data
      
      $fields = [];

      // Add all fields as an array
      foreach($newListingData as $field => $value) {
        $fields[] = $field;
      }

      // Create a comma separated string with every field for the query
      $fields = implode(', ', $fields);
      // inspect($fields);

      $values = [];

      // Add all values to array
      foreach($newListingData as $field => $value) {
        // Convert empty strings into null
        if ($value === '') {
          $newListingData[$field] = null;
        }
        
        // Create array with value placeholder data for query
        $values[] = ':' . $field;
      }

      // Create a comma separated string with every value placeholder for the query
      $values = implode(', ', $values);
      inspect($values);
      
      // $fields and $values are transformation resulting in strings for the SQL syntax
      $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";
      
      // Pass the query with the placeholders and the actual data to the execute the actual query
      $this->db->query($query, $newListingData);

      // Send flash message
      Session::setFlashMessage('success_message', 'Listing created successfully');

      // Redirect
      redirect('/listings');
    }
  }

  /**
   * Delete a listing
   * 
   * @param array $params
   * @return void
   * 
   */
  public function destroy($params) {
    $id = $params['id'];

    $params = [
      'id' => $id
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    // Check if listing exists
    if(!$listing) {
      ErrorController::notFound('Listing not found');
      return;
    }

    // inspectAndDie($listing);

    // Authorize deletion (user must own listing to be able to delete one)
    if (!Authorization::isOwner($listing->user_id)) {
      Session::setFlashMessage('error_message', 'You are not authorized to delete this listing');

      return redirect("/listings/{$listing->id}");
    }

    $this->db->query('DELETE FROM listings WHERE id = :id', $params);

    // Set flash message
    Session::setFlashMessage('success_message', 'Listing deleted successfully');

    redirect('/listings');
  }

    /**
   * Show the listing edit form
   * @param array $params
   * @return void
   */
  public function edit($params) {
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

    // Authorize show edit form (user must own listing to be able to edit one)
    if (!Authorization::isOwner($listing->user_id)) {
      Session::setFlashMessage('error_message', 'You are not authorized to update this listing');

      return redirect("/listings/{$listing->id}");
    }

    // Loads and passes the listing to the view 
    loadView('listings/edit', [
      'listing' => $listing
    ]);
  }

  /**
   * Update a listing
   * 
   * @param array $params
   * @return void
   * 
   */
  public function update($params) {
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

    // Authorize update (user must own listing to be able to edit one)
    if (!Authorization::isOwner($listing->user_id)) {
      Session::setFlashMessage('error_message', 'You are not authorized to update this listing');

      return redirect("/listings/{$listing->id}");
    }

    // Fields from the create job listing form for SECURITY
    $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

    $updateValues = [];

    // array_intersect_key computes the intersection of arrays using keys for comparison
    // array_intersect_key() returns an array containing all the entries of array which have keys that are present in all the arguments.
    // In this case array_intersect_key() will return only the allowed fields
    // array_flip exchanges all keys with their associated values in an array, in this case the index with value
    $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

    // array_map applies the callback to the elements of the given arrays
    $updateValues = array_map('sanitize', $updateValues);

    $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

    $errors = [];

    // Implement validation for all required fields
    // empty determines whether a variable is empty
    foreach ($requiredFields as $field) {
      if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
        // Add an entry to the errors array with a message
        // ucfirst makes a string's first character uppercase
        $errors[$field] = ucfirst($field) . ' is required';
      }
    }

    // inspectAndDie($errors);
    if (!empty($errors)) {
      // Reload view with error data
      loadView('listings/edit', [
        'errors' => $errors,
        // Pass the data so we do NOT lose any current filled fields in the form
        // Since this are not fetched with PDO we can access the values with []
        'listing' => $listing
      ]);
      exit;
    } else {
      // Submit data
      
      $updateFields = [];

      // Add all fields as an array
      // array_keys returns all the keys or a subset of the keys of an array
      foreach(array_keys($updateValues) as $field) {
        $updateFields[] = "{$field} = :{$field}";
      }

      // inspectAndDie($updateFields);

      // Create a comma separated string with every field for the query
      $updateFields = implode(', ', $updateFields);
      // inspect($updateFields);

      // $updateFields is a transformed string to satisfy SQL syntax
      $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";
      // inspectAndDie($updateQuery);
      
      $updateValues['id'] = $id;

      $this->db->query($updateQuery, $updateValues);

      // Set flash message
      Session::setFlashMessage('success_message', 'Listing updated');

      redirect("/listings/{$id}");
    }
  }

  /**
   * Search listings by keywords/location
   * 
   * @return void
   * 
   */
  public function search() {
    $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
    $location = isset($_GET['location']) ? trim($_GET['location']) : '';

    $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords OR company LIKE :keywords) AND (city LIKE :location OR state LIKE :location)";

    $params = [
      'keywords' => "%{$keywords}%",
      'location' => "%{$location}%"
    ];

    $listings = $this->db->query($query, $params)->fetchAll();
    // inspectAndDie($listings);

    loadView('/listings/index', [
      'listings' => $listings,
      'keywords' => $keywords,
      'location' => $location,
    ]);
  }
}
?>