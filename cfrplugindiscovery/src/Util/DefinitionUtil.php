<?php

namespace Drupal\cfrplugindiscovery\Util;

final class DefinitionUtil extends UtilBase {

  /**
   * @param array $stubDefinition
   *   E.g. ['configurator_class' => 'MyConfigurator']
   * @param array[] $annotations
   *   E.g. [['id' => 'entityTitle', 'label' => 'Entity title'], ..]
   * @param string $fallbackId
   *   E.g. 'EntityTitle'.
   *
   * @return array[]
   */
  static function buildDefinitionsById($stubDefinition, array $annotations, $fallbackId) {

    $definitionsById = array();
    foreach ($annotations as $annotation) {

      if (isset($annotation['id'])) {
        $id = $annotation['id'];
      }
      elseif (isset($annotation[0])) {
        $id = $annotation[0];
      }
      else {
        $id = $fallbackId;
      }

      if (isset($annotation['label'])) {
        $label = $annotation['label'];
      }
      elseif (isset($annotation[1])) {
        $label = $annotation[1];
      }
      else {
        $label = $id;
      }

      $definitionsById[$id] = $stubDefinition;
      $definitionsById[$id]['label'] = $label;

      if (array_key_exists('inline', $annotation) && TRUE === $annotation['inline']) {
        $definitionsById[$id]['inline'] = TRUE;
      }
    }

    return $definitionsById;
  }

  /**
   * @param string[] $types
   * @param array[] $definitionsById
   *
   * @return array[][]
   */
  static function buildDefinitionsByTypeAndId(array $types, array $definitionsById) {

    $definitionsByTypeAndId = array();
    foreach ($types as $typeQcnString) {
      $definitionsByTypeAndId[$typeQcnString] = $definitionsById;
    }

    return $definitionsByTypeAndId;
  }
}
