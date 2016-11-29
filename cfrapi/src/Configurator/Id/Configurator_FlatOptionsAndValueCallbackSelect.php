<?php

namespace Drupal\cfrapi\Configurator\Id;

use Drupal\cfrapi\BrokenValue\BrokenValueInterface;

class Configurator_FlatOptionsAndValueCallbackSelect extends Configurator_FlatOptionsSelect {

  /**
   * @var callable
   */
  private $valueCallback;

  /**
   * @param callable $valueCallback
   * @param string[] $options
   * @param bool $required
   * @param string|null $defaultId
   */
  public function __construct($valueCallback, array $options, $required = TRUE, $defaultId = NULL) {
    if (!is_callable($valueCallback)) {
      throw new \InvalidArgumentException("Value callback must be callable.");
    }
    parent::__construct($options, $required, $defaultId);
    $this->valueCallback = $valueCallback;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {
    $id = parent::confGetValue($conf);
    if ($id instanceof BrokenValueInterface) {
      return $id;
    }
    return call_user_func($this->valueCallback, $id);
  }
}
