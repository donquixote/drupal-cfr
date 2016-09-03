<?php

namespace Drupal\cfrplugindiscovery\DocToAnnotations;

interface DocToAnnotationsInterface {

  /**
   * @param string|null $docComment
   *
   * @return array[]
   */
  public function docGetAnnotations($docComment);

}
