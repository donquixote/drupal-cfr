<?php

namespace Drupal\cfrfamily\ConfToValue;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;
use Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface;
use Drupal\cfrapi\Util\ConfUtil;

class ConfToValue_IdConf implements ConfToValueInterface {

  /**
   * @var \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface
   */
  private $idConfToValue;

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
  private $required = TRUE;

  /**
   * @var mixed
   */
  private $defaultValue;

  /**
   * @param \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface $idConfToValue
   */
  public function __construct(IdConfToValueInterface $idConfToValue) {
    $this->idConfToValue = $idConfToValue;
  }

  /**
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\ConfToValue\ConfToValueInterface
   */
  public function cloneAsOptional($defaultValue = NULL) {
    $clone = clone $this;
    $clone->required = FALSE;
    $clone->defaultValue = $defaultValue;
    return $clone;
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

    return $this->idConfToValue->idConfGetValue($id, $optionsConf);
  }
}
