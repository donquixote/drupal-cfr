<?php

namespace Drupal\cfrapi\Configurator\Group;

use Donquixote\CallbackReflection\ArgsPhpToPhp\ArgsPhpToPhpInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;

class Configurator_GroupWithValueCallback extends Configurator_GroupBase implements ConfToPhpInterface {

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
   * @return \Drupal\cfrapi\BrokenValue\BrokenValueInterface|mixed|\mixed[]
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
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CodegenHelperInterface $helper) {

    $php = parent::confGetPhp($conf, $helper);

    $callbackReflection = CallbackUtil::callableGetCallback($this->valueCallback);

    if ($callbackReflection instanceof ArgsPhpToPhpInterface) {
      return $callbackReflection->argsPhpGetPhp([$php]);
    }

    return 'call_user_func('
      . "\n" . $helper->export($this->valueCallback) . ','
      . "\n" . $php . ')';
  }

}
