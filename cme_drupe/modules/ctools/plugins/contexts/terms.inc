<?php
// $Id: terms.inc,v 1.4.2.3 2010/07/15 19:37:05 merlinofchaos Exp $

/**
 * @file
 *
 * Plugin to provide a terms context
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t("Taxonomy terms"),
  'description' => t('Multiple taxonomy terms, as a group.'),
  'context' => 'ctools_context_create_terms',
  'settings form' => 'ctools_context_terms_settings_form',
  'settings form validate' => 'ctools_context_terms_settings_form_validate',
  'keyword' => 'terms',
  'no ui' => TRUE,
  'context name' => 'terms',
  'convert list' => array(
    'tid' => t('Term ID of first term'),
    'tids' => t('Term ID of all term, separated by + or ,'),
    'name' => t('Term name of first term'),
    'names' => t('Term name of all terms, separated by + or ,'),
    'vid' => t('Vocabulary ID of first term'),
  ),
  'convert' => 'ctools_context_terms_convert',
);

/**
 * It's important to remember that $conf is optional here, because contexts
 * are not always created from the UI.
 */
function ctools_context_create_terms($empty, $data = NULL, $conf = FALSE) {
  // The input is expected to be an object as created by ctools_break_phrase
  // which contains a group of terms.

  $context = new ctools_context(array('terms', 'term'));
  $context->plugin = 'terms';

  if ($empty) {
    return $context;
  }

  if (!empty($data) && is_object($data)) {
    $context->operator = $data->operator;
    $context->tids     = $data->value;
    if (!isset($data->term)) {
      // load the first term:
      reset($context->tids);
      $data->term = taxonomy_get_term(current($context->tids));
    }
    $context->data     = $data->term;
    $context->title    = $data->term->name;
    $context->argument = implode($context->operator == 'or' ? '+' : ',', array_unique($context->tids));
    return $context;
  }
}

/**
 * Convert a context into a string.
 */
function ctools_context_terms_convert($context, $type) {
  switch ($type) {
    case 'tid':
      return $context->data->tid;
    case 'tids':
      return $context->argument;
    case 'name':
      return $context->data->name;
    case 'names':
      // We only run this query if this item was requested:
      if (!isset($context->names)) {
        if (empty($context->tids)) {
          $context->names = '';
        }
        else {
          $names = array();
          $result = db_query("SELECT tid, name FROM {term_data} WHERE tid IN (" . db_placeholders($context->tids) . ")", $context->tids);
          while ($term = db_fetch_object($result)) {
            $names[$term->tid] = $term->name;
          }
          $context->names = implode($context->operator == 'or' ? ' + ' : ', ', $names);
        }
      }
      return $context->names;
    case 'vid':
      return $context->data->vid;
  }
}
