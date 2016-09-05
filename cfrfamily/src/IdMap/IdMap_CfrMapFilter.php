<?php

namespace Drupal\cfrfamily\IdMap;

use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class IdMap_CfrMapFilter implements IdMapInterface {

  /**
   * @var \Drupal\cfrfamily\IdMap\IdMapInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @param \Drupal\cfrfamily\IdMap\IdMapInterface $decorated
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   */
  public function __construct(IdMapInterface $decorated, IdToConfiguratorInterface $idToConfigurator) {
    $this->decorated = $decorated;
    $this->idToConfigurator = $idToConfigurator;
  }

  /**
   * @return string[]
   */
  public function getIds() {

    $ids = [];
    foreach ($this->decorated->getIds() as $id) {
      if (null !== $this->idToConfigurator->idGetConfigurator($id)) {
        $ids[] = $id;
      }
    }

    return $ids;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return $this->decorated->idIsKnown($id) && NULL !== $this->idToConfigurator->idGetConfigurator($id);
  }
}
