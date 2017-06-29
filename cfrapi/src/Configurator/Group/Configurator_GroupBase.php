<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;

/**
 * Allows to inherit group configurator functionality, without implementing
 * GroupConfiguratorInterface.
 */
abstract class Configurator_GroupBase extends Configurator_GroupGrandBase {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  private $configurators = [];

  /**
   * @var string[]
   */
  private $labels = [];

  /**
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return static
   */
  public static function createFromConfigurators(array $paramConfigurators, array $labels) {
    $groupConfigurator = new static();
    foreach ($paramConfigurators as $k => $paramConfigurator) {
      $paramLabel = isset($labels[$k]) ? $labels[$k] : $k;
      $groupConfigurator->keySetConfigurator($k, $paramConfigurator, $paramLabel);
    }
    return $groupConfigurator;
  }

  /**
   * @param string $key
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   * @param string $label
   *
   * @return $this
   */
  public function keySetConfigurator($key, ConfiguratorInterface $configurator, $label) {
    if ('#' === $key[0]) {
      throw new \InvalidArgumentException("Key '$key' must not begin with '#'.");
    }
    $this->configurators[$key] = $configurator;
    $this->labels[$key] = $label;
    return $this;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  protected function getConfigurators() {
    return $this->configurators;
  }

  /**
   * @return string[]
   */
  protected function getLabels() {
    return $this->labels;
  }
}
