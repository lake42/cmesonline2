<?php
// $Id: sql.drush.inc,v 1.70 2010/12/08 14:58:47 greg1anderson Exp $

/**
 * @file Drush sql commands
 */

/**
 * Implementation of hook_drush_help().
 */
function sql_drush_help($section) {
  switch ($section) {
    case 'drush:sql-conf':
      return dt('Show database connection details.');
    case 'drush:sql-connect':
      return dt('A string which connects to the current database.');
    case 'drush:sql-cli':
      return dt('Quickly enter the mysql command line.');
    case 'drush:sql-dump':
      return dt('Prints the whole database to STDOUT or save to a file.');
    case 'drush:sql-query':
      return dt("Usage: drush [options] sql-query <query>...\n<query> is a SQL statement, which can alternatively be passed via STDIN. Any additional arguments are passed to the mysql command directly.");
    case 'drush:sql-sync':
      return dt("Usage: drush [options] sql-sync <source_alias> <target_alias>.\n <source_alias> and <target_alias> are site-aliases, or names of directories under \'sites\'. These determine from where and to where you want your database copied.");
  }
}

/**
 * Implementation of hook_drush_command().
 */
function sql_drush_command() {
  $options['--database'] = 'The DB connection key if using multiple connections in settings.php.';
  if (drush_drupal_major_version() >= 7) {
    $options['--target'] = 'The name of a target within the specified database.';
  }

  $items['sql-drop'] = array(
    'description' => 'Drop all tables in a given database.',
    'arguments' => array(
    ),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_DATABASE,
    'options' => array(
      '--yes' => 'Skip confirmation and proceed.',
      '--result-file' => 'Save to a file. The file should be relative to Drupal root. Recommended.',
    ) + $options,
  );
  $items['sql-conf'] = array(
    'description' => 'Print database connection details using print_r().',
    'arguments' => array(
      'all' => 'Show all database connections, instead of just one.',
      'show-passwords' => 'Show database password.',
    ),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_CONFIGURATION,
    'options' => $options,
  );
  $items['sql-connect'] = array(
    'description' => 'A string for connecting to the DB.',
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_CONFIGURATION,
    'options' => $options,
    'examples' => array(
      '`drush sql-connect` < example.sql' => 'Import sql statements from a file into the current database.',
    ),
  );
  $items['sql-dump'] = array(
    'callback' => 'drush_sql_dump_execute',
    'description' => 'Exports the Drupal DB as SQL using mysqldump or equivalent.',
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_CONFIGURATION,
    'examples' => array(
      'drush sql-dump --result-file=../18.sql' => 'Save SQL dump to the directory above Drupal root.',
      'drush sql-dump --skip-tables-key=common' => 'Skip standard tables. @see example.drushrc.com',
    ),
    'options' => array(
      '--result-file' => 'Save to a file. The file should be relative to Drupal root. Recommended.',
      '--skip-tables-key' => 'A key in the $skip_tables array. @see example.drushrc.php. Optional.',
      '--structure-tables-key' => 'A key in the $structure_tables array. @see example.drushrc.php. Optional.',
      '--tables-key' => 'A key in the $tables array. Optional.',
      '--tables-list' => 'A comma-separated list of tables to transfer. Optional.',
      '--ordered-dump' => 'Use this option to output ordered INSERT statements in the sql-dump.Useful when backups are managed in a Version Control System. Optional.',
      '--create-db' => 'Wipe existing tables.',
      '--data-only' => 'Omit CREATE TABLE statements. Postgres only.',
      '--ordered-dump' => 'Order by primary key and add line breaks for efficient diff in revision control. Also, faster rsync. Slows down the dump. Mysql only.',
    ) + $options,
  );
  $items['sql-query'] = array(
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_DATABASE,
    'description' => 'Execute a query against the site database.',
    'examples' => array(
      'drush sql-query "SELECT * FROM {users} WHERE uid=1"' => 'Browse user record. Table prefixes are honored.',
    ),
    'arguments' => array(
       'query' => 'A SQL query. Mandatory.',
    ),
    'options' => array(
      '--extra' => 'Add custom options to the mysql command.',
    ) + $options,
    'aliases' => array('sqlq'),
  );
  $items['sql-sync'] = array(
    'description' => 'Copy source database to target database using rsync.',
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH,
    'examples' => array(
      'drush sql-sync @dev @prod' => 'Copy the DB defined in sites/dev to the DB in sites/prod.',
    ),
    'arguments' => array(
      'from' => 'Name of subdirectory within /sites or a site-alias.',
      'to' => 'Name of subdirectory within /sites or a site-alias.',
    ),
    'options' => array(
      '--skip-tables-key' => 'A key in the $skip_tables array. @see example.drushrc.php. Optional.',
      '--structure-tables-key' => 'A key in the $structure_tables array. @see example.drushrc.php. Optional.',
      '--tables-key' => 'A key in the $tables array. Optional.',
      '--tables-list' => 'A comma-separated list of tables to transfer. Optional.',
      '--cache' => 'Skip dump if result file exists and is less than "cache" hours old. Optional; default is 24 hours.',
      '--no-cache' => 'Do not cache the sql-dump file.',
      '--no-dump' => 'Do not dump the sql database; always use an existing dump file.',
      '--source-db-url' => 'Database specification for source system to dump from.',
      '--source-remote-port' => 'Override sql database port number in source-db-url. Optional.',
      '--source-remote-host' => 'Remote machine to run sql-dump file on. Optional; default is local machine.',
      '--source-dump' => 'Path to dump file. Optional; default is to create a temporary file.',
      '--target-database' => 'A key in the $db_url (D6) or $databases (D7+) array which provides the data.',
      '--source-target' => 'Oy. A key within the --target_database identifying a particular server in the database group.',
      '--target-db-url' => '',
      '--target-remote-port' => '',
      '--target-remote-host' => '',
      '--target-dump' => '',
      '--target-database' => 'A key in the $db_url (D6) or $databases (D7+) array which shall receive the data.',
      '--target-target' => 'Oy. A key within the --target_database identifying a particular server in the database group.',
      '--temp' => 'Use a temporary file to hold dump files.  Implies --no-cache.',
      '--dump-dir' => 'Directory to store sql dump files in when --source-dump or --target-dump are not used.  Takes precedence over --temp.',
      '--create-db' => 'Create a new database before importing the database dump on the target machine.',
      '--db-su' => 'Account to use when creating a new database. Optional.',
      '--db-su-pw' => 'Password for the "db-su" account. Optional.',
      '--sanitize' => 'Obscure email addresses and reset passwords in the user table post-sync. Optional.',
    ),
    'sub-options' => array(
      '--sanitize' => array(
        '--sanitize-password' => 'The password to assign to all accounts in the sanitization operation, or "no" to keep passwords unchanged.  Default is "password".',
        '--sanitize-email' => 'The username for test email addresses in the sanitization operation, or "no" to keep email addresses unchanged.  May contain replacement patterns %uid, %mail or %login.  Default is "user+%uid@localhost".',
        '--confirm-sanitizations' => 'Prompt yes/no after importing the database, but before running the sanitizations',
      ),
    ),
  );
  if (drush_drupal_major_version() >= 7) {

    $items['sql-sync']['options'] += array(
      '--source-target' => 'The name of a target within the SOURCE database.',
      '--destination-target' => 'The name of a target within the specified DESTINATION database.',
    );
  }
  $items['sql-cli'] = array(
    'description' => "Open a SQL command-line interface using Drupal's credentials.",
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_CONFIGURATION,
    'options' => $options,
    'aliases' => array('sqlc'),
  );
  return $items;
}

