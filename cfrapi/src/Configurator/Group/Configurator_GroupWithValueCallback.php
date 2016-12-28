<?php

namespace Drupal\cfrapi\Configurator\Group;

use Donquixote\CallbackReflection\Util\CallbackUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class Configurator_GroupWithValueCallback extends Configurator_GroupBase {

  /**
   * @var callable
   */
  private $valueCallback;

  /**
   * @param callable $valueCallback
   */
  public function __construct($valueCallback) {
    if (!is_callable($valueCallback)) {
      throw new \InvalidArgumentException("Argument must be callable.");
    }
    $this->valueCallback = $valueCallback;
  }

  /**
   * @param mixed[]|mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    if (!is_array($value)) {
      return $value;
    }
    return call_user_func($this->valueCallback, $value);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {

    $php = parent::confGetPhp($conf, $helper);

    $callbackReflection = CallbackUtil::callableGetCallback($this->valueCallback);

    return $callbackReflection->argsPhpGetPhp([$php], $helper);
  }

}
