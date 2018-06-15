<?php

namespace Drupal\cfrapi\Legend;

class Legend_FixedOptions implements LegendInterface {

  /**
   * @var string[]|string[][]|mixed[]
   */
  private $deepOptions;

  /**
   * @var string[]
   */
  private $flatOptions = [];

  /**
   * @param array $options
   */
  public function __construct(array $options) {
    $this->deepOptions = $options;

    foreach ($options as $keyOrGroupLabel => $groupOrLabel) {
      if (!\is_array($groupOrLabel)) {
        $this->flatOptions[$keyOrGroupLabel] = $groupOrLabel;
      }
      else {
        $this->flatOptions += $groupOrLabel;
      }
    }
  }

  /**
   * @return mixed[]
   */
  public function getSelectOptions() {
    return $this->deepOptions;
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    return $this->flatOptions[$id] ?? null;
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return isset($this->flatOptions[$id]);
  }
}
