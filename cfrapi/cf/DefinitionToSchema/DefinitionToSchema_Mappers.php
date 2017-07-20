<?php

namespace Donquixote\Cf\DefinitionToSchema;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\DefinitionToSchema\Helper\DefinitionToSchemaHelper_Schema;
use Donquixote\Cf\DefinitionToSchema\Helper\DefinitionToSchemaHelper_Handler;
use Donquixote\Cf\Exception\CfSchemaCreationException;

class DefinitionToSchema_Mappers implements DefinitionToSchemaInterface {

  /**
   * @var \Donquixote\Cf\DefinitionToSchema\Helper\DefinitionToSchemaHelperInterface[]
   */
  private $helpers;

  /**
   * @return self
   */
  public static function create() {
    return new self([
      'schema' => new DefinitionToSchemaHelper_Schema(),
      // Any Configurator is also a CfSchema.
      'configurator' => new DefinitionToSchemaHelper_Schema(),
      'handler' => new DefinitionToSchemaHelper_Handler(),
    ]);
  }

  /**
   * @param \Donquixote\Cf\DefinitionToSchema\Helper\DefinitionToSchemaHelperInterface[] $helpers
   */
  public function __construct(array $helpers) {
    $this->helpers = $helpers;
  }

  /**
   * @param array $definition
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function definitionGetSchema(array $definition, CfContextInterface $context = NULL) {

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
            throw new CfSchemaCreationException("Candidate is non-object $export.");
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

    throw new CfSchemaCreationException("None of the mappers was applicable to the definition provided.");
  }
}
