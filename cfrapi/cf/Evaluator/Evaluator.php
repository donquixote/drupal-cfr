<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Evaluator\Helper\EmptynessHelper;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelper;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelper;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Evaluator\Partial\PartialEvaluator_SmartChain;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;

class Evaluator implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface
   */
  private $evaluatorHelper;

  /**
   * @var \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface
   */
  private $phpHelper;

  /**
   * @return self
   */
  public static function create() {
    $partial = new PartialEvaluator_SmartChain([]);
    $emptynessHelper = new EmptynessHelper($partial);
    $evaluatorHelper = new EvaluatorHelper($partial, $emptynessHelper);
    $codegenHelper = new CfrCodegenHelper();
    $phpHelper = new PhpHelper($partial, $codegenHelper, $emptynessHelper);
    return new self($evaluatorHelper, $phpHelper);
  }

  /**
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $evaluatorHelper
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $phpHelper
   */
  public function __construct(EvaluatorHelperInterface $evaluatorHelper, PhpHelperInterface $phpHelper) {
    $this->evaluatorHelper = $evaluatorHelper;
    $this->phpHelper = $phpHelper;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf) {
    return $this->evaluatorHelper->schemaConfGetValue($schema, $conf);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf) {
    return $this->phpHelper->schemaConfGetPhp($schema, $conf);
  }
}
