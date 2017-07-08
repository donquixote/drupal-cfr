<?php

namespace Donquixote\Cf\Legacy\XEvaluator;

use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Util\ConfUtil;
use Drupal\cfrapi\Exception\InvalidConfigurationException;

/**
 * @todo Annotate the class.
 */
class XEvaluator_Options implements XEvaluatorInterface {

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
   *   Configuration from a form, config file or storage.
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed Value to be used in the application.
   * Value to be used in the application.
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf, EvaluatorHelperInterface $helper) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      throw new InvalidConfigurationException('Required id empty for options schema.');
    }

    if (!$this->schema->idIsKnown($id)) {
      throw new InvalidConfigurationException("Unknown id '$id' for options schema.");
    }

    return $this->schema->idGetValue($id);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
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

    return $this->schema->idGetPhp($id, $helper->getCodegenHelper());
  }
}
