<?php

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\Schema\Transformable\CfSchema_TransformableInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ValueToValue\ValueToValueInterface;

interface CfSchema_ValueToValueInterface extends CfSchema_TransformableInterface, ValueToValueInterface {

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
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function phpGetPhp($php, CfrCodegenHelperInterface $helper);

}
