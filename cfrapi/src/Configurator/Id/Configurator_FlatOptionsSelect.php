<?php

namespace Drupal\cfrapi\Configurator\Id;

class Configurator_FlatOptionsSelect extends Configurator_SelectBase {

  /**
   * @var string[]
   */
  private $options;

  /**
   * @param string[] $options
   * @param string|null $defaultId
   *
   * @return self
   */
  public static function createRequired(array $options, $defaultId = NULL) {
    return new self($options, TRUE, $defaultId);
  }

  /**
   * @param string[] $options
   * @param string|null $defaultId
   *
   * @return self
   */
  public static function createOptional(array $options, $defaultId = NULL) {
    return new self($options, FALSE, $defaultId);
  }

  /**
   * @param string[] $options
   * @param bool $required
   * @param string|null $defaultId
   */
  public function __construct(array $options, $required = TRUE, $defaultId = NULL) {
    parent::__construct($required, $defaultId);
    $this->options = $options;
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {
    return $this->options;
  }

  /**
   * @param string $id
   *
   * @return string
   */
  protected function idGetLabel($id) {
    return isset($this->options[$id])
      ? $this->options[$id]
      : $id;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  protected function idIsKnown($id) {
    return isset($this->options[$id]);
  }
}
