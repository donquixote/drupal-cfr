<?php

namespace Drupal\cfrfamily\CfrLegendItem;

use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;

interface ParentLegendItemInterface extends CfrLegendItemInterface, CfrLegendProviderInterface {

  /**
   * @param mixed $conf
   *   Format: One of..
   *   - array($idKey => $id, $optionsKey => $optionsConf, ..)
   *   - array($idKey => $id, ..)
   *   - Any other format.
   *   The values of $idKey and $optionsKey depend on the implementation.
   *   Typically, it is $idKey === 'id', and $optionsKey === 'options'.
   *
   * @return array
   *   Format: One of..
   *   - array($id, $optionsConf)
   *   - array($id, null)
   *   - array(null, null)
   */
  function confGetIdOptions($conf);

}
