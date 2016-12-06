<?php

/**
 * Register all classes under TaskFree namespace to be autoloaded
 */
spl_autoload_register(function ($class) {
  $prefix = 'TaskFree\\';
  $baseDir = __DIR__ . '/';

  // Does the class use the namespace prefix?
  $len = strlen($prefix);

  if (strncmp($prefix, $class, $len) !== 0) {
      // No, move to the next registered autoloader
      return;
  }

  $relativeClass = substr($class, $len);

  $file = rtrim($baseDir, '/') . '/' . str_replace('\\', '/', $relativeClass) . '.php';

  if (file_exists($file)) {
      require $file;
  }
});

?>
