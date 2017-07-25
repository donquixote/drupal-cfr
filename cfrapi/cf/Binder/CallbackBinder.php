<?php

namespace Donquixote\Cf\Binder;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;

class CallbackBinder implements CallbackBinderInterface {

  /**
   * @var \Donquixote\Cf\ParamToValue\ParamToValueInterface
   */
  private $paramToValue;

  /**
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue) {
    $this->paramToValue = $paramToValue;
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param true[] $freeParams
   *   Format: $[$paramIndex] = true
   *
   * @return mixed
   */
  public function bind(CallbackReflectionInterface $callback, array $freeParams = []) {

    $else = new \stdClass();

    $boundArgs = [];
    $boundArgsPhp = [];
    foreach ($callback->getReflectionParameters() as $i => $param) {
      if (isset($freeParams[$i])) {
        continue;
      }
      if ($else === $argValue = $this->paramToValue->paramGetValue($param, $else)) {
        return NULL;
      }
      $boundArgs[$i] = $argValue;
      // The PHP part is optional.
      if (NULL !== $argPhp = $this->paramToValue->paramGetPhp($param)) {
        $boundArgsPhp[$i] = $argPhp;
      }
    }

    if ([] === $boundArgs) {
      return $callback;
    }

    return new CallbackReflection_BoundParameters(
      $callback,
      $boundArgs,
      $boundArgsPhp);
  }

}
