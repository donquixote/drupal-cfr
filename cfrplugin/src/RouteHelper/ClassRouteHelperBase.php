<?php

namespace Drupal\cfrplugin\RouteHelper;

use Drupal\Core\Link;
use Drupal\Core\Url;

abstract class ClassRouteHelperBase implements ClassRouteHelperInterface {

  /**
   * @var array
   */
  private $routeParameters;

  /**
   * @var string
   */
  private $methodName;

  /**
   * @param array $routeParameters
   * @param string $suffix
   */
  public function __construct(array $routeParameters, $suffix) {
    $this->routeParameters = $routeParameters;
    $this->methodName = $suffix;
  }

  /**
   * @param string $suffix
   *
   * @return static
   */
  public function subpage($suffix) {
    $clone = clone $this;
    $clone->methodName = $suffix;
    return $clone;
  }

  /**
   * @param $text
   * @param array $options
   *
   * @return \Drupal\Core\Link
   */
  public function link($text, array $options = []) {

    return Link::fromTextAndUrl(
      $text,
      $this->url( $options));
  }

  /**
   * @param array $options
   *
   * @return \Drupal\Core\Url
   */
  public function url(array $options = []) {

    return new Url(
      $this->routeName(),
      $this->routeParameters);
  }

  /**
   * @return string
   */
  protected function getMethodName() {
    return $this->methodName;
  }
}
