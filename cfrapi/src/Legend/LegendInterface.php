<?php

namespace Drupal\cfrapi\Legend;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface LegendInterface extends CfrSchemaInterface {

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
