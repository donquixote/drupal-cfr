<?php

namespace Donquixote\Cf\Schema\Optional;

use Donquixote\Cf\Schema\Transformable\CfSchema_TransformableInterface;

interface CfSchema_OptionalInterface extends CfSchema_TransformableInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *   The non-optional version.
   */
  public function getDecorated();

  /**
   * @return string|null
   *
   * @todo Does this need a helper?
   */
  public function getEmptySummary();

  /**
   * @return mixed
   *   Typically NULL.
   *
   * @todo Does this need a helper?
   */
  public function getEmptyValue();

  /**
   * @return string
   *   Typically 'NULL'.
   *
   * @todo Does this need a helper?
   */
  public function getEmptyPhp();

}
