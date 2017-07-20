<?php

namespace Donquixote\Cf\Schema\Options;

class CfSchema_Options_Fixed implements CfSchema_OptionsInterface {

  /**
   * @var string[][]
   */
  private $groupedOptions;

  /**
   * @var string[]
   */
  private $options;

  /**
   * @param string[][] $groupedOptions
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function __construct(array $groupedOptions) {

    $this->groupedOptions = $groupedOptions;

    $options = [];
    foreach ($groupedOptions as $groupLabel => $groupOptions) {
      $options += $groupOptions;
    }

    $this->options = $options;
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return isset($this->options[$id]);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions() {
    return $this->groupedOptions;
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {

    return isset($this->options[$id])
      ? $this->options[$id]
      : NULL;
  }
}
