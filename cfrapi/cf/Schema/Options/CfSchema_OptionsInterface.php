<?php

namespace Donquixote\Cf\Schema\Options;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

/**
 * @todo Maybe "Options" should be renamed to "Legend"?
 */
interface CfSchema_OptionsInterface extends CfSchemaLocalInterface, AbstractOptionsSchemaInterface {

  /**
   * @param string|int $id
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function idGetValue($id);

  /**
   * @param string|int $id
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function idGetPhp($id, CfrCodegenHelperInterface $helper);

}
