<?php

namespace Donquixote\Cf\Evaluator\Helper;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface PhpHelperInterface extends EvaluatorHelperBaseInterface {

  /**
   * @return string
   */
  public function unknownSchema();

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function incompatibleConfiguration($conf, $message);

  /**
   * @param string $message
   *
   * @return string
   */
  public function invalidConfiguration($message);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf);

  /**
   * @return \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface
   *
   * @todo In the end we want to remove the CfrCodegenHelperInterface.
   */
  public function getCodegenHelper();
}
