<?php

namespace Drupal\cfrfamily\ConfToValue;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;
use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;
use Drupal\cfrapi\Util\ConfUtil;

class ConfToValue_CfrMap implements ConfToValueInterface {

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @var \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|null
   */
  private $idValueToValue;

  /**
   * @var string
   */
  private $idKey = 'id';

  /**
   * @var string
   */
  private $optionsKey = 'options';

  /**
   * @var bool
   */
  private $required;

  /**
   * @var mixed
   */
  private $defaultValue;

  /**
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   * @param \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|NULL $idValueToValue
   *
   * @return \Drupal\cfrfamily\ConfToValue\ConfToValue_CfrMap
   */
  public static function createRequired(IdToConfiguratorInterface $idToConfigurator, IdValueToValueInterface $idValueToValue = NULL) {
    return new self($idToConfigurator, $idValueToValue, TRUE, NULL);
  }

  /**
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   * @param \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|NULL $idValueToValue
   * @param null $defaultValue
   *
   * @return \Drupal\cfrfamily\ConfToValue\ConfToValue_CfrMap
   */
  public static function createOptional(IdToConfiguratorInterface $idToConfigurator, IdValueToValueInterface $idValueToValue = NULL, $defaultValue = NULL) {
    return new self($idToConfigurator, $idValueToValue, FALSE, $defaultValue);
  }

  /**
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   * @param \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|NULL $idValueToValue
   * @param bool $required
   * @param mixed $defaultValue
   */
  public function __construct(IdToConfiguratorInterface $idToConfigurator, IdValueToValueInterface $idValueToValue = NULL, $required, $defaultValue) {
    $this->idToConfigurator = $idToConfigurator;
    $this->idValueToValue = $idValueToValue;
    $this->required = $required;
    $this->defaultValue = $defaultValue;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf, $this->idKey, $this->optionsKey);

    if (NULL === $id) {
      if ($this->required) {
        return new BrokenValue($this, get_defined_vars(), 'Required.');
      }
      else {
        return $this->defaultValue;
      }
    }

    $configurator = $this->idToConfigurator->idGetConfigurator($id);

    if (NULL === $configurator) {
      return new BrokenValue($this, get_defined_vars(), 'Unknown id.');
    }

    $value = $configurator->confGetValue($optionsConf);

    if (NULL !== $this->idValueToValue && !$value instanceof BrokenValueInterface) {
      $value = $this->idValueToValue->idValueGetValue($id, $value);
    }

    return $value;
  }
}
