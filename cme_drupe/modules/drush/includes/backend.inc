<?php
// $Id: backend.inc,v 1.34 2010/12/09 01:10:11 greg1anderson Exp $

/**
 * @file Drush backend API
 *
 * When a drush command is called with the --backend option,
 * it will buffer all output, and instead return a JSON encoded
 * string containing all relevant information on the command that
 * was just executed.
 *
 * Through this mechanism, it is possible for Drush commands to
 * invoke each other.
 *
 * There are many cases where a command might wish to call another
 * command in its own process, to allow the calling command to
 * intercept and act on any errors that may occur in the script that
 * was called.
 *
 * A simple example is if there exists an 'update' command for running
 * update.php on a specific site. The original command might download
 * a newer version of a module for installation on a site, and then
 * run the update script in a separate process, so that in the case
 * of an error running a hook_update_n function, the module can revert
 * to a previously made database backup, and the previously installed code.
 *
 * By calling the script in a separate process, the calling script is insulated
 * from any error that occurs in the called script, to the level that if a
 * php code error occurs (ie: misformed file, missing parenthesis, whatever),
 * it is still able to reliably handle any problems that occur.
 *
 * This is nearly a RESTful API. @see http://en.wikipedia.org/wiki/REST
 *
 * Instead of :
 *   http://[server]/[apipath]/[command]?[arg1]=[value1],[arg2]=[value2]
 *
 * It will call :
 *  [apipath] [command] --[arg1]=[value1] --[arg2]=[value2] --backend
 *
 * [apipath] in this case will be the path to the drush.php file.
 * [command] is the command you would call, for instance 'status'.
 *
 * GET parameters will be passed as options to the script.
 * POST parameters will be passed to the script as a JSON encoded associative array over STDIN.
 *
 * Because of this standard interface, Drush commands can also be executed on
 * external servers through SSH pipes, simply by prepending, 'ssh username@server.com'
 * in front of the command.
 *
 * If the key-based ssh authentication has been set up between the servers, this will just
 * work, otherwise the user will be asked to enter a password.
 */

/**
 * Identify the JSON encoded output from a command.
 */
define('DRUSH_BACKEND_OUTPUT_DELIMITER', 'DRUSH_BACKEND_OUTPUT_START>>>%s<<<DRUSH_BACKEND_OUTPUT_END');

function drush_backend_set_result($value) {
  if (drush_get_context('DRUSH_BACKEND')) {
    drush_set_context('BACKEND_RESULT', $value);
  }
}

function drush_backend_get_result() {
  return drush_get_context('BACKEND_RESULT');
}

function drush_backend_output() {
  $data = array();

  $data['output'] = ob_get_contents();
  ob_end_clean();

  $result_object = drush_backend_get_result();
  if (isset($result_object)) {
    $data['object'] = $result_object;
  }

  $error = drush_get_error();
  $data['error_status'] = ($error) ? $error : DRUSH_SUCCESS;

  $data['log'] = drush_get_log(); // Append logging information
  // The error log is a more specific version of the log, and may be used by calling
  // scripts to check for specific errors that have occurred.
  $data['error_log'] = drush_get_error_log();

  // Return the options that were set at the end of the process.
  $data['context']  = drush_get_merged_options();
  if (!drush_get_context('DRUSH_QUIET')) {
    printf(DRUSH_BACKEND_OUTPUT_DELIMITER, json_encode($data));
  }
}

/**
 * Parse output returned from a Drush command.
 *
 * @param string
 *    The output of a drush command
 * @param integrate
 *    Integrate the errors and log messages from the command into the current process.
 *
 * @return
 *   An associative array containing the data from the external command, or the string parameter if it
 *   could not be parsed successfully.
 */
function drush_backend_parse_output($string, $integrate = TRUE) {
  $regex = sprintf(DRUSH_BACKEND_OUTPUT_DELIMITER, '(.*)');

  preg_match("/$regex/s", $string, $match);

  if ($match[1]) {
    // we have our JSON encoded string
    $output = $match[1];
    // remove the match we just made and any non printing characters
    $string = trim(str_replace(sprintf(DRUSH_BACKEND_OUTPUT_DELIMITER, $match[1]), '', $string));
  }

  if ($output) {
    $data = json_decode($output, TRUE);
    if (is_array($data)) {
      if ($integrate) {
        _drush_backend_integrate($data);
      }
      return $data;
    }
  }
  return $string;
}