/**
 * Command callback. Displays the Drupal site's database connection string.
 */
function drush_sql_conf() {
  if (drush_get_option('db-url', FALSE)) {
    $db_spec['db-url'] = $GLOBALS['db_url'];
  }
  elseif (drush_get_option('all', FALSE)) {
    $db_spec = _drush_sql_get_all_db_specs();
  }
  if (!isset($db_spec)) {
    $db_spec = _drush_sql_get_db_spec();
  }
  drush_backend_set_result($db_spec);
  if (!drush_get_option('show-passwords', FALSE)) {
    drush_unset_recursive($db_spec, 'password');
  }
  drush_print_r($db_spec);
}

/**
 * Command callback. Emits a connect string for mysql or pgsql.
 */
function _drush_sql_connect($db_spec = NULL) {
  switch (_drush_sql_get_scheme($db_spec)) {
    case 'mysql':
      $command = 'mysql ';
      $command .= _drush_sql_get_credentials($db_spec);
      break;
    case 'pgsql':
      $command = 'psql';
      $command .= _drush_sql_get_credentials($db_spec);
      break;
    case 'sqlite':
      $command = 'sqlite3 ';
      $command .= _drush_sql_get_credentials($db_spec);
      break;
  }
  return $command;
}

function drush_sql_connect() {
  drush_print(_drush_sql_connect());
}

