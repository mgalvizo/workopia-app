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
 * @return void
 * 
 */
function loadView($name) {
  // Check if the view exists
  $viewPath = basePath("views/{$name}.view.php");
  
  // file_exists checks whether a file or directory exists
  if (file_exists($viewPath)) {
      require $viewPath;
  } else {
      echo "View '{$name}' not found!";
  }
}

/**
 * Load a partial
 * @param string $name
 * @return void
 * 
 */
function loadPartial($name) {

  $partialPath = basePath("views/partials/{$name}.php");

  // file_exists checks whether a file or directory exists
  if (file_exists($partialPath)) {
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
?>