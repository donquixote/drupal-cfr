<?php

namespace Drupal\cfrfamily\IdToLabel;

interface IdToLabelInterface {

  /**
   * @param string $id
   * @param string|null $else
   *
   * @return string|null
   */
  public function idGetLabel($id, $else = NULL);

}
