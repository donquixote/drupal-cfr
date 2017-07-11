<?php

namespace Drupal\cfrfamily\DrilldownSchema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;

abstract class CfSchema_Drilldown_DecoratorBase implements CfSchema_DrilldownInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   */
  public function __construct(CfSchema_DrilldownInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions() {
    return $this->decorated->getGroupedOptions();
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    return $this->decorated->idGetLabel($id);
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return $this->decorated->idIsKnown($id);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id) {
    return $this->decorated->idGetSchema($id);
  }

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function idValueGetValue($id, $value) {
    return $this->decorated->idValueGetValue($id, $value);
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, $php) {
    return $this->decorated->idPhpGetPhp($id, $php);
  }
}
