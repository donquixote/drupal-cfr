<?php

namespace Drupal\cfrapi\Legend;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface LegendInterface extends CfSchemaInterface {

  /**
   * @return mixed[]
   */
  public function getSelectOptions();

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id);

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id);

}
