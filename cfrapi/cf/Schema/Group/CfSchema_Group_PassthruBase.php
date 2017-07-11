<?php

namespace Donquixote\Cf\Schema\Group;

abstract class CfSchema_Group_PassthruBase implements CfSchema_GroupInterface {

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed[]
   *   Format: $[$groupItemKey] = $groupItemValue
   */
  final public function valuesGetValue(array $values) {
    return $values;
  }

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  final public function itemsPhpGetPhp(array $itemsPhp) {

    if ([] === $itemsPhp) {
      return '[]';
    }

    $php = '';
    foreach ($itemsPhp as $k => $vPhp) {
      $kPhp = var_export($k, TRUE);
      $php .= "\n  $kPhp => $vPhp,";
    }

    return "[$php\n]";
  }
}
