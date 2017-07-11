<?php

namespace Donquixote\Cf\Schema\ValueProvider;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;

class CfSchema_ValueProvider_Callback implements CfSchema_ValueProviderInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   *
   * @return \Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_Callback
   */
  public static function createFromClass($class) {
    $callback = CallbackReflection_ClassConstruction::create($class);
    return new self($callback);
  }

  /**
   * @param string $class
   * @param string $methodName
   *
   * @return \Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_Callback
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
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function getValue() {
    return $this->callback->invokeArgs([]);
  }

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp() {
    $helper = new CodegenHelper();
    return $this->callback->argsPhpGetPhp([], $helper);
  }
}
