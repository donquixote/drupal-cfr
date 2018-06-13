<?php

namespace Drupal\cfrapi\Configurator\Sequence;

use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;

class Configurator_SequenceWithValueCallback extends Configurator_Sequence {

  /**
   * @var callable
   */
  private $valueCallback;

  /**
   * @param \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface $itemConfigurator
   * @param callable $valueCallback
   */
  public function __construct(OptionalConfiguratorInterface $itemConfigurator, $valueCallback) {
    if (!is_callable($valueCallback)) {
      throw new \InvalidArgumentException("Argument must be callable.");
    }
    $this->valueCallback = $valueCallback;
    parent::__construct($itemConfigurator);
  }

  /**
   * @param mixed[]|mixed $conf
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    if (!is_array($value)) {
      return $value;
    }
    return call_user_func($this->valueCallback, $value);
  }

}
