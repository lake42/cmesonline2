<?php

/**
 * Display node comment status
 */
class views_handler_field_node_comment extends views_handler_field {
  function render($values) {
    switch ($values->{$this->field_alias}) {
      case COMMENT_NODE_DISABLED:
      default:
        return t('Disabled');
      case COMMENT_NODE_READ_ONLY:
        return t('Read only');
      case COMMENT_NODE_READ_WRITE:
        return t('Read/Write');
    }
  }
}
