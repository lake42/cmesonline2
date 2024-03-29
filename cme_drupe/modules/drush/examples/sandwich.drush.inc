<?php
// $Id: sandwich.drush.inc,v 1.5 2010/12/05 07:27:32 greg1anderson Exp $

/**
 * @file
 *   Example drush command.
 *
 *   To run this *fun* command, execute `sudo drush --include=./examples mmas`
 *   from within your drush directory.
 *
 *   Shows how to make your own drush command.
 *
 *   You can copy this file to any of the following
 *     1. A .drush folder in your HOME folder.
 *     2. Anywhere in a folder tree below an active module on your site.
 *     3. /usr/share/drush/commands (configurable)
 *     4. In an arbitrary folder specified with the --include option.
 */

/**
 * Implementation of hook_drush_command().
 *
 * In this hook, you specify which commands your
 * drush module makes available, what it does and
 * description.
 *
 * Notice how this structure closely resembles how
 * you define menu hooks.
 *
 * See `drush topic docs-commands` for a list of recognized keys.
 *
 * @return
 *   An associative array describing your command(s).
 */
function sandwich_drush_command() {
  $items = array();

  // The 'make-me-a-sandwich' command
  $items['make-me-a-sandwich'] = array(
    'description' => "Makes a delicious sandwich.",
    'arguments' => array(
      'filling' => 'The type of the sandwich (turkey, cheese, etc.)',
    ),
    'options' => array(
      'spreads' => 'Comma delimited list of spreads (e.g. mayonnaise, mustard)',
    ),
    'examples' => array(
      'drush mmas turkey --spreads=ketchup,mustard' => 'Make a terrible-tasting sandwich that is lacking in pickles.',
    ),
    'aliases' => array('mmas'),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH, // No bootstrap at all.
  );
  
  // Commandfiles may also add topics.  These will appear in
  // the list of topics when `drush topic` is executed.
  // To view this topic, run `drush --include=/full/path/to/examples topic`
  $items['sandwich-exposition'] = array(
    'description' => dt('Ruminations on the true meaning and philosophy of sandwiches.'),
    'hidden' => TRUE,
    'topic' => TRUE,
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH,
    'callback' => 'drush_print_file',
    'callback arguments' => array(dirname(__FILE__) . '/sandwich-topic.txt'),
  );

  return $items;
}

/**
 * Implementation of hook_drush_help().
 *
 * This function is called whenever a drush user calls
 * 'drush help <name-of-your-command>'
 *
 * @param
 *   A string with the help section (prepend with 'drush:')
 *
 * @return
 *   A string with the help text for your command.
 */
function sandwich_drush_help($section) {
  switch ($section) {
    case 'drush:make-me-a-sandwich':
      return dt("This command will make you a delicious sandwich, just how you like it.");
  }
}

/**
 * Implementation of drush_hook_COMMAND_validate().
 */
function drush_sandwich_make_me_a_sandwich_validate() {
  $name = posix_getpwuid(posix_geteuid());
  if ($name['name'] !== 'root') {
    return drush_set_error('MAKE_IT_YOUSELF', dt('What? Make your own sandwich.'));
  }
}

/**
 * Example drush command callback. This is where the action takes place.
 *
 * The function name should be same as command name but with dashes turned to
 * underscores and 'drush_commandfile_' prepended, where 'commandfile' is
 * taken from the file 'commandfile.drush.inc', which in this case is 'sandwich'.
 * Note also that a simplification step is also done in instances where
 * the commandfile name is the same as the beginning of the command name,
 * "drush_example_example_foo" is simplified to just "drush_example_foo".
 * To also implement a hook that is called before your command, implement
 * "drush_hook_pre_example_foo".  For a list of all available hooks for a
 * given command, run drush in --debug mode.
 *
 * If for some reason you do not want your hook function to be named
 * after your command, you may define a 'callback' item in your command
 * object that specifies the exact name of the function that should be
 * called.  However, the specified callback function must still begin
 * with "drush_commandfile_" (e.g. 'callback' => "drush_example_foo_execute")
 * if you want that all hook functions are still called (e.g.
 * drush_example_pre_foo_execute, and so on).
 *
 * In this function, all of Drupal's API is (usually) available, including
 * any functions you have added in your own modules/themes.
 *
 * @see drush_invoke()
 * @see drush.api.php
 */
function drush_sandwich_make_me_a_sandwich($filling = 'ascii') {
  $str_spreads = '';
  if ($spreads = drush_get_option('spreads')) {
    $list = implode(' and ', explode(',', $spreads));
    $str_spreads = ' with just a dash of ' . $list;
  }
  $msg = dt('Okay. Enjoy this !filling sandwich!str_spreads.',
            array('!filling' => $filling, '!str_spreads' => $str_spreads)
         );
  drush_print("\n" . $msg . "\n");
  drush_print(file_get_contents(dirname(__FILE__) . '/sandwich.txt'));
}
