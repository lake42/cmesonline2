<?php

/**
 * @file
 *   Include file to provide Acquia Drupal version number.
 *
 *   Used to tell Acquia Drupal and non-Acquia Drupal installations from each
 *   other. Version data here also used to inform users of updates and to detect
 *   file changes when running Acquia Drupal.
 */
 
/**
 * Is this an Acquia Drupal install?
 */
define('IS_ACQUIA_DRUPAL', TRUE);

/**
 * Acquia Drupal version information (only used if IS_ACQUIA_DRUPAL).
 */
define('ACQUIA_DRUPAL_VERSION' , '1.2.33 (Drupal 6.20 core)');
define('ACQUIA_DRUPAL_SERIES'  , '1.0');
define('ACQUIA_DRUPAL_BRANCH'  , '1.x-6.x');
define('ACQUIA_DRUPAL_REVISION', '5349');
