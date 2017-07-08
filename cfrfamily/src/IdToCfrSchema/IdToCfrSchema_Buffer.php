<?php

namespace Drupal\cfrfamily\IdToCfrSchema;

class IdToCfrSchema_Buffer implements IdToCfrSchemaInterface {

  /**
   * @var \Drupal\cfrfamily\IdToCfrSchema\IdToCfrSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $buffer = [];

  /**
   * @param \Drupal\cfrfamily\IdToCfrSchema\IdToCfrSchemaInterface $decorated
   */
  public function __construct(IdToCfrSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetCfrSchema($id) {
    // @todo Optimize with isset()? But allow NULL values?
    return array_key_exists($id, $this->buffer)
      ? $this->buffer[$id]
      : $this->buffer[$id] = $this->decorated->idGetCfrSchema($id);
  }
}
