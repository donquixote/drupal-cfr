<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\Util\ReflectionUtil;

class SchemaToAnythingPartial_Callback extends SchemaToAnythingPartialBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialBase|null
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

    if (CfSchemaInterface::class === $schemaType = $t0->getName()) {
      $schemaType = NULL;
    }
    elseif (!is_a($schemaType, CfSchemaInterface::class, TRUE)) {
      return NULL;
    }

    if (1
      && isset($params[1])
      && NULL !== ($t1 = $params[1]->getClass())
      && is_a(SchemaToAnythingHelperInterface::class, $t1->getName(), TRUE)
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
      return new self(
        $callback,
        $schemaType);
    }
    else {
      return new SchemaToAnythingPartial_CallbackNoHelper(
        $callback,
        $schemaType);
    }
  }

  /**
   *
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string|null $schemaType
   * @param string|null $resultType
   */
  public function __construct(CallbackReflectionInterface $callback, $schemaType = NULL, $resultType = NULL) {
    $this->callback = $callback;
    parent::__construct($schemaType, $resultType);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function schemaDoGetObject(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {
    return $this->callback->invokeArgs([$schema, $helper]);
  }
}
