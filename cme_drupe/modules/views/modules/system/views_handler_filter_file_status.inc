<?php
/**
 * Filter by file status
 */
class views_handler_filter_file_status extends views_handler_filter_in_operator {
  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_options = _views_file_status();
    }
  }
}

