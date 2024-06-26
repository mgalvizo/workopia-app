<?php
// Add the namespace for this class specified in out list of namespaces in composer.json
// Namespaces avoid conflicts if there is another class with the same name in a different directory
namespace Framework;

// Needed since the app will by default look in the current Framework namespace
use PDO;
use PDOException;
use Exception;

class Database {
  // Property that represents the connection
  public $conn;

  /**
   * Constructor for Database class
   * 
   * @param array $config
   * 
   */
  public function __construct($config) {
    // Data source name
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

    // Array of options for PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // With FETCH_ASSOC we access the data with "[<property-name>]"
        // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        
        // With FETCH_OBJ we access the data with -><property-name>
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    try {
        $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
        // echo 'Database connection successful';
    } catch (PDOException $e) {
        throw new Exception("Database connection failed: {$e->getMessage()}");
    }
  }

  /**
   * Query the database
   * 
   * @param string $query
   * @param array $params
   * @return PDOStatement
   * @throws PDOException
   * 
   */
  public function query($query, $params = []) {
    try {
      // Prepare statement with query
      $stmt = $this->conn->prepare($query);

      // Bind named parameters (placeholders) for the query
      foreach($params as $param => $value) {
        // PDOStatement::bindValue binds a value to a parameter
        $stmt->bindValue(":{$param}", $value);
      }
      
      // Execute statement
      $stmt->execute();

      return $stmt;
    } catch (PDOException $e) {
      throw new Exception("Query failed to execute: {$e->getMessage()}");
    }
  }
}
?>