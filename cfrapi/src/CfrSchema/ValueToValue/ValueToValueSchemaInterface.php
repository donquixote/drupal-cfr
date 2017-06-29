<?php

namespace Drupal\cfrapi\CfrSchema\ValueToValue;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\ValueToValue\ValueToValueInterface;

interface ValueToValueSchemaInterface extends CfrSchemaInterface, ValueToValueInterface {

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
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
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function phpGetPhp($php, CfrCodegenHelperInterface $helper);

}
