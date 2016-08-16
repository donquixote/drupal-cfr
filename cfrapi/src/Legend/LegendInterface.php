<?php

namespace Drupal\cfrapi\Legend;

interface LegendInterface {

  /**
   * @return mixed[]
   */
  function getSelectOptions();

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  function idGetLabel($id);

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  function idIsKnown($id);

}
