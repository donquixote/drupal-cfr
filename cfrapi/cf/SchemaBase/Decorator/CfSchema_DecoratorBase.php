<?php

namespace Donquixote\Cf\SchemaBase\Decorator;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_TransformableInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class CfSchema_DecoratorBase implements CfSchema_TransformableInterface {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   */
  public function __construct(CfSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

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
  public function withReplacements(SchemaReplacerInterface $replacer) {

    if (NULL === $replacement = $replacer->schemaGetReplacement(
        $this->decorated)
    ) {
      return $this;
    }

    $clone = clone $this;
    $clone->decorated = $replacement;
    return $clone;
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *   The non-optional version.
   */
  public function getDecorated() {
    return $this->decorated;
  }
}