<?php

namespace Donquixote\Cf\ATA\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\ATA\ATAInterface;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Util\ReflectionUtil;

class ATAPartial_Callback extends ATAPartialBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Cf\ATA\Partial\ATAPartialBase|null
   */
  public static function create(
    CallbackReflectionInterface $callback,
    ParamToValueInterface $paramToValue
  ) {

    $params = $callback->getReflectionParameters();

    if (0
      || !isset($params[0])
      || NULL === ($t0 = $params[0]->getClass())
    ) {
      return NULL;
    }
    unset($params[0]);

    $sourceType = $t0->getName();
    $specifity = count($t0->getInterfaceNames());

    if (1
      && isset($params[1])
      && NULL !== ($t1 = $params[1]->getClass())
      && is_a(ATAInterface::class, $t1->getName(), TRUE)
    ) {
      $hasStaParam = TRUE;
      unset($params[1]);
    }
    else {
      $hasStaParam = FALSE;
    }

    if ([] !== $params) {
      if (NULL === $boundArgs = ReflectionUtil::paramsGetValues($params, $paramToValue)) {
        return NULL;
      }

      $callback = new CallbackReflection_BoundParameters($callback, $boundArgs);
    }

    if ($hasStaParam) {
      $sta = new self(
        $callback,
        $sourceType);
    }
    else {
      $sta = new ATAPartial_CallbackNoHelper(
        $callback,
        $sourceType);
    }

    $sta = $sta->withSpecifity($specifity);

    return $sta;
  }

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $sourceType
   * @param string|null $resultType
   */
  public function __construct(CallbackReflectionInterface $callback, $sourceType = NULL, $resultType = NULL) {
    $this->callback = $callback;
    parent::__construct($sourceType, $resultType);
  }

  /**
   * @param mixed $source
   * @param string $interface
   * @param \Donquixote\Cf\ATA\ATAInterface $helper
   *
   * @return null|object An instance of $interface, or NULL.
   * An instance of $interface, or NULL.
   */
  public function doCast(
    $source,
    $interface,
    ATAInterface $helper
  ) {
    return $this->callback->invokeArgs([$source, $helper]);
  }
}
