<?php

namespace Drupal\cfrapi\Legend;

interface LegendInterface {

  /**
   * @return mixed[]
   */
  function getSelectOptions();

  /**
   * @param string $id
   *
   * @return string|null
   */
  function idGetLabel($id);

  /**
   * @param string $id
   *
   * @return bool
   */
  function idIsKnown($id);

}
