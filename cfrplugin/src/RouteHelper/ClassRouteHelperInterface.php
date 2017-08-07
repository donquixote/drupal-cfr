<?php

namespace Drupal\cfrplugin\RouteHelper;

interface ClassRouteHelperInterface {

  /**
   * @param string $methodName
   *
   * @return static
   */
  public function subpage($methodName);

  /**
   * @param $text
   * @param array $options
   *
   * @return \Drupal\Core\Link
   */
  public function link($text, array $options = []);

  /**
   * @param array $options
   *
   * @return \Drupal\Core\Url
   */
  public function url(array $options = []);

  /**
   * @return string
   */
  public function routeName();

}
