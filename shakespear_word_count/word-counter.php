<?php

$words = array();

$formatting = FALSE;
$show = -1;

// parse arguments
$parse_fail = FALSE;
$copy = $argv;
foreach ($copy as $ind => $arg) {
  // get extra flags
  if (strpos($arg, '--') != -1) {
    switch ($arg) {
      // set beter formatting flag
      case '--better-formatting':
        $formatting = TRUE;
        unset($argv[$ind]);
        break;

      // set number of words to show
      case '--show':
        if (!isset($argv[$ind + 1]) || !is_numeric($argv[$ind + 1])) {
          $parse_fail = TRUE;
          break;
        }
        $show = $argv[$ind + 1];
        unset($argv[$ind]);
        unset($argv[$ind + 1]);
        break;
    }
  }
}

// make sure command has been given correct number of args
if (count($argv) != 2 || $argv[1] == '' || $parse_fail) {
  print_r("\nUsage: php word-counter.php FILENAME.txt [--better-formatting] [--show INT]\n\n");
  exit;
}
else {
  word_count($argv, $words);
  display_results($words, $formatting, $show);
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

  // create file handle for processing (suppress errors)
  $file_hande = @fopen($file_path, 'r');

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
    exit;
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
  // remove all non alphabetic, non whitespace, non hyphen characters
  $alpha_line = preg_replace('/[^a-zA-Z \-]/', '', $line);

  // replace hyphens and underscores with spaces
  $clean_line = preg_replace('/[\-\_]/', ' ', $alpha_line);

  // make the line lowercase
  $lower_line = strtolower($clean_line);

  // divide into array of words
  $split_line = explode(' ', $lower_line);

  // add non blank words to $words array
  foreach ($split_line as $word) {
    if (isset($words[$word])) {
      $words[$word]++;
    }
    elseif ($word != '') {
      $words[$word] = 1;
    }
  }
}


/**
 * Display results of word count in format specified in README.md
 * 
 * @param  array $words
 *     Array of word counts indexed by word.
 */
function display_results($words, $formatting, $show) {
  // sort the words array keeping keys intact
  arsort($words);

  // print total word count (with thousands separator)
  print_r("There are " . number_format(count($words)) . " words in this document\n");

  // get the length of the longest word
  $maxlen = max(array_map('strlen', array_keys($words)));
  // print_r($maxlen . PHP_EOL);

  // print the words in descending order
  // $show = 5;
  $n = 0;

  foreach ($words as $word => $count) {
    // calculate spacing
    $spaces = $maxlen - strlen($word);

    // create separator
    $separator = ' ..';
    for ($i = 0; $i < $spaces; $i++) {
      $separator .= '.';
    }
    $separator .= ' ';

    // don't bother using separator if original formatting
    if (!$formatting) {
      $separator = ' ';
    }

    // print the results
    if ($show == -1 || $n < $show) {
      print_r(ucfirst($word) . $separator . number_format($count) . "\n");
      $n++;
    }
    else {
      exit;
    }
  }
}
