<?php

namespace Donquixote\Cf\Context;

use Drupal\cfrapi\Context\CfrContextInterface;

class CfContext_FromCfrContext implements CfContextInterface {

  /**
   * @var \Drupal\cfrapi\Context\CfrContextInterface
   */
  private $cfrContext;

  /**
   * @param \Drupal\cfrapi\Context\CfrContextInterface $cfrContext
   */
  public function __construct(CfrContextInterface $cfrContext) {
    $this->cfrContext = $cfrContext;
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  public function paramValueExists(\ReflectionParameter $param) {
    return $this->cfrContext->paramValueExists($param);
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return mixed
   */
  public function paramGetValue(\ReflectionParameter $param) {
    return $this->cfrContext->paramGetValue($param);
  }

  /**
   * @param string $paramName
   *
   * @return bool
   */
  public function paramNameHasValue($paramName) {
    return $this->cfrContext->paramNameHasValue($paramName);
  }

  /**
   * @param string $paramName
   *
   * @return mixed
   */
  public function paramNameGetValue($paramName) {
    return $this->cfrContext->paramNameGetValue($paramName);
  }

  /**
   * @return string
   */
  public function getMachineName() {
    return $this->cfrContext->getMachineName();
  }
}
