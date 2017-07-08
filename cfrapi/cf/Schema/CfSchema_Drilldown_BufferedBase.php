<?php

namespace Donquixote\Cf\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;

abstract class CfSchema_Drilldown_BufferedBase implements CfSchema_DrilldownInterface {

  /**
   * @var string[]
   */
  private $labels = [];

  /**
   * @var null|array
   *   Format: $[$groupLabel][$key] = $label,
   *   mixed with $[$key] = $label
   *   or NULL if not initialized yet.
   */
  private $groupedOptions;

  /**
   * @var bool[]
   */
  private $idsKnown = [];

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $schemas = [];

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions() {
    return (NULL !== $this->groupedOptions)
      ? $this->groupedOptions
      : $this->groupedOptions = $this->buildGroupedOptions();
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  abstract protected function buildGroupedOptions();

  /**
   * @param string|int $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    return array_key_exists($id, $this->labels)
      ? $this->labels[$id]
      : $this->labels[$id] = $this->idBuildLabel($id);
  }

  /**
   * @param string|int $id
   *
   * @return string|null
   */
  abstract protected function idBuildLabel($id);

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return isset($this->idsKnown[$id])
      ? $this->idsKnown[$id]
      : $this->idsKnown[$id] = $this->idDetermineIfKnown($id);
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  abstract protected function idDetermineIfKnown($id);

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id) {
    return array_key_exists($id, $this->schemas)
      ? $this->schemas[$id]
      : $this->schemas[$id] = $this->idBuildCfrSchema($id);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  abstract protected function idBuildCfrSchema($id);
}
