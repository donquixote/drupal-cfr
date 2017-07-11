<?php

namespace Donquixote\Cf\Schema\Group;

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
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp) {

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
