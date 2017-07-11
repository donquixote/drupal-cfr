<?php

namespace Donquixote\Cf\Schema\Drilldown;

trait CfSchema_Drilldown_PassthruTrait {

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
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
   *
   * @return mixed
   * @see \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface::idPhpGetPhp()
   */
  final public function idPhpGetPhp(
    /** @noinspection PhpUnusedParameterInspection */ $id,
    $php
  ) {
    return $php;
  }
}
