<?php

namespace Donquixote\Cf\Schema\Group;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

trait CfSchema_Group_PassthruTrait {

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
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  final public function itemsPhpGetPhp(
    array $itemsPhp,
    /** @noinspection PhpUnusedParameterInspection */ CfrCodegenHelperInterface $helper
  ) {

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
