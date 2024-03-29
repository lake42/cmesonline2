<?php
// $Id: bzr.inc,v 1.9 2010/12/07 16:35:09 jonhattan Exp $

/**
 * @file Drush pm Bazaar extension
 */

class drush_pm_version_control_bzr implements drush_pm_version_control {

  /**
   * Implementation of pre_update().
   */
  public function pre_update(&$project, $items_to_test = array()) {
    // If items to test is empty, test everything; otherwise, pass just
    // the list of files to test to Bazaar status.
    $status_files = implode(' ', array_keys($items_to_test));

    // Check the project directory looks clean
    if (drush_shell_exec('cd %s && bzr status --short %s', $project['full_project_path'], $status_files)) {
      $output = preg_grep('/^[\sRCP][\sNDKM][\s\*]/', drush_shell_exec_output());
      if (!empty($output)) {
        return drush_set_error('DRUSH_PM_BZR_LOCAL_CHANGES', dt("The Bazaar working copy at !path appears to have uncommitted changes (see below). Please commit or revert these changes before continuing:\n!output", array('!path' => $project['full_project_path'], '!output' => implode("\n", $output))));
      }
    }
    else {
      return drush_set_error('DRUSH_PM_BZR_NOT_FOUND', dt("Drush was unable to get the bzr status on !path. Check that you have Bazaar \ninstalled and that this directory is a Bazaar working copy.\nThe specific errors are below:\n!errors", array('!path' => $project['full_project_path'], '!errors' => implode("\n", drush_shell_exec_output()))));
    }
    return TRUE;
  }

  /**
   * Implementation of rollback().
   */
  public function rollback($project) {
    if (drush_shell_exec('bzr revert %s', $project['full_project_path'])) {
      $output = drush_shell_exec_output();
      if (!empty($output)) {
        return drush_set_error('DRUSH_PM_BZR_LOCAL_CHANGES', dt("The Bazaar working copy at !path appears to have uncommitted changes (see below). Please commit or revert these changes before continuing:\n!output", array('!path' => $project['full_project_path'], '!output' => implode("\n", $output))));
      }
    }
    else {
      return drush_set_error('DRUSH_PM_BZR_NOT_FOUND', dt("Drush was unable to get the Bazaar status on !path. Check that you have Bazaar \ninstalled and that this directory is a Bazaar working copy.\nThe specific errors are below:\n!errors", array('!path' => $project['full_project_path'], '!errors' => implode("\n", drush_shell_exec_output()))));
    }
  }

  /**
   * Implementation of post_update().
   */
  public function post_update($project) {
    if ($this->sync($project)) {
      // Only attempt commit on a sucessful sync
      $this->commit($project);
    }
  }

  /**
   * Implementation of post_download().
   */
  public function post_download($project) {
    if ($this->sync($project)) {
      // Only attempt commit on a sucessful sync
      $this->commit($project);
    }
  }

  /**
   * Automatically add any unversioned files to Bazaar and remove any files
   * that have been deleted on the file system
   */
  private function sync($project) {
    if (drush_get_option('bzrsync')) {
      $errors = '';
      $root = array();
      if (drush_shell_exec('bzr status --short %s', $project['full_project_path'])) {
        $output = drush_shell_exec_output();
        // All paths returned by bzr status are relative to the repository root.
        if (drush_shell_exec('bzr root %s', $project['full_project_path'])) {
          $root = drush_shell_exec_output();
        }
        foreach ($output as $line) {
          if (preg_match('/^\?\s+(.*)/', $line, $matches)) {
            $path = $root[0] .'/'. $matches[1];
            // Bazaar add is by default recursive so only run on "full_project_path".
            if (($path == $project['full_project_path'] .'/') && !drush_shell_exec('bzr add %s', $path)) {
              $errors .= implode("\n", drush_shell_exec_output());
            }
          }
          else if (preg_match('/^\s+D\s+(.*)/', $line, $matches)) {
            $path = $root[0] .'/'. $matches[1];
            if (!drush_shell_exec('bzr remove %s', $path)) {
              $errors .= implode("\n", drush_shell_exec_output());
            }
          }
        }
        if (!empty($errors)) {
          return drush_set_error('DRUSH_PM_BZR_SYNC_PROBLEMS', dt("Problems were encountered adding or removing files to/from Bazaar.\nThe specific errors are below:\n!errors", array('!errors' => $errors)));
        }
      }
      else {
        return drush_set_error('DRUSH_PM_BZR_NOT_FOUND', dt("Drush was unable to get the bzr status. Check that you have Bazaar \ninstalled and that the site is a Bazaar working copy.\nThe specific errors are below:\n!errors", array('!errors' => implode("\n", drush_shell_exec_output()))));
      }
      return TRUE;
    }
  }

  /**
   * Automatically commit changes to the repository
   */
  private function commit($project) {
    if (drush_get_option('bzrcommit')) {
      $message = drush_get_option('bzrmessage');
      if (empty($message)) {
        $message = dt("Drush automatic commit.\nProject: @name @type\nCommand: @arguments", array('@name' => $project['name'], '@type' => $project['project_type'], '@arguments' => implode(' ', $_SERVER['argv'])));
      }
      if (drush_shell_exec('bzr commit --message=%s %s', $message, $project['full_project_path'])) {
        drush_log(dt('Project committed to Bazaar successfully'), 'ok');
      }
      else {
        drush_set_error('DRUSH_PM_BZR_COMMIT_PROBLEMS', dt("Problems were encountered committing your changes to Bazaar.\nThe specific errors are below:\n!errors", array('!errors' => implode("\n", drush_shell_exec_output()))));
      }
    }
    else {
      drush_print(dt("You should consider committing the new code to your Bazaar repository.\nIf this version becomes undesireable, use Bazaar to roll back."));
    }
  }

  public static function reserved_files() {
    return array('.bzr', '.bzrignore', '.bzrtags');
  }
}
