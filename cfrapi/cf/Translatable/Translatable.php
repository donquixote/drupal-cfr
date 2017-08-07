<?php

namespace Donquixote\Cf\Translatable;

class Translatable implements TranslatableInterface {

  /**
   * @var string
   */
  private $original;

  /**
   * @var mixed[]
   */
  private $replacements;

  /**
   * @param string $original
   * @param mixed[] $replacements
   */
  public function __construct($original, array $replacements) {
    $this->original = $original;
    $this->replacements = $replacements;
  }

  /**
   * @return string
   */
  public function getOriginalText() {
    return $this->original;
  }

  /**
   * @return mixed[]
   */
  public function getReplacements() {
    return $this->replacements;
  }
}
