<?php
namespace Framework;

class Session {

  /**
   * Start the session
   * 
   * @return void
   * 
   */
  public static function start() {
    // session_status() returns the current session status
    // 0 = Sessions Disabled
    // 1 = No Session
    // 2 = Active Session
    // Check if there is NO session and start it
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * Set a session key-value pair
   * 
   * @param string $key
   * @param mixed $value
   * @return void
   * 
   */
  public static function set($key, $value) {
    $_SESSION[$key] = $value;
  }

  /**
   * Get a session value by key
   * 
   * @param string $key
   * @param mixed $default
   * @return 
   * 
   */
  public static function get($key, $default = null) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
  }

  /**
   * Check if session key exists
   * 
   * @param sstring $key
   * @return bool
   * 
   */
  public static function has ($key) {
    return isset($_SESSION[$key]);
  }

  /**
   * Clear session by key
   * 
   * @param string $key
   * @return void
   * 
   */
  public static function clear($key) {
    if (isset($_SESSION[$key])) {
      // unset unsets a given variable
      unset($_SESSION[$key]);
    }
  }

  /**
   * Clear all session data
   * 
   * @return void
   * 
   */
  public static function clearAll() {
    // session_unset frees all session variables
    session_unset();
    // session_destroy destroys all data registered to a session
    session_destroy();
  }

  /**
   * Set a flash message
   * 
   * @param string $key
   * @param string $message
   * @return void
   * 
   */
  public static function setFlashMessage($key, $message) {
    // We use self to refer to a static method of the own class
    self::set("flash_{$key}", $message);
  }

  /**
   * Get a flash message and unset
   * 
   * @param string $key
   * @param mixed $default
   * @return string
   * 
   */
  public static function getFlashMessage($key, $default = null) {
    // Get the message (all messages are prefixed with 'flash_')
    $message = self::get("flash_{$key}", $default);

    // Immediately unset the message
    self::clear("flash_{$key}");
    
    return $message;
  }
}
?>

