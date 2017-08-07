<?php

namespace Drupal\cfrplugin\Controller;

use Drupal\Component\Utility\Html;
use Drupal\controller_annotations\Configuration\Route;
use Drupal\controller_annotations\RouteModifier\RouteAccessPublic;
use Drupal\controller_annotations\RouteModifier\RouteParameters;
use Drupal\Core\Entity\EntityInterface;

/**
 * @Route("test/x/")
 * @RouteAccessPublic()
 */
class Controller_TestX {

  /**
   * @Route("parameters/{user}")
   * @RouteParameters(user = "entity:user")
   *
   * @param \Drupal\Core\Entity\EntityInterface $user
   *
   * @return array
   */
  public function parameters(EntityInterface $user) {
    return ['#markup' => Html::escape($user->label())];
  }

}