/**
 * Integrate log messages and error statuses into the current process.
 *
 * Output produced by the called script will be printed, errors will be set
 * and log messages will be logged locally.
 *
 * @param data
 *    The associative array returned from the external command.
 */
function _drush_backend_integrate($data) {
  if (is_array($data['log'])) {
    foreach($data['log'] as $log) {
      if (!is_null($log['error'])) {
        drush_set_error($log['error'], $log['message']);
      }
      else {
        drush_log($log['message'], $log['type'], $log['error']);
      }
    }
  }
  // Output will either be printed, or buffered to the drush_backend_output command.
  if (drush_cmp_error('DRUSH_APPLICATION_ERROR') && !empty($data['output'])) {
    drush_set_error("DRUSH_APPLICATION_ERROR", dt("Output from failed command :\n !output", array('!output' => $data['output'])));
  }
  else {
    print ($data['output']);
  }

}

/**
 * Call an external command using proc_open.
 *
 * @param cmd
 *    The command to execute. This command already needs to be properly escaped.
 * @param data
 *    An associative array that will be JSON encoded and passed to the script being called.
 *    Objects are not allowed, as they do not json_decode gracefully.
 *
 * @return
 *   False if the command could not be executed, or did not return any output.
 *   If it executed successfully, it returns an associative array containing the command
 *   called, the output of the command, and the error code of the command.
 */
function _drush_proc_open($cmd, $data = NULL, $context = NULL) {
  $descriptorspec = array(
     0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
     1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
     2 => array("pipe", "w")   // stderr is a pipe the child will write to
  );

  $process = proc_open($cmd, $descriptorspec, $pipes, null, null, array('context' => $context));
  if (is_resource($process)) {
    if ($data) {
      fwrite($pipes[0], json_encode($data)); // pass the data array in a JSON encoded string
    }

    $info = stream_get_meta_data($pipes[1]);
    stream_set_blocking($pipes[1], TRUE);
    stream_set_timeout($pipes[1], 1);
    $string = '';
    while (!feof($pipes[1]) && !$info['timed_out']) {
      $string .= fgets($pipes[1], 4096);
      $info = stream_get_meta_data($pipes[1]);
      flush();
    };

    $info = stream_get_meta_data($pipes[2]);
    stream_set_blocking($pipes[2], TRUE);
    stream_set_timeout($pipes[2], 1);
    while (!feof($pipes[2]) && !$info['timed_out']) {
      $string .= fgets($pipes[2], 4096);
      $info = stream_get_meta_data($pipes[2]);
      flush();
    };

    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $code = proc_close($process);
    return array('cmd' => $cmd, 'output' => $string, 'code' => $code);
  }
  return false;
}

/**
 * Invoke a drush backend command.
 *
 * @param command
 *    A defined drush command such as 'cron', 'status' or any of the available ones such as 'drush pm'.
 * @param data
 *    Optional. An array containing options to pass to the call. Common options would be 'uri' if you want to call a command
 *    on a different site, or 'root', if you want to call a command using a different Drupal installation.
 *    Array items with a numeric key are treated as optional arguments to the command.
 * @param method
 *    Optional. Defaults to 'GET'.
 *    If this parameter is set to 'POST', the $data array will be passed to the script being called as a JSON encoded string over
 *    the STDIN pipe of that process. This is preferable if you have to pass sensitive data such as passwords and the like.
 *    For any other value, the $data array will be collapsed down into a set of command line options to the script.
 * @param integrate
 *    Optional. Defaults to TRUE.
 *    If TRUE, any error statuses or log messages will be integrated into the current process. This might not be what you want,
 *    if you are writing a command that operates on multiple sites.
 * @param drush_path
 *    Optional. Defaults to the current drush.php file on the local machine, and
 *    to simply 'drush' (the drush script in the current PATH) on remote servers.
 *    You may also specify a different drush.php script explicitly.  You will need
 *    to set this when calling drush on a remote server if 'drush' is not in the
 *    PATH on that machine.
 * @param hostname
 *    Optional. A remote host to execute the drush command on.
 * @param username
 *    Optional. Defaults to the current user. If you specify this, you can choose which module to send.
 *
 * @deprecated Command name includes arguments, and these are not quote-escaped in any way.
 * @see drush_backend_invoke_args and @see drush_invoke_process for better options.
 *
 * @return
 *   If the command could not be completed successfully, FALSE.
 *   If the command was completed, this will return an associative array containing the data from drush_backend_output().
 */
