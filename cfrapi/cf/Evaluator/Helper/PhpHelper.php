<?php

namespace Donquixote\Cf\Evaluator\Helper;

use Donquixote\Cf\Helper\SchemaHelperBase;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Exception\ConfToValueException;
use Drupal\cfrapi\Util\CodegenFailureUtil;

class PhpHelper extends SchemaHelperBase implements PhpHelperInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface
   */
  private $partialEvaluator;

  /**
   * @var \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface
   */
  private $helper;

  /**
   * @var \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface
   */
  private $emptynessHelper;

  /**
   * @param \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface $partial
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $codegenHelper
   * @param \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface $emptynessHelper
   */
  public function __construct(
    PartialEvaluatorInterface $partial,
    CfrCodegenHelperInterface $codegenHelper,
    EmptynessHelperInterface $emptynessHelper
  ) {
    $this->partialEvaluator = $partial;
    $this->helper = $codegenHelper;
    $this->emptynessHelper = $emptynessHelper;
  }

  /**
   * @return string
   */
  public function unknownSchema() {
    // Return something that is not a valid PHP statement.
    return '?-UNKNOWN-SCHEMA-?';
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function incompatibleConfiguration($conf, $message) {
    return $this->helper->incompatibleConfiguration($conf, $message);
  }

  /**
   * @param string $message
   *
   * @return string
   */
  public function invalidConfiguration($message) {
    return $this->helper->incompatibleConfiguration(NULL, $message);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return bool
   */
  public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf) {
    return $this->emptynessHelper->schemaConfIsEmpty($schema, $conf);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf) {

    $php = $this->partialEvaluator->schemaConfGetPhp($schema, $conf, $this);

    if ($this->unknownSchema() === $php) {
      return CodegenFailureUtil::exception(
        ConfToValueException::class,
        "Unsupported schema.");
    }

    return $php;
  }

  /**
   * @return \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface
   */
  public function getCodegenHelper() {
    return $this->helper;
  }
}
