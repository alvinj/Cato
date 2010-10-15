#!/usr/bin/php
<?php
  # !/Applications/MAMP/bin/php5/bin/php -q

  # ----------------------------------------------------------------
  # files needed by this application:
  # app.config - user's definition of their database parameters
  #
  # * PHP MDB2 module
  # * Smarty templates
  #
  # ----------------------------------------------------------------

  # need this to really see error messages
  # http://php.net/manual/en/ref.errorfunc.php
  # error_reporting(E_ALL | E_STRICT); # this prints a *lot* of errors in pear
  error_reporting(E_ALL);

  # ini_set — sets the value of a configuration option
  ini_set("display_errors", 'On'); 

  # had to do this on my laptop to get mamp/php to work with my 'normal' mysql db
  # sudo ln -s /private/tmp/mysql.sock /Applications/MAMP/tmp/mysql/mysql.sock


  #------------------------------------------------
  # handle all of our "include" and "require" needs
  #------------------------------------------------
  # need the current directory to find the app.config file
  set_include_path(get_include_path() . PATH_SEPARATOR . '.');
  
  # must create a $smarty reference before reading the config file  
  require('Smarty/Smarty.class.php');
  $smarty = new Smarty();

  # get the user's database configuration, smarty template config, and more.
  # this file may further modify the include path, so i'm trying to bring it in
  # early; for instance, i have to modify the include path for mamp and mdb2.
  require_once 'app.config';
  
  require_once 'MDB2.php';
  require_once 'args.inc';
  require_once 'CrudMapUserInputToTableName.inc';
  
  # handles the logic of converting database table information into
  # the various types we can use in our templates
  require_once 'DatabaseTable.php';


  #--------------------------------
  # begin the main processing logic
  #--------------------------------

  # 0th element is program name, skip it
  $num_args = count($argv);
  if ($num_args <= 1)
  {
    usage();
    exit;
  }

  # we got at least one arg, figure out what it is
  if ($argv[1] != 'gen' && $argv[1] != 'generate')
  {
    echo "Dude, I don't know what you want.\n";
    usage();
    exit;
  }
  
  # next better be model, view, controller, or other template file name,
  # or there's going to be a big nasty error later.
  $template = $argv[2];

  # handling className and tableName in args 3 & 4  
  # next better be the name of the class (User, Order, OrderItem)
  $classname = $argv[3];
  $table_name = '';
  if (isset($argv[4]))
  {
    $table_name = $argv[4];
  }
  else
  {
    # TODO - this algorithm still needs some work
    $table_name = get_tablename($classname);
  }

  # use Pear MDB2
  # @see http://pear.php.net/package/MDB2/docs/latest/MDB2/MDB2.html
  $mdb =& MDB2::connect($dsn, $options);
  if (PEAR::isError($mdb)) {
    die($mdb->getMessage());
  }

  # get the database name
  $dbname = $mdb->getDatabase();

  # need the Manager module to do our magic
  # @see http://pear.php.net/package/MDB2/docs/latest/MDB2/MDB2_Driver_Manager_Common.html
  $mdb->loadModule('Manager');
  // Extended, Datatype, Manager, Reverse, Native, Function

  # get all the field names
  $table_field_names = $mdb->listTableFields($table_name);
  $nfields = count($table_field_names);

  # need to issue a query against the desired table to get the metadata
  $query = "SELECT * FROM $table_name";

  # on success this returns an MDB2_Result handle
  # TODO - deal with the failure condition here
  #$result = $mdb->query($query, true, true, 'MDB2_BufferedIterator');
  $result =& $mdb->query($query, true, true);

  # now that we have a result set, we can get the field types
  # as an array:
  $dt = new DatabaseTable();
  $dt->set_raw_table_name($table_name);
  $dt->set_raw_field_names($table_field_names);
  $dt->set_db_field_types($result->types);
  
  # assign all the smarty variables
  $smarty->assign('classname', $dt->get_camelcase_table_name());
  $smarty->assign('objectname', $dt->get_java_object_name());
  $smarty->assign('tablename', $table_name);
  $smarty->assign('fields', $dt->get_camelcase_field_names());
  $smarty->assign('fields_as_insert_csv_string', $dt->get_fields_as_insert_stmt_csv_list());
  $smarty->assign('prep_stmt_as_insert_csv_string', $dt->get_prep_stmt_insert_csv_string());
  $smarty->assign('prep_stmt_as_update_csv_string', $dt->get_fields_as_update_stmt_csv_list());
  $smarty->assign('types', $dt->get_java_field_types());
  $smarty->assign('dt', $dt);
  
  # TODO - this is hard-coded
  $out = $smarty->fetch("$template.tpl");
  echo $out;
  
  # may need this for error-handling
  #if (PEAR::isError($verify)) {
  #   return $verify;
  #}

  $mdb->disconnect();

?>
