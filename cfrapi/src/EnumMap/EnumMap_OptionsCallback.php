<?php

namespace Drupal\cfrapi\EnumMap;

class EnumMap_OptionsCallback implements EnumMapInterface {

  /**
   * @var callable
   */
  private $optionsCallback;

  /**
   * @var string[]|null
   */
  private $optionsBuffer;

  /**
   * @param callable $optionsCallback
   */
  function __construct($optionsCallback) {
    $this->optionsCallback = $optionsCallback;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  function idIsKnown($id) {
    if (NULL !== $this->optionsBuffer) {
      return array_key_exists($id, $this->optionsBuffer);
    }
    // Check if this is a "smart" callback..
    $result = call_user_func($this->optionsCallback, $id);
    if (is_array($result)) {
      $this->optionsBuffer = $result;
      return array_key_exists($id, $this->optionsBuffer);
    }
    else {
      return FALSE !== $result && NULL !== $result;
    }
  }

  /**
   * @return mixed[]
   */
  function getSelectOptions() {
    if (NULL === $this->optionsBuffer) {
      $this->optionsBuffer = call_user_func($this->optionsCallback);
      if (!is_array($this->optionsBuffer)) {
        // @todo Throw an exception or something?
        return $this->optionsBuffer = array();
      }
    }
    return $this->optionsBuffer;
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  function idGetLabel($id) {
    if (NULL !== $this->optionsBuffer) {
      return array_key_exists($id, $this->optionsBuffer);
    }
    // Check if this is a "smart" callback..
    $result = call_user_func($this->optionsCallback, $id);
    if (is_array($result)) {
      $this->optionsBuffer = $result;
      return array_key_exists($id, $this->optionsBuffer);
    }
    elseif (is_string($result)) {
      return $result;
    }
    else {
      return NULL;
    }
  }
}
