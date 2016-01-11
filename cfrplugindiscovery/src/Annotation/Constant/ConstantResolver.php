<?php

namespace Drupal\cfrplugindiscovery\Annotation\Constant;

use Drupal\cfrplugindiscovery\Annotation\Unresolved\IncompleteConstant;
use Drupal\cfrplugindiscovery\Annotation\Unresolved\UnresolvedConstant;
use vektah\parser_combinator\language\php\annotation\ConstLookup;

class ConstantResolver implements ConstantResolverInterface {

  /**
   * @var mixed[]
   */
  private $map = array();

  /**
   * Creates a constant resolver that accepts the common native PHP constants,
   * uppercase and lowercase, and not more.
   *
   * @return \Drupal\cfrplugindiscovery\Annotation\Constant\ConstantResolver
   */
  static function create() {
    return new self(
      array(
        'null' => NULL,
        'NULL' => NULL,
        'false' => FALSE,
        'FALSE' => FALSE,
        'true' => TRUE,
        'TRUE' => TRUE,
      ));
  }

  /**
   * @param mixed[] $map
   */
  function __construct(array $map) {
    $this->map = $map;
  }

  /**
   * Resolves a constant found in an annotation.
   *
   * @param ConstLookup $arg
   *
   * @return mixed
   *   Value of the constant.
   *
   * @throws \Exception
   *   If the constant is not defined.
   */
  function resolveConstant(ConstLookup $arg) {

    if (!isset($arg->static)) {
      return new IncompleteConstant($arg);
    }

    $name = isset($arg->class)
      ? $arg->class . '::' . $arg->static
      : $arg->static;

    // Only accept common native PHP constants, not any random constant defined
    // in code with define().
    # return constant($name);

    if (!array_key_exists($name, $this->map)) {
      return new UnresolvedConstant($name);
    }

    return $this->map[$name];
  }

} 
