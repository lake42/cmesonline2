<?php

/**
 * Simple filter to handle greater than/less than filters
 */
class views_handler_filter_numeric extends views_handler_filter {
  var $no_single = TRUE;
  function option_definition() {
    $options = parent::option_definition();

    $options['value'] = array(
      'contains' => array(
        'min' => array('default' => ''),
        'max' => array('default' => ''),
        'value' => array('default' => ''),
      ),
    );

    return $options;
  }

  function operators() {
    $operators = array(
      '<' => array(
        'title' => t('Is less than'),
        'method' => 'op_simple',
        'short' => t('<'),
        'values' => 1,
      ),
      '<=' => array(
        'title' => t('Is less than or equal to'),
        'method' => 'op_simple',
        'short' => t('<='),
        'values' => 1,
      ),
      '=' => array(
        'title' => t('Is equal to'),
        'method' => 'op_simple',
        'short' => t('='),
        'values' => 1,
      ),
      '!=' => array(
        'title' => t('Is not equal to'),
        'method' => 'op_simple',
        'short' => t('!='),
        'values' => 1,
      ),
      '>=' => array(
        'title' => t('Is greater than or equal to'),
        'method' => 'op_simple',
        'short' => t('>='),
        'values' => 1,
      ),
      '>' => array(
        'title' => t('Is greater than'),
        'method' => 'op_simple',
        'short' => t('>'),
        'values' => 1,
      ),
      'between' => array(
        'title' => t('Is between'),
        'method' => 'op_between',
        'short' => t('between'),
        'values' => 2,
      ),
      'not between' => array(
        'title' => t('Is not between'),
        'method' => 'op_between',
        'short' => t('not between'),
        'values' => 2,
      ),
    );

    // if the definition allows for the empty operator, add it.
    if (!empty($this->definition['allow empty'])) {
      $operators += array(
        'empty' => array(
          'title' => t('Is empty (NULL)'),
          'method' => 'op_empty',
          'short' => t('empty'),
          'values' => 0,
        ),
        'not empty' => array(
          'title' => t('Is not empty (NOT NULL)'),
          'method' => 'op_empty',
          'short' => t('not empty'),
          'values' => 0,
        ),
      );
    }

    return $operators;
  }

  /**
   * Provide a list of all the numeric operators
   */
  function operator_options($which = 'title') {
    $options = array();
    foreach ($this->operators() as $id => $info) {
      $options[$id] = $info[$which];
    }

    return $options;
  }

  function operator_values($values = 1) {
    $options = array();
    foreach ($this->operators() as $id => $info) {
      if ($info['values'] == $values) {
        $options[] = $id;
      }
    }

    return $options;
  }
  /**
   * Provide a simple textfield for equality
   */
  function value_form(&$form, &$form_state) {
    $form['value']['#tree'] = TRUE;

    // We have to make some choices when creating this as an exposed
    // filter form. For example, if the operator is locked and thus
    // not rendered, we can't render dependencies; instead we only
    // render the form items we need.
    $which = 'all';
    if (!empty($form['operator'])) {
      $source = ($form['operator']['#type'] == 'radios') ? 'radio:options[operator]' : 'edit-options-operator';
    }

    if (!empty($form_state['exposed'])) {
      $identifier = $this->options['expose']['identifier'];

      if (empty($this->options['expose']['use_operator']) || empty($this->options['expose']['operator'])) {
        // exposed and locked.
        $which = in_array($this->operator, $this->operator_values(2)) ? 'minmax' : 'value';
      }
      else {
        $source = 'edit-' . form_clean_id($this->options['expose']['operator']);
      }
    }

    if ($which == 'all') {
      $form['value']['value'] = array(
        '#type' => 'textfield',
        '#title' => empty($form_state['exposed']) ? t('Value') : '',
        '#size' => 30,
        '#default_value' => $this->value['value'],
        '#process' => array('views_process_dependency'),
        '#dependency' => array($source => $this->operator_values(1)),
      );
      if (!empty($form_state['exposed']) && !isset($form_state['input'][$identifier]['value'])) {
        $form_state['input'][$identifier]['value'] = $this->value['value'];
      }
    }
    else if ($which == 'value') {
      // When exposed we drop the value-value and just do value if
      // the operator is locked.
      $form['value'] = array(
        '#type' => 'textfield',
        '#title' => empty($form_state['exposed']) ? t('Value') : '',
        '#size' => 30,
        '#default_value' => $this->value['value'],
      );
      if (!empty($form_state['exposed']) && !isset($form_state['input'][$identifier])) {
        $form_state['input'][$identifier] = $this->value['value'];
      }
    }

    if ($which == 'all' || $which == 'minmax') {
      $form['value']['min'] = array(
        '#type' => 'textfield',
        '#title' => empty($form_state['exposed']) ? t('Min') : '',
        '#size' => 30,
        '#default_value' => $this->value['min'],
      );
      $form['value']['max'] = array(
        '#type' => 'textfield',
        '#title' => empty($form_state['exposed']) ? t('And max') : t('And'),
        '#size' => 30,
        '#default_value' => $this->value['max'],
      );
      if ($which == 'all') {
        $dependency = array(
          '#process' => array('views_process_dependency'),
          '#dependency' => array($source => $this->operator_values(2)),
        );
        $form['value']['min'] += $dependency;
        $form['value']['max'] += $dependency;
      }
      if (!empty($form_state['exposed']) && !isset($form_state['input'][$identifier]['min'])) {
        $form_state['input'][$identifier]['min'] = $this->value['min'];
      }
      if (!empty($form_state['exposed']) && !isset($form_state['input'][$identifier]['max'])) {
        $form_state['input'][$identifier]['max'] = $this->value['max'];
      }

      if (!isset($form['value'])) {
        // Ensure there is something in the 'value'.
        $form['value'] = array(
          '#type' => 'value',
          '#value' => NULL
        );
      }
    }
  }

