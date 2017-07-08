<?php

namespace Donquixote\Cf\Schema\Group;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class GroupSchema extends CfSchema_GroupBase {

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed
   */
  public function valuesGetValue(array $values) {
    return $values;
  }

  /**
   * @param string[] $itemsPhp
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp, CfrCodegenHelperInterface $helper) {

    if ([] === $itemsPhp) {
      return '[]';
    }

    $php = '';
    foreach ($itemsPhp as $key => $phpStatement) {
      $php .= "\n  " . var_export($key, TRUE) . ' => ' . $phpStatement . ',';
    }

    return "[$php\n]";
  }
}
