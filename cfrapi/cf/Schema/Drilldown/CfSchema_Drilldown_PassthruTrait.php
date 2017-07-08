<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

trait CfSchema_Drilldown_PassthruTrait {

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   * @see \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface::idValueGetValue()
   */
  public function idValueGetValue(
    /** @noinspection PhpUnusedParameterInspection */ $id,
    $value
  ) {
    return $value;
  }

  /**
   * @param string|int $id
   * @param string $php
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return mixed
   * @see \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface::idPhpGetPhp()
   */
  final public function idPhpGetPhp(
    /** @noinspection PhpUnusedParameterInspection */ $id,
    $php,
    /** @noinspection PhpUnusedParameterInspection */ CfrCodegenHelperInterface $helper
  ) {
    return $php;
  }
}
