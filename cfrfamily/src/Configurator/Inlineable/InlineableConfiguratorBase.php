<?php

namespace Drupal\cfrfamily\Configurator\Inlineable;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\Util\ConfUtil;
use Drupal\cfrfamily\IdConfToPhp\IdConfToPhpUtil;

abstract class InlineableConfiguratorBase implements InlineableConfiguratorInterface, ConfToPhpInterface {

  /**
   * @var string
   */
  private $idKey = 'id';

  /**
   * @var string
   */
  private $optionsKey = 'options';

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  final public function confGetValue($conf) {
    list($id, $conf) = $this->confGetIdOptions($conf);
    if (NULL === $id) {
      return new BrokenValue($this, get_defined_vars(), 'No id specified.');
    }
    return $this->idConfGetValue($id, $conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  final function confGetPhp($conf) {
    list($id, $conf) = $this->confGetIdOptions($conf);
    if (NULL === $id) {
      throw new InvalidConfigurationException("Required id missing.");
    }
    return IdConfToPhpUtil::objIdConfGetPhp($this, $id, $conf);
  }

  /**
   * @param mixed $conf
   *
   * @return array
   */
  protected function confGetIdOptions($conf) {
    return ConfUtil::confGetIdOptions($conf, $this->idKey, $this->optionsKey);
  }

}
