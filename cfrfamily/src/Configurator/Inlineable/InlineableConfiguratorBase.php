<?php

namespace Drupal\cfrfamily\Configurator\Inlineable;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
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
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  final function confGetPhp($conf, CodegenHelperInterface $helper) {

    list($id, $optionsConf) = $this->confGetIdOptions($conf);

    if (NULL === $id) {
      return $helper->incompatibleConfiguration($optionsConf, "Required id missing.");
    }

    return IdConfToPhpUtil::objIdConfGetPhp($this, $id, $optionsConf, $helper);
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
