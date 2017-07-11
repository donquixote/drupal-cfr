<?php

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\Cf\Schema\CfSchemaInterface;

class CfSchema_ValueToValue_Callback extends CfSchema_ValueToValueBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   *
   * @return self
   */
  public static function createFromClassStaticMethod($class, $methodName, CfSchemaInterface $decorated) {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return new self($decorated, $callback);
  }

  /**
   * @param string $class
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   *
   * @return self
   */
  public static function createFromClass($class, CfSchemaInterface $decorated) {
    $callback = CallbackReflection_ClassConstruction::create($class);
    return new self($decorated, $callback);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  public function __construct(CfSchemaInterface $decorated, CallbackReflectionInterface $callback) {
    parent::__construct($decorated);
    $this->callback = $callback;
  }

  /**
   * @param mixed $value
   *
   * @return mixed
   */
  public function valueGetValue($value) {
    return $this->callback->invokeArgs([$value]);
  }


  /**
   * @param string $php
   *
   * @return string
   */
  public function phpGetPhp($php) {
    $helper = new CodegenHelper();
    return $this->callback->argsPhpGetPhp([$php], $helper);
  }
}
