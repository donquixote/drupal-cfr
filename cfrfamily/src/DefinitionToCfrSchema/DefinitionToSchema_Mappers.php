<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\SchemaCreationException;
use Drupal\cfrfamily\DefinitionToCfrSchema\Helper\DefinitionToCfrSchemaHelper_Schema;
use Drupal\cfrfamily\DefinitionToCfrSchema\Helper\DefinitionToSchemaHelper_Handler;

class DefinitionToSchema_Mappers implements DefinitionToSchemaInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\Helper\DefinitionToSchemaHelperInterface[]
   */
  private $helpers;

  /**
   * @return self
   */
  public static function create() {
    return new self([
      'schema' => new DefinitionToCfrSchemaHelper_Schema(),
      // Any Configurator is also a CfrSchema.
      'configurator' => new DefinitionToCfrSchemaHelper_Schema(),
      'handler' => new DefinitionToSchemaHelper_Handler(),
    ]);
  }

  /**
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\Helper\DefinitionToSchemaHelperInterface[] $helpers
   */
  public function __construct(array $helpers) {
    $this->helpers = $helpers;
  }

  /**
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function definitionGetSchema(array $definition, CfrContextInterface $context = NULL) {

    foreach ($this->helpers as $prefix => $helper) {

      if (isset($definition[$key = $prefix . '_class'])) {
        $factory = CallbackReflection_ClassConstruction::createFromClassNameCandidate(
          $definition[$key]);
      }
      elseif (isset($definition[$key = $prefix . '_factory'])) {
        $factory = CallbackUtil::callableGetCallback($definition[$key]);
      }
      else {
        if (isset($definition[$prefix])) {
          $candidate = $definition[$prefix];
          if (!is_object($candidate)) {
            $export = var_export($candidate, TRUE);
            throw new SchemaCreationException("Candidate is non-object $export.");
          }
          return $helper->objectGetSchema($candidate);
        }
        continue;
      }

      if (!empty($definition[$argsKey = $prefix . '_arguments'])) {
        // Currying!
        $factory = new CallbackReflection_BoundParameters(
          $factory, $definition[$argsKey]);
      }

      return $helper->factoryGetSchema($factory, $context);
    }

    throw new SchemaCreationException("None of the mappers was applicable to the definition provided.");
  }
}