/**
 * Command callback. Outputs the entire Drupal database in SQL format using mysqldump.
 */
function drush_sql_dump_execute() {
  list($exec, $file) = drush_sql_dump();
  // Avoid the php memory of the $output array in drush_shell_exec().
  if (!$return = drush_op_system($exec)) {
    drush_log(dt('Database dump saved to !path', array('!path' => $file)), 'success');
  }
  return $return;
}

function drush_sql_get_table_selection() {
  // Skip large core tables if instructed.  Also used by 'sql-sync' command.
  $skip_tables = _drush_sql_get_table_list('skip-tables');
  // Skip any structure-tables as well.
  $structure_tables = _drush_sql_get_table_list('structure-tables');
  // Dump only the specified tables.  Takes precedence over skip-tables and structure-tables.
  $tables = _drush_sql_get_table_list('tables');

  return array('skip' => $skip_tables, 'structure' => $structure_tables, 'tables' => $tables);
}

/**
 * Build a mysqldump/pg_dump/sqlite statement.
 *
 * @param db_spec
 *   For D5/D6, a $db_url. For D7, a target in the default DB connection.
 * @return array
 *   An array with items.
 *     1. A mysqldump/pg_dump/sqlite statement that is ready for executing.
 *     2. The filepath where the dump will be saved.
 */
function drush_sql_dump($db_spec = NULL) {
  return drush_sql_build_dump_command(drush_sql_get_table_selection(), $db_spec);
}

/**
 * Build a mysqldump/pg_dump/sqlite statement.
 *
 * @param db_spec
 *   For D5/D6, a $db_url. For D7, a target in the default DB connection.
 * @return array
 *   An array with items.
 *     1. A mysqldump/pg_dump/sqlite statement that is ready for executing.
 *     2. The filepath where the dump will be saved.
 */
