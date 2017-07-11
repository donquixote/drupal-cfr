<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface;
use Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Util\ConfUtil;

/**
 * @Cf
 */
class Evaluator_Options implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $schema
   */
  public function __construct(CfSchema_OptionsInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface $helper
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return $helper->invalidConfiguration('Required id empty for options schema.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' for options schema.");
    }

    return $this->schema->idGetValue($id);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return $helper->incompatibleConfiguration($conf, 'Required id empty for options schema.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return $helper->incompatibleConfiguration($conf, "Unknown id '$id' for options schema.");
    }

    return $this->schema->idGetPhp($id);
  }
}