  function query() {
    $this->ensure_my_table();
    $field = "$this->table_alias.$this->real_field";

    $info = $this->operators();
    if (!empty($info[$this->operator]['method'])) {
      $this->{$info[$this->operator]['method']}($field);
    }
  }

  function op_between($field) {
    if ($this->operator == 'between') {
      $this->query->add_where($this->options['group'], "$field >= %d", $this->value['min']);
      $this->query->add_where($this->options['group'], "$field <= %d", $this->value['max']);
    }
    else {
      $this->query->add_where($this->options['group'], "$field <= %d OR $field >= %d", $this->value['min'], $this->value['max']);
    }
  }

  function op_simple($field) {
    $this->query->add_where($this->options['group'], "$field $this->operator %d", $this->value['value']);
  }

  function op_empty($field) {
    if ($this->operator == 'empty') {
      $operator = "IS NULL";
    }
    else {
      $operator = "IS NOT NULL";
    }

    $this->query->add_where($this->options['group'], "$field $operator");
  }

  function admin_summary() {
    if (!empty($this->options['exposed'])) {
      return t('exposed');
    }

    $options = $this->operator_options('short');
    $output = check_plain($options[$this->operator]);
    if (in_array($this->operator, $this->operator_values(2))) {
      $output .= ' ' . t('@min and @max', array('@min' => $this->value['min'], '@max' => $this->value['max']));
    }
    elseif (in_array($this->operator, $this->operator_values(1))) {
      $output .= ' ' . check_plain($this->value['value']);
    }
    return $output;
  }

  /**
   * Do some minor translation of the exposed input
   */
  function accept_exposed_input($input) {
    if (empty($this->options['exposed'])) {
      return TRUE;
    }

    // rewrite the input value so that it's in the correct format so that
    // the parent gets the right data.
    if (!empty($this->options['expose']['identifier'])) {
      $value = &$input[$this->options['expose']['identifier']];
      if (!is_array($value)) {
        $value = array(
          'value' => $value,
        );
      }
    }

    $rc = parent::accept_exposed_input($input);

    if (!empty($this->options['expose']['optional'])) {
      // We have to do some of our own optional checking.
      $info = $this->operators();
      if (!empty($info[$this->operator]['values'])) {
        switch ($info[$this->operator]['values']) {
          case 1:
            if ($value['value'] === '') {
              return FALSE;
            }
            break;
          case 2:
            if ($value['min'] === '' && $value['max'] === '') {
              return FALSE;
            }
            break;
        }
      }
    }

    return $rc;
  }
}