function drush_backend_invoke($command, $data = array(), $method = 'GET', $integrate = TRUE, $drush_path = NULL, $hostname = NULL, $username = NULL) {
  $args = explode(" ", $command);
  $command = array_shift($args);
  return drush_backend_invoke_args($command, $args, $data, $method, $integrate, $drush_path, $hostname, $username);
}

/**
 * A variant of drush_backend_invoke() which specifies command and arguments separately.
 */
function drush_backend_invoke_args($command, $args, $data = array(), $method = 'GET', $integrate = TRUE, $drush_path = NULL, $hostname = NULL, $username = NULL, $ssh_options = NULL) {
  $cmd = _drush_backend_generate_command($command, $args, $data, $method, $drush_path, $hostname, $username, $ssh_options);
  return _drush_backend_invoke($cmd, $data, $integrate);
}

/**
 * Create a new pipe with proc_open, and attempt to parse the output.
 *
 * We use proc_open instead of exec or others because proc_open is best
 * for doing bi-directional pipes, and we need to pass data over STDIN
 * to the remote script.
 *
 * Exec also seems to exhibit some strangeness in keeping the returned
 * data intact, in that it modifies the newline characters.
 *
 * @param cmd
 *   The complete command line call to use.
 * @param data
 *   An associative array to pass to the remote script.
 * @param integrate
 *   Integrate data from remote script with local process.
 *
 * @return
 *   If the command could not be completed successfully, FALSE.
 *   If the command was completed, this will return an associative array containing the data from drush_backend_output().
 */
function _drush_backend_invoke($cmd, $data = null, $integrate = TRUE) {
  drush_log(dt('Running: !cmd', array('!cmd' => $cmd)), 'command');
  $proc = _drush_proc_open($cmd, $data);

  if (($proc['code'] == DRUSH_APPLICATION_ERROR) && $integrate) {
    drush_set_error('DRUSH_APPLICATION_ERROR', dt("The external command could not be executed due to an application error."));
  }

  if ($proc['output']) {
    $values = drush_backend_parse_output($proc['output'], $integrate);
    if (is_array($values)) {
      return $values;
    }
    else {
      return drush_set_error('DRUSH_FRAMEWORK_ERROR', dt("The command could not be executed successfully (returned: !return, code: %code)", array("!return" => $proc['output'], "%code" =>  $proc['code'])));
    }
  }
  return FALSE;
}

/**
 * Generate a command to execute.
 *
 * @param command
 *    A defined drush command such as 'cron', 'status' or any of the available ones such as 'drush pm'.
 * @param args
 *    An array of arguments for the command.
 * @param data
 *    Optional. An array containing options to pass to the remote script.
 *    Array items with a numeric key are treated as optional arguments to the command.
 *    This parameter is a reference, as any options that have been represented as either an option, or an argument will be removed.
 *    This allows you to pass the left over options as a JSON encoded string, without duplicating data.
 * @param method
 *    Optional. Defaults to 'GET'.
 *    If this parameter is set to 'POST', the $data array will be passed to the script being called as a JSON encoded string over
 *    the STDIN pipe of that process. This is preferable if you have to pass sensitive data such as passwords and the like.
 *    For any other value, the $data array will be collapsed down into a set of command line options to the script.
 * @param integrate
 *    Optional. Defaults to TRUE.
 *    If TRUE, any error statuses or log messages will be integrated into the current process. This might not be what you want,
 *    if you are writing a command that operates on multiple sites.
 * @param drush_path
 *    Optional. Defaults to the current drush.php file on the local machine, and
 *    to simply 'drush' (the drush script in the current PATH) on remote servers.
 *    You may also specify a different drush.php script explicitly.  You will need
 *    to set this when calling drush on a remote server if 'drush' is not in the
 *    PATH on that machine.
 * @param hostname
 *    Optional. A remote host to execute the drush command on.
 * @param username
 *    Optional. Defaults to the current user. If you specify this, you can choose which module to send.
 *
 * @return
 *   A text string representing a fully escaped command.
 */
