<?php
/**
 * Get the base path
 * @param string $path
 * @return string
 */
function basePath($path = '') {
  // to return the absolute path
  return __DIR__ . '/' . $path;
}

/**
 * Load a view
 * @param string $name
 * @param string $data
 * @return void
 * 
 */
function loadView($name, $data = []) {
  // Check if the view exists
  $viewPath = basePath("App/views/{$name}.view.php");
  
  // file_exists checks whether a file or directory exists
  if (file_exists($viewPath)) {
    // extract imports variables into the current symbol table from an array
    // Makes easy to pass data into the view
    extract($data);

    require $viewPath;
  } else {
    echo "View '{$name}' not found!";
  }
}

/**
 * Load a partial
 * @param string $name
 * @param array $data
 * @return void
 * 
 */
function loadPartial($name, $data = []) {

  $partialPath = basePath("App/views/partials/{$name}.php");

  // file_exists checks whether a file or directory exists
  if (file_exists($partialPath)) {
    // extract imports variables into the current symbol table from an array
    // Makes easy to pass data into the view
    extract($data);
    require $partialPath;
  } else {
    echo "Partial '{$name}' not found!";
  }
}

/**
 * Inspect a value(s)
 * @param mixed $value
 * @return void
 * 
 */
function inspect($value) {
  echo '<pre>';
  var_dump($value);
  echo '</pre>';
}

/**
 * Inspect a value(s) and die
 * @param mixed $value
 * @return void
 * 
 */
function inspectAndDie($value) {
  echo '<pre>';
  // die is equivalent to exit
  die(var_dump($value));
  echo '</pre>';
}

/**
 * Format Salary
 * 
 * @param string $salary
 * @return string Formatted Salary
 * 
 */
function formatSalary($salary) {
  return '$' . number_format(floatval($salary));
}

/**
 * Sanitize Data
 * 
 * @param string $dirty
 * @return string
 * 
 */
function sanitize($dirty) {
  // Strip from any special characters
  return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect to a given URL
 * 
 * @param string $url
 * @return void
 * 
 */
function redirect($url) {
  header("Location: {$url}");
  exit;
}
?>