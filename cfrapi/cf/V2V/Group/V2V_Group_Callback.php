<?php

namespace Donquixote\Cf\V2V\Group;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;

class V2V_Group_Callback implements V2V_GroupInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   */
  public function __construct(CallbackReflectionInterface $callbackReflection) {
    $this->callbackReflection = $callbackReflection;
  }

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values) {
    return $this->callbackReflection->invokeArgs($values);
  }

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp) {
    // @todo Does the helper need to be passed into this method?
    $helper = new CodegenHelper();
    return $this->callbackReflection->argsPhpGetPhp($itemsPhp, $helper);
  }
}