function drush_sql_build_dump_command($tabel_selection, $db_spec = NULL) {
  $skip_tables = $tabel_selection['skip'];
  $structure_tables = $tabel_selection['structure'];
  $tables = $tabel_selection['tables'];

  $ignores = array();
  $skip_tables  = array_merge($structure_tables, $skip_tables);
  $data_only = drush_get_option('data-only');
  // The ordered-dump option is only supported by MySQL for now.
  // @todo add documention once a hook for drush_get_option_help() is available.
  // @see drush_get_option_help() in drush.inc
  $ordered_dump = drush_get_option('ordered-dump');

  if (is_null($db_spec)) {
    $db_spec = _drush_sql_get_db_spec();
  }
  $database = $db_spec['database'];
  
  // Get the setting of --result-file.  If the user
  // has set $options['result-file'] = TRUE, then we
  // will generate an SQL dump file in the same backup
  // directory that pm-updatecode uses.
  $file = NULL;
  if ($file = drush_get_option('result-file', FALSE)) {
    if ($file === TRUE) {
      // User did not pass a specific value for --result-file. Make one.
      drush_include_engine('version_control', 'backup');
      $backup = new drush_pm_version_control_backup();
      $backup_dir = $backup->prepare_backup_dir();
      if (empty($backup_dir)) {
        $backup_dir = "/tmp";
      }
      $file = $backup_dir . '/@DATABASE_@DATE.sql';
    }
    $file = str_replace(array('@DATABASE', '@DATE'), array($database, date('Ymd_his')), $file);
  }

  switch (_drush_sql_get_scheme($db_spec)) {
    case 'mysql':
      $exec = 'mysqldump';
      if ($file) {
        $exec .= ' --result-file '. $file;
      }
      // mysqldump wants 'databasename' instead of 'database=databasename' for no good reason.
      $extra = ' --single-transaction --opt -Q' . str_replace('--database=', ' ', _drush_sql_get_credentials($db_spec));
      if (isset($data_only)) {
        $extra .= ' --no-create-info';
      }
      if (isset($ordered_dump)) {
       $extra .= ' --skip-extended-insert --order-by-primary';
      }
      $exec .= $extra;

      if (!empty($tables)) {
        $exec .= ' ' . implode(' ', $tables);
      }
      else {
        // Append the ignore-table options.
        foreach ($skip_tables as $table) {
          $ignores[] = "--ignore-table=$database.$table";
        }
        $exec .= ' '. implode(' ', $ignores);

        // Run mysqldump again and append output if we need some structure only tables.
        if (!empty($structure_tables)) {
          $exec .= "; mysqldump --no-data $extra " . implode(' ', $structure_tables);
          if ($file) {
            $exec .= " >> $file";
          }
        }
      }
      break;
    case 'pgsql':
      $create_db = drush_get_option('create-db');
      $exec = 'pg_dump ';
      if ($file) {
        $exec .= ' --file '. $file;
      }
      // Unlike psql, pg_dump does not take a '-d' flag before the database name.
      // We'll put the database name in 'by hand' and then clear the name from
      // the record to prevent _drush_sql_get_credentials from inserting a -d term
      // that pg_dump does not understand.
      $extra = ' ' . $db_spec['database'];
      $db_spec['database'] = null;
      $extra .= _drush_sql_get_credentials($db_spec);
      if (isset($data_only)) {
        $extra .= ' --data-only';
      }
      $exec .= $extra;
      $exec .= (!isset($create_db) && !isset($data_only) ? ' --clean' : '');

      if (!empty($tables)) {
        foreach ($tables as $table) {
          $exec .= " --table=$table";
        }
      }
      else {
        foreach ($skip_tables as $table) {
          $ignores[] = "--exclude-table=$table";
        }
        $exec .= ' '. implode(' ', $ignores);
        // Run pg_dump again and append output if we need some structure only tables.
        if (!empty($structure_tables)) {
          $schemaonlies = array();
          foreach ($structure_tables as $table) {
            $schemaonlies[] = "--table=$table";
          }
          $exec .= "; pg_dump --schema-only " . implode(' ', $schemaonlies) . $extra;
          if ($file) {
            $exec .= " >> $file";
          }
        }
      }
      break;
    case 'sqlite':
      // Dumping is usually not necessary in SQLite, since all database data
      // is stored in a single file on the filesystem which can be copied just
      // like any other file. But it still has a use in migration purposes and
      // building human-readable diffs and such, so let's do it anyway.
      $exec = _drush_sql_connect();
      // SQLite's dump command doesn't support many of the features of its
      // Postgres or MySQL equivalents. We may be able to fake some in the
      // future, but for now, let's just support simple dumps.
      $exec .= ' ".dump"';
      if ($file = drush_get_option('result-file')) {
        $exec .= ' > '. $file;
      }
      break;
  }

  return array($exec, $file);
}

