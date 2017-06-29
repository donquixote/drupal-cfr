<?php

namespace Drupal\cfrapi\CfrSchema\Optionless;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class OptionlessSchema_Callback implements OptionlessSchemaInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   *
   * @return \Drupal\cfrapi\CfrSchema\Optionless\OptionlessSchema_Callback
   */
  public static function createFromClass($class) {
    $callback = CallbackReflection_ClassConstruction::create($class);
    return new self($callback);
  }

  /**
   * @param string $class
   * @param string $methodName
   *
   * @return \Drupal\cfrapi\CfrSchema\Optionless\OptionlessSchema_Callback
   */
  public static function createFromClassStaticMethod($class, $methodName) {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return new self($callback);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  public function __construct(CallbackReflectionInterface $callback) {
    $this->callback = $callback;
  }

  /**
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function getValue() {
    return $this->callback->invokeArgs([]);
  }

  /**
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(CfrCodegenHelperInterface $helper) {
    return $this->callback->argsPhpGetPhp([], $helper);
  }
}
