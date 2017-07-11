<?php

namespace Donquixote\Cf\Schema\ValueProvider;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;

class CfSchema_ValueProvider_FixedValue implements CfSchema_ValueProviderInterface {

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
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp() {

    if (NULL !== $this->php) {
      return $this->php;
    }

    $helper = new CodegenHelper();
    return $helper->export($this->value);
  }
}
