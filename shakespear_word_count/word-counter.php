<?php

$words = array();

// make sure command has been given correct number of args
if (count($argv) != 2 || $argv[1] == '') {
  print_r("\nUsage: php word-counter.php FILENAME.txt\n\n");
  exit;
}
else {
  word_count($argv, $words);
  display_results($words);
}


/**
 * Count the number of words in a given text file.
 * 
 * @param  array $argv
 *     Array of arguments passed into the script.
 * @param  array &$words
 *     Array of word counts indexed by word, pased by reference.
 */
function word_count($argv, &$words) {
  // get file path
  $file_path = $argv[1];

  // create file handle for processing
  $file_hande = fopen($file_path, 'r');

  if ($file_hande) {
    // process one line at a time
    while (($line = fgets($file_hande)) !== FALSE) {
      word_count_by_line($line, $words);
    }

    fclose($file_hande);
  }
  else {
    // error opening file
    print_r("File '" . $file_path . "' could not be opened.\n");
  }
}


/**
 * Split line into words and add counts to $words array.
 * 
 * @param  string $line
 *     Single line from input text file.
 * @param  array  &$words
 *     Array of word counts indexed by word, pased by reference.
 */
function word_count_by_line($line, &$words) {

}


/**
 * Display results of word count in format specified in README.md
 * 
 * @param  array $words
 *     Array of word counts indexed by word.
 */
function display_results($words) {

}
