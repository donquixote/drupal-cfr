<?php

namespace Donquixote\Cf\Schema\Group;

use Donquixote\Cf\Schema\Transformable\CfSchema_TransformableInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

interface CfSchema_GroupInterface extends CfSchema_TransformableInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
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
