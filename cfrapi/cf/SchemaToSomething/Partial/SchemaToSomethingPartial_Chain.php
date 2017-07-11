<?php

namespace Donquixote\Cf\SchemaToSomething\Partial;

use Donquixote\Cf\SchemaToSomething\Helper\SchemaToSomethingHelperInterface;
use Donquixote\Cf\SchemaToSomething\SchemaToSomethingCommonBase;
use Donquixote\Cf\Schema\CfSchemaInterface;

class SchemaToSomethingPartial_Chain extends SchemaToSomethingCommonBase implements SchemaToSomethingPartialInterface {

  /**
   * @var \Donquixote\Cf\SchemaToSomething\Partial\SchemaToSomethingPartialInterface[]
   */
  private $mappers;

  /**
   * @var string
   */
  private $resultInterface;

  /**
   * @param \Donquixote\Cf\SchemaToSomething\Partial\SchemaToSomethingPartialInterface[] $mappers
   * @param string $resultInterface
   */
  public function __construct(array $mappers, $resultInterface) {
    parent::__construct($resultInterface);
    $this->mappers = $mappers;
    $this->resultInterface = $resultInterface;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToSomething\Helper\SchemaToSomethingHelperInterface $helper
   *
   * @return null|object
   * @internal param string $interface
   */
  public function schema(
    CfSchemaInterface $schema, SchemaToSomethingHelperInterface $helper
  ) {

    foreach ($this->mappers as $mapper) {
      if (NULL !== $candidate = $mapper->schema($schema, $helper)) {
        if ($candidate instanceof $this->resultInterface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }

}
