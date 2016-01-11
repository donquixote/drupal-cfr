<?php
namespace Drupal\cfrplugindiscovery\Annotation\Constant;

use vektah\parser_combinator\language\php\annotation\ConstLookup;

interface ConstantResolverInterface {

  /**
   * Resolves a constant found in an annotation.
   *
   * @param ConstLookup $arg
   *   Object identifying a global constant or a class constant.
   *
   * @return mixed
   *   Value of the constant.
   *
   * @throws \Exception
   *   If the constant is not defined.
   */
  function resolveConstant(ConstLookup $arg);
}