/**
 * Consult the specified options and return the list of tables
 * specified.
 *
 * @param option_name
 *   The option name to check: skip-tables, structure-tables
 *   or tables.  This funciton will check both *-key and *-list,
 *   and, in the case of sql-sync, will also check target-*
 *   and source-*, to see if an alias set one of these options.
 * @returns array
 *   Returns an array of tables based on the first option
 *   found, or an empty array if there were no matches.
 */
function _drush_sql_get_table_list($option_name) {
  foreach(array('' => 'options', 'target-,,source-' => NULL) as $prefix_list => $context) {
    foreach(explode(',',$prefix_list) as $prefix) {
      $key_list = drush_get_option($prefix . $option_name . '-key', NULL, $context);
      foreach(explode(',', $key_list) as $key) {
        $all_tables = drush_get_option($option_name, array());
        if (array_key_exists($key, $all_tables)) {
          return $all_tables[$key];
        }
        if ($option_name != 'tables') {
          $all_tables = drush_get_option('tables', array());
          if (array_key_exists($key, $all_tables)) {
            return $all_tables[$key];
          }
        }
      }
      $table_list = drush_get_option($prefix . $option_name . '-list', NULL, $context);
      if (isset($table_list)) {
        return empty($table_list) ? array() : explode(',', $table_list);
      }
    }
  }

  return array();
}

/**
 * Command callback. Executes the given SQL query on the Drupal database.
 */
function drush_sql_query($query) {
  return _drush_sql_query($query);
}

function _drush_sql_query($query, $db_spec = NULL) {
  $scheme = _drush_sql_get_scheme($db_spec);

  // Inject table prefixes as needed.
  if (drush_drupal_major_version() >= 7) {
    $query = Database::getConnection()->prefixTables($query);
  }
  else {
    $query = db_prefix_tables($query);
  }

  // Convert mysql 'show tables;' query into something pgsql understands
  if (($scheme == 'pgsql') && ($query == 'show tables;')) {
    $query = drush_sql_show_tables_pgsql();
  }

  // Save $query to a tmp file. We will redirect it in.
  if ($filename = drush_save_data_to_temp_file($query)) {
    $exec = drush_sql_build_exec($db_spec, $filename);
    // In --simulate mode, drush_op will show the call to mysql or psql,
    // but the sql query itself is stored in a temp file and not displayed.
    // We will therefore show the query explicitly in the interest of full disclosure.
    if (drush_get_context('DRUSH_SIMULATE')) {
      drush_print('sql-query: ' . $query);
    }
    $return = drush_op_system($exec) == 0;
    return $return;
  }
}

