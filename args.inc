#!/usr/bin/php
<?php

#-------------------------------------------------------------------
# These are library methods for handling command line arguments
# in the main application.
#
# The command line should look like these examples:
#
#   crud about
#   crud gen controller ClassName action
#   crud gen model ClassName
#   crud gen scaffold ClassName
#-------------------------------------------------------------------

function usage()
{
  echo "Usage statement here\n";
}

function do_gen_controller(&$args)
{
  echo "CONTROLLER\n";
  print_r($args);
}

function do_gen_model(&$args)
{
  echo "MODEL\n";
  print_r($args);
}

function do_gen_view(&$args)
{
  echo "VIEW\n";
  print_r($args);
}

function do_gen_action(&$args)
{
  print_r($args);
  # better be at least 4 args or we've got a problem
  $n = count($args);
  if ($n < 4)
  {
    echo "DUDE, n was < 4\n";
    usage();
    exit;
  }
  # third arg is what user wants to generate
  $gen = $args[2];
  if ($gen == 'controller') 
  {
    do_gen_controller($args);
  } 
  elseif ($gen == 'model') 
  {
    do_gen_model($args);
  }
  elseif ($gen == 'view') 
  {
    do_gen_view($args);
  }
}

?>
