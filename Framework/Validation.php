<?php
namespace Framework;

class Validation {
  /**
   * Validate a string
   * 
   * @param string $value
   * @param int $min
   * @param int $max
   * @return bool
   */
  public static function string($value, $min = 1, $max = INF) {
    // is_string finds whether the type of a variable is string
    if (is_string($value)) {
      $value = trim($value);
      $length = strlen($value);

      return $length >= $min && $length <= $max;
    }

    return false;
  }

  /**
   * Validate email address
   * 
   * @param string $value
   * @return 
   * 
   */
  public static function email($value) {
    $value = trim($value);

    // filter_var filters a variable with a specified filter
    // Will return a boolean if it does NOT satisfies the condition or the actual email 
    return filter_var($value, FILTER_VALIDATE_EMAIL);
  }

  /**
   * Match a value against another
   * 
   * @param string $value1
   * @param string $value2
   * @return bool
   * 
   */
  public static function match($value1, $value2) {
    $value1 = trim($value1);
    $value2 = trim($value2);
  
    return $value1 === $value2;
  }
}
?>