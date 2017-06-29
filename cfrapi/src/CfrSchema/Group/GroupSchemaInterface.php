<?php

namespace Drupal\cfrapi\CfrSchema\Group;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface GroupSchemaInterface extends CfrSchemaInterface {

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[]
   *   Format: $[$groupItemKey] = $groupItemSchema
   */
  public function getItemSchemas();

  /**
   * @return string[]
   */
  public function getLabels();

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed
   */
  public function valuesGetValue(array $values);

  /**
   * @param string[] $itemsPhp
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp, CfrCodegenHelperInterface $helper);

}
