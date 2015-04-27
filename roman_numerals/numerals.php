<?php

// must have input an argument
if (count($argv) == 1) {
  print_r("Please enter an integer value.\n");
}

// each arg must be numeric
foreach ($argv as $ind => $arg) {
  if ($arg === '0') {
    print_r("'" . $arg . "' is undefined in Roman numerals.\n\n");
  }
  else {
    if ($ind && is_numeric($arg)) {
      if ($arg > 1000000000 && $arg <= 10000000000000) {
        print_r("Your input is greater than 1 billion, but the answer is:\n\n");
        get_and_print_numeral(round($arg));
      }
      elseif ($arg > 10000000000000) {
        print_r("That's too much man!");
        die;
      }
    }
    elseif ($ind && !is_numeric($arg)) {
      print_r("'" . $arg . "' is not a valid integer.\n\n");
    }
  }
}

/**
 * Convert and print decimal input integer as Roman numeral
 * 
 * @param  int $decimal_input
 *  Number to convert.
 */
function get_and_print_numeral($decimal_input) {
  // debug flag
  $debug = FALSE;

  // decimal => numeral map
  $basic_map = array(
    1 => 'I',
    4 => 'IV',
    5 => 'V',
    9 => 'IX',
    10  => 'X',
    40  => 'XL',
    50  => 'L',
    90  => 'XC',
    100 => 'C',
    400 => 'CD',
    500 => 'D',
    900 => 'CM',
    1000  => 'M',
    4000 => 'MV',
    5000  => 'V',
    9000 => 'MX',
    10000 => 'X',
    40000 => 'XL',
    50000 => 'L',
    90000 => 'XC',
    100000  => 'C',
    400000  => 'CD',
    500000  => 'D',
    900000  => 'CM',
    1000000 => 'M',
    1000000000 => 'M',
  );

  // answer string and modifier strings
  $full_numeral_string = '';
  $exponent_string = '';

  print_r($decimal_input . ":\n");

  // check for negative number
  if ($decimal_input < 0) {
    $full_numeral_string .= '-';
    $exponent_string .= ' ';
    $decimal_input = abs($decimal_input);
  }

  // calculate numeral version of number
  recursive_get_numeral($decimal_input, $full_numeral_string, $exponent_string, $basic_map, $debug);

  $display_array = array();

  // split into n caracter lines
  $n = 100;
  for ($i = 0; $i < strlen($full_numeral_string); $i += $n) {
    // modifier line goes above answer line
    $display_array[] = substr($exponent_string, $i, $n);
    $display_array[] = substr($full_numeral_string, $i, $n);
  }


  // display the lines
  foreach ($display_array as $line) {
    print_r($line . "\n");
  }
  print_r("\n");
}

/**
 * Recursively generate Roman numeral for given positive integer.
 * @param  int $x
 *  Input integer to be converted.
 * 
 * @param  string &$full_numeral_string
 *  Current roman numeral string passed by reference.
 *   
 * @param  string &$exponent_string
 *  Current modifier string for diplaying bars above numerals.
 *  
 * @param  array $basic_map
 *  Array of pre-defined integer-numeral conversions.
 *  
 * @param  boolean $debug
 *  $flag for showing debug messages.
 */
function recursive_get_numeral($x, &$full_numeral_string, &$exponent_string, $basic_map, $debug) {

  if ($debug) {
    print_r('x: ' . $x . "\n");
  }

  // $value to decrement
  $current_x = $x;

  // best numeral string for this iteration
  $num_string = '';

  // find highest map less than or equal to  x
  foreach ($basic_map as $dec => $num) {
    if ($dec <= $x) {
      // get decimal and numeral versions
      $highest = $dec;
      $num_string = $num;
    }
    else {
      break;
    }
  }

  // add spaces/underscores to modifier string
  if ($highest >= 4000) {
    if ($highest >= 1000000000) {
      $exponent_string .= '=';
    }
    else {
      $exponent_string .= (strlen($num_string) == 1) ? '_' : ' _';
    }
  }
  else {
    for ($i = 0; $i < strlen($num_string); $i++) {
      $exponent_string .= ' ';
    }
  }

  if ($debug) {
    print_r('highest: ' . $highest . "\n");
    print_r('numeral string: ' . $num_string . "\n");
  }

  // reduce current number by the value of latest numerals added
  $current_x -= $highest;

  // concat latest numerals onto answer string
  $full_numeral_string .= $num_string;

  if ($debug) {
    print_r('full numeral string: ' . $full_numeral_string . "\n");
  }

  // if current value is now 0, exit
  if ($current_x == 0) {
    return;
  }
  // otherwise recurse on remaining value
  else {
    recursive_get_numeral($current_x, $full_numeral_string, $exponent_string, $basic_map, $debug);
  }
}
