<?php
/**
 * @file
 * Contains the base row style plugin.
 */

/**
 * The basic 'fields' row plugin
 *
 * This displays fields one after another, giving options for inline
 * or not.
 *
 * @ingroup views_row_plugins
 */
class views_plugin_row_fields extends views_plugin_row {
  function option_definition() {
    $options = parent::option_definition();

    $options['inline'] = array('default' => array());
    $options['separator'] = array('default' => '');
    $options['hide_empty'] = array('default' => FALSE);
    return $options;
  }

  /**
   * Provide a form for setting options.
   */
  function options_form(&$form, &$form_state) {
    $options = $this->display->handler->get_field_labels();

    if (empty($this->options['inline'])) {
      $this->options['inline'] = array();
    }

    $form['inline'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Inline fields'),
      '#options' => $options,
      '#default_value' => $this->options['inline'],
      '#description' => t('Inline fields will be displayed next to each other rather than one after another.'),
    );

    $form['separator'] = array(
      '#title' => t('Separator'),
      '#type' => 'textfield',
      '#size' => 10,
      '#default_value' => isset($this->options['separator']) ? $this->options['separator'] : '',
      '#description' => t('The separator may be placed between inline fields to keep them from squishing up next to each other. You can use HTML in this field.'),
    );

    $form['hide_empty'] = array(
      '#type' => 'checkbox',
      '#title' => t('Hide empty fields'),
      '#default_value' => $this->options['hide_empty'],
      '#description' => t('Do not display fields, labels or markup for fields that are empty.'),
    );
  }

  /**
   * Perform any necessary changes to the form values prior to storage.
   * There is no need for this function to actually store the data.
   */
  function options_submit($form, &$form_state) {
    $form_state['values']['row_options']['inline'] = array_filter($form_state['values']['row_options']['inline']);
  }
}

