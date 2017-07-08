<?php

namespace Donquixote\Cf\Schema\Sequence;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

abstract class CfSchema_Sequence_PassthruBase implements CfSchema_SequenceInterface {

  /**
   * @param mixed[] $values
   *
   * @return mixed[]
   */
  final public function valuesGetValue(array $values) {
    return $values;
  }

  /**
   * @param array $itemsPhp
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return mixed
   */
  final public function itemsPhpGetPhp(array $itemsPhp, CfrCodegenHelperInterface $helper) {

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
