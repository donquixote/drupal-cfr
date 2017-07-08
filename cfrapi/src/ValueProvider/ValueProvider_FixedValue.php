<?php

namespace Drupal\cfrapi\ValueProvider;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class ValueProvider_FixedValue implements ValueProviderInterface {

  /**
   * @var mixed
   */
  private $value;

  /**
   * @var null|string
   */
  private $php;

  /**
   * @param mixed $value
   * @param string|null $php
   */
  public function __construct($value, $php = NULL) {
    $this->value = $value;
    $this->php = $php;
  }

  /**
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(CfrCodegenHelperInterface $helper) {
    if (NULL !== $this->php) {
      return $this->php;
    }
    return $helper->export($this->value);
  }
}
