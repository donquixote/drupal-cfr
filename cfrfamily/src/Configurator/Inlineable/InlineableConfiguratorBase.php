<?php

namespace Drupal\cfrfamily\Configurator\Inlineable;

use Drupal\cfrapi\BrokenValue\BrokenValue;
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
   */
  final function confGetValue($conf) {
    list($id, $conf) = $this->confGetIdOptions($conf);
    if (NULL === $id) {
      return new BrokenValue($this, get_defined_vars(), 'No id specified.');
    }
    return $this->idConfGetValue($id, $conf);
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
