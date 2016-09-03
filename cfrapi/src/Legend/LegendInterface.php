<?php

namespace Drupal\cfrapi\Legend;

interface LegendInterface {

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
