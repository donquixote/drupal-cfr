<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\LocalPackageUtil;

class SchemaToAnythingPartial_SmartChain implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private $partials;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[][][]
   *   Format: $[$schemaType][$targetType] = $partials
   */
  private $partialsGrouped = [];

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[][]
   *   Format: $[$targetType] = $partials
   */
  private $partialsByTargetType = [];

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[][]
   *   Format: $[$schemaType] = $partials
   */
  private $partialsBySchemaType = [];

  /**
   * @return self
   */
  public static function create() {
    $partials = LocalPackageUtil::collectSTAPartials();
    return new self($partials);
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function schema(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {

    $partials = $this->schemaTypeAndTargetTypeGetPartials(
      get_class($schema),
      $interface);

    foreach ($partials as $partial) {
      if (NULL !== $candidate = $partial->schema($schema, $interface, $helper)) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }

  /**
   * @param string $schemaType
   * @param string $targetType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function schemaTypeAndTargetTypeGetPartials($schemaType, $targetType) {

    return isset($this->partialsGrouped[$schemaType][$targetType])
      ? $this->partialsGrouped[$schemaType][$targetType]
      : $this->partialsGrouped[$schemaType][$targetType] = $this->schemaTypeAndTargetTypeCollectPartials($schemaType, $targetType);
  }

  /**
   * @param string $schemaType
   * @param string $targetType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function schemaTypeAndTargetTypeCollectPartials($schemaType, $targetType) {

    return array_intersect_key(
      $this->schemaTypeGetPartials($schemaType),
      $this->targetTypeGetPartials($targetType));
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function targetTypeGetPartials($interface) {

    return isset($this->partialsByTargetType[$interface])
      ? $this->partialsByTargetType[$interface]
      : $this->partialsByTargetType[$interface] = $this->targetTypeCollectPartials($interface);
  }

  /**
   * @param string $targetType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function targetTypeCollectPartials($targetType) {

    $partials = [];
    /** @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->providesResultType($targetType)) {
        // Preserve keys for array_intersect().
        $partials[$k] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @param string $interface
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function schemaTypeGetPartials($interface) {

    return isset($this->partialsBySchemaType[$interface])
      ? $this->partialsBySchemaType[$interface]
      : $this->partialsBySchemaType[$interface] = $this->schemaTypeCollectPartials($interface);
  }

  /**
   * @param string $schemaType
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private function schemaTypeCollectPartials($schemaType) {

    $partials = [];
    /** @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface $partial */
    foreach ($this->partials as $k => $partial) {
      if ($partial->acceptsSchemaClass($schemaType)) {
        // Preserve keys for array_intersect().
        $partials[$k] = $partial;
      }
    }

    return $partials;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function providesResultType($interface) {
    return [] !== $this->targetTypeGetPartials($interface);
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function acceptsSchemaClass($interface) {
    return [] !== $this->schemaTypeGetPartials($interface);
  }
}
