<?php

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\Schema\Transformable\CfSchema_TransformableInterface;

interface CfSchema_ValueToValueInterface extends CfSchema_TransformableInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getDecorated();

  /**
   * @return string|null
   */
  public function getLabel();

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