function _drush_backend_generate_command($command, $args, &$data, $method = 'GET', $drush_path = null, $hostname = null, $username = null, $ssh_options = NULL) {
  if (drush_is_local_host($hostname)) {
    $hostname = null;
  }
  
  $drush_path = !is_null($drush_path) ? $drush_path : (is_null($hostname) ? DRUSH_COMMAND : 'drush'); // Call own drush.php file on local machines, or 'drush' on remote machines.
  $data['root'] = array_key_exists('root', $data) ? $data['root'] : drush_get_context('DRUSH_DRUPAL_ROOT');
  $data['uri'] = array_key_exists('uri', $data) ? $data['uri'] : drush_get_context('DRUSH_URI');

  $option_str = _drush_backend_argument_string($data, $method);
  foreach ($data as $key => $arg) {
    if (is_numeric($key)) {
      $args[] = $arg;
      unset($data[$key]);
    }
  }
  foreach ($args as $arg) {
    $command .= ' ' . escapeshellarg($arg);
  }
  // @TODO: Implement proper multi platform / multi server support.
  $cmd = escapeshellcmd($drush_path) . " " . $option_str . " " . $command . " --backend";

  if (!is_null($hostname)) {
    $username = (!is_null($username)) ? $username : get_current_user();
    $ssh_options = (!is_null($ssh_options)) ? $ssh_options : drush_get_option('ssh-options', "-o PasswordAuthentication=no");
    $cmd = "ssh " . $ssh_options . " " . escapeshellarg($username) . "@" . escapeshellarg($hostname) . " " . escapeshellarg($cmd);
  }

  return $cmd;
}

/**
 * A small utility function to call a drush command in the background.
 *
 * Takes the same parameters as drush_backend_invoke, but forks a new
 * process by calling the command using system() and adding a '&' at the
 * end of the command.
 *
 * Use this if you don't care what the return value of the command may be.
 */
function drush_backend_fork($command, $data, $drush_path = null, $hostname = null, $username = null) {
  $data['quiet'] = TRUE;
  $args = explode(" ", $command);
  $command = array_shift($args);
  $cmd = "(" . _drush_backend_generate_command($command, $args, $data, 'GET', $drush_path, $hostname, $username) . ' &) > /dev/null';
  drush_log(dt("Forking : !cmd", array('!cmd' => $cmd)));
  system($cmd);
}

/**
 * Map the options to a string containing all the possible arguments and options.
 *
 * @param data
 *    Optional. An array containing options to pass to the remote script.
 *    Array items with a numeric key are treated as optional arguments to the command.
 *    This parameter is a reference, as any options that have been represented as either an option, or an argument will be removed.
 *    This allows you to pass the left over options as a JSON encoded string, without duplicating data.
 * @param method
 *    Optional. Defaults to 'GET'.
 *    If this parameter is set to 'POST', the $data array will be passed to the script being called as a JSON encoded string over
 *    the STDIN pipe of that process. This is preferable if you have to pass sensitive data such as passwords and the like.
 *    For any other value, the $data array will be collapsed down into a set of command line options to the script.
 * @return
 *    A properly formatted and escaped set of arguments and options to append to the drush.php shell command.
 */
function _drush_backend_argument_string(&$data, $method = 'GET') {
  // Named keys are options, numerically indexed keys are optional arguments.
  $args = array();
  $options = array();

  foreach ($data as $key => $value) {
    if (!is_array($value) && !is_object($value) && !is_null($value) && ($value != '')) {
      if (is_numeric($key)) {
        $args[$key] = $value;
      }
      else {
        $options[$key] = $value;
      }
    }
  }
  if (array_key_exists('backend', $data)) {
    unset($data['backend']);
  }

  $special = array('root', 'uri'); // These should be in the command line.
  $option_str = '';
  foreach ($options as $key => $value) {
    if (($method != 'POST') || (($method == 'POST') && in_array($key, $special))) {
      $option_str .= _drush_escape_option($key, $value);
      unset($data[$key]); // Remove items in the data array.
    }
  }

  return $option_str;
}

/**
 * Return a properly formatted and escaped command line option
 *
 * @param key
 *   The name of the option.
 * @param value
 *   The value of the option.
 *
 * @return
 *   If the value is set to TRUE, this function will return " --key"
 *   In other cases it will return " --key='value'"
 */
function _drush_escape_option($key, $value = TRUE) {
  if ($value !== TRUE) {
    $option_str = " --$key=" . escapeshellarg($value);
  }
  else {
    $option_str = " --$key";
  }
  return $option_str;
}

/**
 * Read options fron STDIN during POST requests.
 *
 * This function will read any text from the STDIN pipe,
 * and attempts to generate an associative array if valid
 * JSON was received.
 *
 * @return
 *   An associative array of options, if successfull. Otherwise FALSE.
 */
function _drush_backend_get_stdin() {
  $fp = fopen('php://stdin', 'r');
  stream_set_blocking($fp, FALSE);
  $string = stream_get_contents($fp);
  fclose($fp);
  if (trim($string)) {
    return json_decode($string, TRUE);
  }
  return FALSE;
}