function drush_sql_drop() {
  if (!drush_confirm(dt('Do you really want to drop all tables?'))) {
    drush_user_abort();
  }

  // TODO: integrate with _drush_sql_get_table_list?

  $scheme = _drush_sql_get_scheme();
  switch ($scheme) {
    case 'pgsql':
      $query = drush_sql_show_tables_pgsql();
      break;
    case 'sqlite':
      $query = '.tables';
      break;
    default:
      $query = 'SHOW TABLES;';
  }
  
  $filename = drush_save_data_to_temp_file($query);
  $exec = drush_sql_build_exec(NULL, $filename);
  
  // Actually run this prep query no matter if in SIMULATE.
  $old = drush_get_context('DRUSH_SIMULATE');
  drush_set_context('DRUSH_SIMULATE', FALSE);
  drush_shell_exec($exec);
  drush_set_context('DRUSH_SIMULATE', $old);
  $tables = drush_shell_exec_output();
  if ($scheme === 'sqlite') {
    // SQLite's '.tables' command always outputs the table names in a column
    // format, like this:
    // table_alpha    table_charlie    table_echo
    // table_bravo    table_delta      table_foxtrot
    // …and there doesn't seem to be a way to fix that. So we need to do some
    // clean-up.
    // Since we're already doing iteration here, might as well build the SQL
    // too, since SQLite only wants one table per DROP TABLE command (so we have
    // to do "DROP TABLE foo; DROP TABLE bar;" instead of
    // "DROP TABLE foo, bar;").
    $sql = '';
    foreach ($tables as $line) {
      preg_match_all('/[^\s]+/', $line, $matches);
      if (!empty($matches[0])) {
        foreach ($matches[0] as $match) {
          $sql .= "DROP TABLE {$match};";
        }
      }
    }
    // We can't use drush_op('db_query', $sql) because it will only perform one
    // SQL command and we're technically performing several.
    $exec = _drush_sql_connect();
    $exec .= " '{$sql}'";
    return drush_op_system($exec) == 0;
  }
  else {
    // Shift off the header of the column of data returned.
    array_shift($tables);
    $sql = 'DROP TABLE '. implode(', ', $tables);
    return drush_op('db_query', $sql);
  }
}

function drush_sql_cli() {
  switch (_drush_sql_get_scheme()) {
    case 'mysql':
      $command = 'mysql ' . (drush_get_context('DRUSH_VERBOSE') ? ' -v' : '');
      $command .= _drush_sql_get_credentials();
      break;
    case 'pgsql':
      $command = 'psql ';
      $command .= _drush_sql_get_credentials();
      break;
    case 'sqlite':
      $command = 'sqlite3 ';
      $command .= _drush_sql_get_credentials();
      break;
  }
  proc_close(proc_open($command, array(0 => STDIN, 1 => STDOUT, 2 => STDERR), $pipes));
}


//////////////////////////////////////////////////////////////////////////////
// SQL SERVICE HELPERS

/**
 * Get a database specification for the active DB connection. Honors the
 * 'database' and 'target command' line options.
 *
 * @return
 *   An info array describing a database target.
 */
function _drush_sql_get_db_spec() {
  $database = drush_get_option('database', 'default');
  $target = drush_get_option('target', 'default');

  switch (drush_drupal_major_version()) {
    case 5:
    case 6:
      $url = $GLOBALS['db_url'];
      // TODO: array version not working?
      $url =  is_array($url) ? $url[$database] : $url;
      return drush_convert_db_from_db_url($url);

    default:
      // We don't use DB API here `sql-sync` would have to messily addConnection.
      if (!isset($GLOBALS['databases']) || !array_key_exists($database, $GLOBALS['databases']) || !array_key_exists($target, $GLOBALS['databases'][$database])) {
        return NULL;
      }
      return $GLOBALS['databases'][$database][$target];
  }
}

function _drush_sql_get_all_db_specs() {
  switch (drush_drupal_major_version()) {
    case 5:
    case 6:
      return drush_sitealias_convert_db_from_db_url($GLOBALS['db_url']);

    default:
      if (!isset($GLOBALS['databases'])) {
        return NULL;
      }
      return $GLOBALS['databases'];
  }
}

function _drush_sql_get_spec_from_options($prefix, $default_to_self = TRUE) {
  $db_spec = NULL;
  $databases = drush_get_option($prefix . 'databases');
  if (isset($databases) && !empty($databases)) {
    $database = drush_get_option($prefix . 'database', 'default');
    $target = drush_get_option($prefix . 'target', 'default');
    if (array_key_exists($database, $databases) && array_key_exists($target, $databases[$database])) {
      $db_spec = $databases[$database][$target];
    }
  }
  else {
    $db_url = drush_get_option($prefix . 'db-url');
    if (isset($db_url)) {
      $db_spec = drush_convert_db_from_db_url($db_url);
    }
    elseif ($default_to_self) {
      $db_spec = _drush_sql_get_db_spec();
    }
  }

  if (isset($db_spec)) {
    $remote_host = drush_get_option($prefix . 'remote-host');
    if (!drush_is_local_host($remote_host)) {
      $db_spec['remote-host'] = $remote_host;
      $db_spec['port'] = drush_get_option($prefix . 'remote-port', $db_spec['port']);
    }
  }

  return $db_spec;
}

