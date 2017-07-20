<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;
use Donquixote\Cf\SchemaBase\Options\CfSchemaBase_AbstractOptionsInterface;

interface CfSchema_DrilldownInterface extends CfSchemaLocalInterface, CfSchemaBase_AbstractOptionsInterface {

  // @todo Add ->getIdKey() and ->getOptionsKey()?

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id);

}
