<?php

namespace Drupal\cfrapi\BrokenValue;

abstract class BrokenValueBase implements BrokenValueInterface {

  /**
   * @var null|object
   */
  private $object;

  /**
   * @var array
   */
  private $vars;

  /**
   * @var null|string
   */
  private $message;

  /**
   * @param object|null $object
   *   The $this reference where the broken value is created.
   * @param array $vars
   *   The get_defined_vars() from where the broken value is created.
   * @param string|null $message
   *   Message explaining what went wrong.
   */
  function __construct($object, array $vars, $message = NULL) {
    if (array_key_exists('this', $vars) && $vars['this'] === $object) {
      unset($vars['this']);
    }
    $this->object = $object;
    $this->vars = $vars;
    $this->message = $message;
  }

}
