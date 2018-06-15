<?php

namespace Drupal\cfrapi\Configurator\Id;

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
    if (!\is_callable($valueCallback)) {
      throw new \InvalidArgumentException("Value callback must be callable.");
    }
    parent::__construct($options, $required, $defaultId);
    $this->valueCallback = $valueCallback;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    $id = parent::confGetValue($conf);
    return \call_user_func($this->valueCallback, $id);
  }
}
