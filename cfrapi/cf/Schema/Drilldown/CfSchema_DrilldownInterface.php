<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;
use Donquixote\Cf\Schema\Options\AbstractOptionsSchemaInterface;

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
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idValueGetValue($id, $value);

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, $php);

}
