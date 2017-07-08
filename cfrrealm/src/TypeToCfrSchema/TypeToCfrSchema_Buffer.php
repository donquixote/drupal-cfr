<?php

namespace Drupal\cfrrealm\TypeToCfrSchema;

use Drupal\cfrapi\Context\CfrContextInterface;

class TypeToCfrSchema_Buffer implements TypeToCfrSchemaInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $schemas = [];

  /**
   * @param \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface $decorated
   */
  public function __construct(TypeToCfrSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($type, CfrContextInterface $context = NULL) {
    return array_key_exists($type, $this->schemas)
      ? $this->schemas[$type]
      : $this->schemas[$type] = $this->decorated->typeGetCfrSchema($type, $context);
  }

}