/**
 * Determine where to store an sql dump file.  This
 * function is called by sql-sync if the caller did
 * not explicitly specify a dump file to use.
 *
 * @param db_spec
 *   Information about the database being dumped; used
 *   to generate the filename.
 * @return string
 *   The path to the dump file
 */
function drush_sql_dump_file(&$db_spec, $prefix) {
  // Use an entry in the db spec to indicate whether the dump
  // file we use is temporary or not.
  $db_spec['dump-is-temp'] = FALSE;
  // Make a base filename pattern to use to name the dump file
  $filename_pattern = $db_spec['database'];
  if (isset($db_spec['remote-host'])) {
    $filename_pattern = $db_spec['remote-host'] . '_' . $filename_pattern;
  }
  // If the user has set the --{prefix}-dir option, then
  // use the exact name provided.
  $dump_file = drush_get_option($prefix . 'dump');
  if (!isset($dump_file)) {
    // If the user has set the --dump-dir option, then
    // store persistant sql dump files there.
    $dump_dir = drush_get_option(array($prefix . 'dump-dir', 'dump-dir'));
    if (isset($dump_dir)) {
      $dump_file = $dump_dir . '/' . $filename_pattern . '.sql';
    }
    // If the --dump-dir option is not set, then store
    // the sql dump in a temporary file.
    else {
      $dump_file = drush_tempnam($filename_pattern . '.sql.');
      $db_spec['dump-is-temp'] = TRUE;
    }
  }
  return $dump_file;
}

function _drush_sql_get_scheme($db_spec = NULL) {
  if (is_null($db_spec)) {
    $db_spec = _drush_sql_get_db_spec();
  }
  return $db_spec['driver'];
}

/**
 * Build a fragment containing credentials and mysql-connection parameters.
 *
 * @param $db_spec
 * @return string
 */
function _drush_sql_get_credentials($db_spec = NULL) {
  if (is_null($db_spec)) {
    $db_spec = _drush_sql_get_db_spec();
  }

  // Build an array of key-value pairs for the parameters.
  $parameters = array();

  switch (_drush_sql_get_scheme($db_spec)) {
    case 'mysql':
      // Some drush commands (e.g. site-install) want to connect to the
      // server, but not the database.  Connect to the built-in database.
      $parameters['database'] = empty($db_spec['database']) ? 'information_schema' : $db_spec['database'];

      // Host is required.
      $parameters['host'] = $db_spec['host'];

      // An empty port is invalid.
      if (!empty($db_spec['port'])) {
        $parameters['port'] = $db_spec['port'];
      }

      // User is required. Drupal calls it 'username'. MySQL calls it 'user'.
      $parameters['user'] = $db_spec['username'];

      // EMPTY password is not the same as NO password, and is valid.
      if (isset($db_spec['password'])) {
        $parameters['password'] = $db_spec['password'];
      }
      break;

    case 'pgsql':
      // Database is optional in Postgres.
      if (isset($db_spec['database'])) {
        $parameters['dbname'] = empty($db_spec['database']) ? 'template1' : $db_spec['database'];
      }

      // Host and port are optional but have defaults.
      $parameters['host'] = empty($db_spec['host']) ? 'localhost' : $db_spec['host'];
      $parameters['port'] = empty($db_spec['port']) ? '5432' : $db_spec['port'];

      // Username is required.
      $parameters['username'] = $db_spec['username'];

      // Don't set the password.
      // @see http://drupal.org/node/438828
      break;

    case 'sqlite':
      // SQLite doesn't do user management, instead relying on the filesystem
      // for that. So the only info we really need is the path to the database
      // file, and not as a "--key=value" parameter.
      return ' ' . $db_spec['database'];
      break;
  }

  // Turn each parameter into a valid parameter string.
  $parameter_strings = array();
  foreach ($parameters as $key => $value) {
    // Only escape the values, not the keys or the rest of the string.
    $value = escapeshellcmd($value);
    $parameter_strings[] = "--$key=$value";
  }

  // Join the parameters and return.
  return ' ' . implode(' ', $parameter_strings);
}

