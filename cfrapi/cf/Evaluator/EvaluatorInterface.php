<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface EvaluatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   *   FALSE, if $conf is either invalid or non-empty.
   */
  # public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf);

}
