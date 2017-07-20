<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Schema\Label\CfSchema_LabelInterface;

class Configurator_LabelSchema extends Configurator_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Schema\Label\CfSchema_LabelInterface
   */
  private $labelSchema;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $decorated
   * @param \Donquixote\Cf\Schema\Label\CfSchema_LabelInterface $labelSchema
   */
  public function __construct(
    ConfiguratorInterface $decorated,
    CfSchema_LabelInterface $labelSchema
  ) {
    parent::__construct($decorated);
    $this->labelSchema = $labelSchema;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {

    $paramLabel = $this->labelSchema->getLabel();

    if (NULL === $label) {
      $label = $paramLabel;
    }
    elseif (NULL !== $paramLabel) {
      $label .= ' | ' . $paramLabel;
    }

    return parent::confGetForm($conf, $label);
  }
}
