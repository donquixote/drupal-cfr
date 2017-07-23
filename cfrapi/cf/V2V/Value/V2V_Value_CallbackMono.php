<?php

namespace Donquixote\Cf\V2V\Value;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;

class V2V_Value_CallbackMono implements V2V_ValueInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  public function __construct(CallbackReflectionInterface $callback) {
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