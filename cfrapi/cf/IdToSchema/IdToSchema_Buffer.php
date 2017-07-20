<?php

namespace Donquixote\Cf\IdToSchema;

class IdToSchema_Buffer implements IdToSchemaInterface {

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $buffer = [];

  /**
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $decorated
   */
  public function __construct(IdToSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id) {
    // @todo Optimize with isset()? But allow NULL values?
    return array_key_exists($id, $this->buffer)
      ? $this->buffer[$id]
      : $this->buffer[$id] = $this->decorated->idGetSchema($id);
  }
}
