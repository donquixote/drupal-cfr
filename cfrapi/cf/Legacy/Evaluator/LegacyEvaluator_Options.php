<?php

namespace Donquixote\Cf\Legacy\Evaluator;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Util\ConfUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Exception\InvalidConfigurationException;

/**
 * @todo Annotate the class.
 */
class LegacyEvaluator_Options implements LegacyEvaluatorInterface {

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
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {

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
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return $helper->incompatibleConfiguration($conf, 'Required id empty for options schema.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return $helper->incompatibleConfiguration($conf, "Unknown id '$id' for options schema.");
    }

    return $this->schema->idGetPhp($id, $helper);
  }
}
