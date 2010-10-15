<?php

# TODO - still working on logic here
# This method converts typical Java class names into database table names
# (OrderItem -> order_items, User -> users).
function get_tablename($classname)
{
  # bust the given class name into an array of words
  $pieces = preg_split("/([A-Z]+)/", $classname, -1, PREG_SPLIT_DELIM_CAPTURE+PREG_SPLIT_NO_EMPTY);
  $length = count($pieces);
  $words = array();
  for ($i=0; $i<$length; $i++)
  {
    $w = $pieces[$i] . $pieces[$i+1];
    array_push($words, $w);
    # double up on the counter
    $i++;
  }

  # convert all the 'words' to lowercase
  $length = count($words);
  for ($i=0; $i<$length; $i++)
  {
    $words[$i] = strtolower($words[$i]);
  }

  # create one new word from the words
  return implode('_', $words) . 's';
  
}

# returns 'users'
#$table_name = get_tablename('User');
#echo "$table_name\n";

# returns 'order_items'
#$table_name = get_tablename('OrderItem');
#echo "$table_name\n";

?>

