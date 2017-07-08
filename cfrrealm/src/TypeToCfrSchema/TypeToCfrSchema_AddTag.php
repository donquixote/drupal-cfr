<?php

namespace Drupal\cfrrealm\TypeToCfrSchema;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrrealm\CfrSchema\CfSchema_Neutral_IfaceTransformed;

class TypeToCfrSchema_AddTag implements TypeToCfrSchemaInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface
   */
  private $decorated;

  /**
   * @param \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface $decorated
   */
  public function __construct(TypeToCfrSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($interface, CfrContextInterface $context = NULL) {

    return new CfSchema_Neutral_IfaceTransformed(
      $this->decorated->typeGetCfrSchema($interface, $context),
      $interface,
      $context);
  }
}
