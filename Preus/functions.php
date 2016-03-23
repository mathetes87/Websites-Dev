<?php
// functions.php

/**
 * Directly renders a partial to the screen
 * 
 * @param string $file the filesystem path to the partial
 * @param array $vars variables that should be available to the partial
 */
function include_partial($file, $vars = array()) {
   // let get_partial do all the work - this is just a shortcut to 
   // render it immediately
   echo get_partial($file, $vars);
}

/**
 * Get the contents of a partial as a string
 * 
 * @param string $file the filesystem path to the partial
 * @param array $vars variables that should be available to the partial
 */
function get_partial($file, $vars = array()) {
    // open a buffer
    ob_start();

    // import the array items to local variables
    // ie 'someKey => 'someValue' in the array can be accessed as $someKey
    extract($vars);

    // include the partial file
    include($file);

    // get the contents of the buffer and clean it out
    // then return that
    return ob_get_clean();
}
?>