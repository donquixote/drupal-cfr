<?php

namespace Drupal\cfrfamily\IdToConfigurator;

class IdToConfigurator_Buffer implements IdToConfiguratorInterface {

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface[]|null[]
   */
  private $buffer = [];

  /**
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $decorated
   */
  public function __construct(IdToConfiguratorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string|int $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  public function idGetConfigurator($id) {
    return array_key_exists($id, $this->buffer)
      ? $this->buffer[$id]
      : $this->buffer[$id] = $this->decorated->idGetConfigurator($id);
  }
}