function _drush_sql_get_invalid_url_msg($db_spec = NULL) {
  if (is_null($db_spec)) {
    $db_spec = _drush_sql_get_db_spec();
  }
  switch (drush_drupal_major_version()) {
    case 5:
    case 6:
      return dt('Unable to parse DB connection string');
   default:
     return dt('Unable to parse DB connection array');
  }
}

/**
 * Call from a pre-sql-sync hook to register an sql
 * query to be executed in the post-sql-sync hook.
 * @see drush_sql_pre_sql_sync() and @see drush_sql_post_sql_sync().
 *
 * @param $id
 *   String containing an identifier representing this
 *   operation.  This id is not actually used at the
 *   moment, it is just used to fufill the contract
 *   of drush contexts.
 * @param $message
 *   String with the confirmation message that describes
 *   to the user what the post-sync operation is going
 *   to do.  This confirmation message is printed out
 *   just before the user is asked whether or not the
 *   sql-sync operation should be continued.
 * @param $query
 *   String containing the sql query to execute.  If no
 *   query is provided, then the confirmation message will
 *   be displayed to the user, but no action will be taken
 *   in the post-sync hook.  This is useful for drush modules
 *   that wish to provide their own post-sync hooks to fix
 *   up the target database in other ways (e.g. through
 *   Drupal APIs).
 */
function drush_sql_register_post_sync_op($id, $message, $query = NULL) {
  $options = drush_get_context('post-sync-ops');

  $options[$id] = array('message' => $message, 'query' => $query);

  drush_set_context('post-sync-ops', $options);
}

/**
 * Builds a confirmation message for all post-sync operations.
 *
 * @return string
 *   All post-sync operation messages concatenated together.
 */
function _drush_sql_get_post_sync_messages() {
  $messages = FALSE;

  $options = drush_get_context('post-sync-ops');
  if (!empty($options)) {
    $messages = dt('The following post-sync operations will be done on the destination:') . "\n";

    foreach($options as $id => $data) {
      $messages .= "  * " . $data['message'] . "\n";
    }
  }

  return $messages;
}

// Convert mysql 'show tables;' query into something pgsql understands.
function drush_sql_show_tables_pgsql() {
  return "select tablename from pg_tables where schemaname='public';";
}

function drush_sql_build_exec($db_spec, $filepath) {
  $scheme = _drush_sql_get_scheme($db_spec);
  switch ($scheme) {
    case 'mysql':
      $exec = 'mysql' . (drush_get_context('DRUSH_VERBOSE') ? ' -v' : '');
      $exec .= _drush_sql_get_credentials($db_spec);
      $exec .= ' ' . drush_get_option('extra');
      $exec .= " < $filepath";

      break;
    case 'pgsql':
      $exec = 'psql';
      $exec .= _drush_sql_get_credentials($db_spec);
      $exec .= (drush_get_context('DRUSH_VERBOSE') ? '' : ' -q');
      $exec .= ' ' . (drush_get_option('extra') ? drush_get_option('extra') : "--no-align --field-separator='\t' --pset footer=off");
      $exec .= " --file $filepath";
      break;
    case 'sqlite':
      $exec = 'sqlite3';
      $exec .= ' ' . drush_get_option('extra');
      $exec .= _drush_sql_get_credentials($db_spec);
      $exec .= " < $filepath";
      break;
  }
  return $exec;
}
