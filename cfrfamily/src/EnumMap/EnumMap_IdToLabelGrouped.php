<?php

namespace Drupal\cfrfamily\EnumMap;

use Drupal\cfrapi\EnumMap\EnumMapInterface;
use Drupal\cfrfamily\IdMap\IdMapInterface;
use Drupal\cfrfamily\IdToLabel\IdToLabelInterface;

class EnumMap_IdToLabelGrouped implements EnumMapInterface {

  /**
   * @var \Drupal\cfrfamily\IdMap\IdMapInterface
   */
  private $idMap;

  /**
   * @var \Drupal\cfrfamily\IdToLabel\IdToLabelInterface
   */
  private $idToLabel;

  /**
   * @var \Drupal\cfrfamily\IdToLabel\IdToLabelInterface
   */
  private $idToGroupLabel;

  /**
   * @param \Drupal\cfrfamily\IdMap\IdMapInterface $idMap
   * @param \Drupal\cfrfamily\IdToLabel\IdToLabelInterface $idToLabel
   * @param \Drupal\cfrfamily\IdToLabel\IdToLabelInterface|NULL $idToGroupLabel
   *
   * @return \Drupal\cfrapi\EnumMap\EnumMapInterface
   */
  public static function create(IdMapInterface $idMap, IdToLabelInterface $idToLabel, IdToLabelInterface $idToGroupLabel = NULL) {
    return (NULL === $idToGroupLabel)
      ? new EnumMap_IdToLabel($idMap, $idToLabel)
      : new self($idMap, $idToLabel, $idToGroupLabel);
  }

  /**
   * @param \Drupal\cfrfamily\IdMap\IdMapInterface $idMap
   * @param \Drupal\cfrfamily\IdToLabel\IdToLabelInterface $idToLabel
   * @param \Drupal\cfrfamily\IdToLabel\IdToLabelInterface $idToGroupLabel
   */
  public function __construct(IdMapInterface $idMap, IdToLabelInterface $idToLabel, IdToLabelInterface $idToGroupLabel) {
    $this->idMap = $idMap;
    $this->idToLabel = $idToLabel;
    $this->idToGroupLabel = $idToGroupLabel;
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
      if (NULL === $groupLabel = $this->idToGroupLabel->idGetLabel($id)) {
        $options[$id] = $this->idToLabel->idGetLabel($id, $id);
      }
      else {
        $options[$groupLabel][$id] = $this->idToLabel->idGetLabel($id, $id);
      }
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
