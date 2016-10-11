<?php

namespace Drupal\cfrfamily\Configurator\Inlineable;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\Util\ConfUtil;

abstract class InlineableConfiguratorBase implements InlineableConfiguratorInterface {

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
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  final public function confGetValue($conf) {
    list($id, $conf) = $this->confGetIdOptions($conf);

    if (NULL === $id) {
      throw new InvalidConfigurationException("Id is required.");
    }

    return $this->idConfGetValue($id, $conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  final function confGetPhp($conf, CfrCodegenHelperInterface $helper) {

    list($id, $optionsConf) = $this->confGetIdOptions($conf);

    if (NULL === $id) {
      return $helper->incompatibleConfiguration($conf, "Required id missing.");
    }

    return $this->idConfGetPhp($id, $optionsConf, $helper);
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
