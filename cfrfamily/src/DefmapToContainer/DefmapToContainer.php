<?php

namespace Drupal\cfrfamily\DefmapToContainer;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;

use Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainer_FromDefmap;

class DefmapToContainer implements DefmapToContainerInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   */
  private $definitionToConfigurator;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @param \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface $definitionToConfigurator
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   */
  public function __construct(
    DefinitionToConfiguratorInterface $definitionToConfigurator,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel
  ) {
    $this->definitionToConfigurator = $definitionToConfigurator;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
  }

  /**
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface
   */
  public function defmapGetContainer(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL) {
    return new CfrFamilyContainer_FromDefmap(
      $this->definitionToConfigurator,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $definitionMap,
      $context);
  }
}
