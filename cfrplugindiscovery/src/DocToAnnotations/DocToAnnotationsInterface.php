<?php

namespace Drupal\cfrplugindiscovery\DocToAnnotations;

interface DocToAnnotationsInterface {

  /**
   * @param string|null $docComment
   *
   * @return array[]
   */
  function docGetAnnotations($docComment);

}
