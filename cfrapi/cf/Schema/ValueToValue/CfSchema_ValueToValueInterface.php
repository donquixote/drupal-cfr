<?php

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\SchemaBase\CfSchema_TransformableInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_ValueToValueInterface extends CfSchema_TransformableInterface, CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getDecorated();

  /**
   * @param mixed $value
   *
   * @return mixed
   */
  public function valueGetValue($value);

  /**
   * @param string $php
   *
   * @return string
   */
  public function phpGetPhp($php);

}
