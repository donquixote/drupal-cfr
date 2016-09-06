<?php

namespace Drupal\cfrfamily\EnumMap;

use Drupal\cfrapi\EnumMap\EnumMapInterface;
use Drupal\cfrfamily\IdMap\IdMapInterface;
use Drupal\cfrfamily\IdToLabel\IdToLabelInterface;

class EnumMap_IdToLabel implements EnumMapInterface {

  /**
   * @var \Drupal\cfrfamily\IdMap\IdMapInterface
   */
  private $idMap;

  /**
   * @var \Drupal\cfrfamily\IdToLabel\IdToLabelInterface
   */
  private $idToLabel;

  /**
   * @param \Drupal\cfrfamily\IdMap\IdMapInterface $idMap
   * @param \Drupal\cfrfamily\IdToLabel\IdToLabelInterface $idToLabel
   */
  public function __construct(IdMapInterface $idMap, IdToLabelInterface $idToLabel) {
    $this->idMap = $idMap;
    $this->idToLabel = $idToLabel;
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return $this->idMap->idIsKnown($id);
  }

  /**
   * @return string[]
   */
  public function getSelectOptions() {

    $options = [];
    foreach ($this->idMap->getIds() as $id) {
      $options[$id] = $this->idToLabel->idGetLabel($id, $id);
    }

    return $options;
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    return $this->idToLabel->idGetLabel($id);
  }
}
