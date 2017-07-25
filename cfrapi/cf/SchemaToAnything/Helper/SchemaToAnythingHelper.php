<?php

namespace Donquixote\Cf\SchemaToAnything\Helper;

use Donquixote\Cf\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_SmartChain;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;

class SchemaToAnythingHelper implements SchemaToAnythingHelperInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface
   */
  private $partial;

  /**
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue) {
    return new self(SchemaToAnythingPartial_SmartChain::create($paramToValue));
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $partials
   *
   * @return self
   */
  public static function createFromPartials(array $partials) {
    return new self(new SchemaToAnythingPartial_SmartChain($partials));
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface $partial
   */
  public function __construct(SchemaToAnythingPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function schema(CfSchemaInterface $schema, $interface) {
    return $this->partial->schema($schema, $interface, $this);
  }
}
