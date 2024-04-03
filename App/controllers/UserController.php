<?php
namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController {
  protected $db;

  public function __construct() {
    $config = require basePath('config/db.php');
    $this->db = new Database($config);
  }

  /**
   * Show the login page
   * 
   * @return void
   * 
   */
  public function login() {
    loadView('users/login');
  }

  /**
   * Show the register page
   * 
   * @return void
   * 
   */
  public function create() {
    loadView('users/create');
  }

  /**
   * Store user in database
   * 
   * @return void
   * 
   */
  public function store() {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $password = $_POST['password'];
    $passwordConfirmation = $_POST['password_confirmation'];

    $errors = [];

    // Validations
    if (!Validation::email($email)) {
      $errors['email'] = 'Please enter a valid email address';
    }

    if (!Validation::string($name, 2, 50)) {
      $errors['name'] = 'Name must be between 2 and 50 characters';
    }

    if (!Validation::string($password, 6, 50)) {
      $errors['password'] = 'Password must be at least 6 characters';
    }

    if (!Validation::match($password, $passwordConfirmation)) {
      $errors['password_confirmation'] = 'Passwords do not match';
    }

    // Reload view with errors and data previously filled except password
    if (!empty($errors)) {
      loadView('users/create', [
        'errors' => $errors,
        'user' => [
          'name' => $name,
          'email' => $email,
          'city' => $city,
          'state' => $state,
        ]
      ]);
      exit;
    }
    
    // Check if email exists
    $params = [
      'email' => $email
    ];

    $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

    // If we found a user the email is already in use
    if ($user) {
      $errors['email'] = 'Email is already in use';
      loadView('users/create', [
        'errors' => $errors,
        'user' => [
          'name' => $name,
          'email' => $email,
          'city' => $city,
          'state' => $state,
        ]
      ]);
      exit;
    }

    // Create user account
    $params = [
      'name' => $name,
      'email' => $email,
      'city' => $city,
      'state' => $state,
      // password_hash creates a password hash
      'password' => password_hash($password, PASSWORD_DEFAULT),
    ];

    $this->db->query('INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)', $params);

    // Get new user ID
    // PDO::lastInsertId returns the ID of the last inserted row or sequence value 
    $userId = $this->db->conn->lastInsertId();

    // Add entries to SESSION
    Session::set('user', [
      'id' => $userId,
      'name' => $name,
      'email' => $email,
      'city' => $city,
      'state' => $state,
    ]);

    // inspectAndDie(Session::get('user'));

    redirect('/');
  }

  /**
   * Logout a user and kill session
   * 
   * @return void
   * 
   */
  public function logout() {
    Session::clearAll();

    // Remove cookie
    // session_get_cookie_params gets the session cookie parameters
    // setcookie sends a cookie
    $params = session_get_cookie_params();
    // PHPSESSID is automatically created by this app when starting the session
    // Set a value of an empty string, an expiration in the past, and the path and params that we got from the cookie previously
    setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

    redirect('/');
  }
}
?>