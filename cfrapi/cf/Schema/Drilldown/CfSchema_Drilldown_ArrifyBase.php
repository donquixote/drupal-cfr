<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

abstract class CfSchema_Drilldown_ArrifyBase implements CfSchema_DrilldownInterface {

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function idValueGetValue($id, $value) {

    return [
      $this->getIdKey() => $id,
      $this->getOptionsKey() => $value,
    ];
  }

  /**
   * @param string|int $id
   * @param string $php
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return mixed
   */
  final public function idPhpGetPhp($id, $php, CfrCodegenHelperInterface $helper) {

    $idKeyPhp = var_export($this->getIdKey(), TRUE);
    $optionsKeyPhp = var_export($this->getOptionsKey(), TRUE);
    $idPhp = var_export($id, TRUE);

    return <<<EOT
[
  $idKeyPhp => $idPhp,
  $optionsKeyPhp => $php,
];
EOT;
  }

  /**
   * @return string
   */
  abstract public function getIdKey();

  /**
   * @return string
   */
  abstract public function getOptionsKey();
}
