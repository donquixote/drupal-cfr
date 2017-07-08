<?php

namespace Donquixote\Cf\Legacy\SchemaToSomething;

trait SchemaToSomethingTrait {

  /**
   * @var string
   */
  private $resultInterface;

  /**
   * @param string $resultInterface
   */
  protected function __construct($resultInterface) {
    $this->resultInterface = $resultInterface;
  }

  /**
   * @param string $expectedResultInterface
   *
   * @throws \Exception
   */
  public function requireResultType($expectedResultInterface) {

    if ($expectedResultInterface === $this->resultInterface) {
      return;
    }

    if (is_a($this->resultInterface, $expectedResultInterface, TRUE)) {
      return;
    }

    throw new \Exception("Return type mismatch.");
  }
}
