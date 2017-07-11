<?php

namespace Donquixote\Cf\Schema\Options;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;

/**
 * @todo Maybe "Options" should be renamed to "Choice"?
 */
interface CfSchema_OptionsInterface extends CfSchemaLocalInterface, AbstractOptionsSchemaInterface {

  /**
   * @param string|int $id
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idGetValue($id);

  /**
   * @param string|int $id
   *
   * @return string
   */
  public function idGetPhp($id);

}
