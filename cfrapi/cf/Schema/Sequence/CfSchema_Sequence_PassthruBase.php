<?php

namespace Donquixote\Cf\Schema\Sequence;

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
   *
   * @return mixed
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
