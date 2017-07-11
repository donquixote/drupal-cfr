<?php

namespace Donquixote\Cf\Schema\Transformable;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

interface CfSchema_TransformableInterface extends CfSchemaLocalInterface {

  /**
   * Returns a version of this schema where internal schemas are replaced by
   * "better" ones, recursively.
   *
   * A schema is considered "better" if it is closer to actual implementation.
   *
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return static
   */
  public function withReplacements(SchemaReplacerInterface $replacer);

}
