<?php

namespace Donquixote\Cf\Evaluator\Helper;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface EvaluatorHelperInterface extends EvaluatorHelperBaseInterface {

  /**
   * @return mixed
   */
  public function unknownSchema();

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf);

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return mixed
   */
  public function incompatibleConfiguration($conf, $message);

  /**
   * @param string $message
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function invalidConfiguration($message);
}
