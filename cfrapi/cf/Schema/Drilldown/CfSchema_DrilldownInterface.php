<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;
use Donquixote\Cf\Schema\Options\AbstractOptionsSchemaInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

interface CfSchema_DrilldownInterface extends CfSchemaLocalInterface, AbstractOptionsSchemaInterface {

  // @todo Add ->getIdKey() and ->getOptionsKey()?

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id);

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function idValueGetValue($id, $value);

  /**
   * @param string|int $id
   * @param string $php
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, $php, CfrCodegenHelperInterface $helper);

}
