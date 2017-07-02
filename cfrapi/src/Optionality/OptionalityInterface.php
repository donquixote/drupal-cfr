<?php

namespace Drupal\cfrapi\Optionality;

interface OptionalityInterface {

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function getConfigurator();

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  public function getEmptyness();

  /**
   * @param array $form
   *
   * @return mixed
   */
  public function formGetOptional(array $form);

}
