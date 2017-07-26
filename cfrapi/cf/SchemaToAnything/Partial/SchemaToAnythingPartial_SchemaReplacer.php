<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;

class SchemaToAnythingPartial_SchemaReplacer implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   */
  public function __construct(SchemaReplacerInterface $replacer) {
    $this->replacer = $replacer;
  }

  /**
   * @return int
   */
  public function getSpecifity() {
    return 0;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Exception
   */
  public function schema(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {
    static $recursionLevel = 0;
    ++$recursionLevel;

    if (NULL === $replacement = $this->replacer->schemaGetReplacement($schema)) {
      --$recursionLevel;
      return NULL;
    }

    if ($replacement === $schema) {
      kdpm($replacement, 'REPLACEMENT');
      throw new \Exception("Replacer did not replace.");
    }

    if ($recursionLevel > 5) {
      dpm(spl_object_hash($replacement), 'REPLACEMENT OBJECT HASH at ' . $recursionLevel);
    }

    if ($recursionLevel > 10) {
      kdpm($schema, spl_object_hash($schema));
      kdpm($replacement, spl_object_hash($replacement));
      kdpm($this->replacer, 'REPLACER');
      throw new \Exception("Recursion.");
    }

    if (false && get_class($replacement) === get_class($schema)) {
      kdpm($schema, spl_object_hash($schema));
      kdpm($replacement, spl_object_hash($replacement));
      kdpm($this->replacer, 'REPLACER');
      throw new \Exception("Replacer did not replace.");
    }

    $anything = $helper->schema($replacement, $interface);

    --$recursionLevel;
    return $anything;
  }

  /**
   * @param string $resultInterface
   *
   * @return bool
   */
  public function providesResultType($resultInterface) {
    return TRUE;
  }

  /**
   * @param string $schemaClass
   *
   * @return bool
   */
  public function acceptsSchemaClass($schemaClass) {
    return TRUE;
  }
}
